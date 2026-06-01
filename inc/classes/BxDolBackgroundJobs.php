<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

define('BX_DOL_BG_JOBS_STATUS_PENDING', 'pending');
define('BX_DOL_BG_JOBS_STATUS_PROCESSING', 'processing');
define('BX_DOL_BG_JOBS_STATUS_ERROR', 'error');

class BxDolBackgroundJobs  extends BxDolFactory implements iBxDolSingleton
{
    protected $_oQuery;

    protected $_sObjectLog;

    protected $_sParamWorkers;
    protected $_sParamWorkersLimit;

    protected $_iAttemptsMax;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_sObjectLog = 'sys_background_jobs';

        $this->_sParamWorkers = 'sys_bg_jobs_workers';
        $this->_sParamWorkersLimit = 'sys_bg_jobs_workers_limit';

        $this->_iAttemptsMax = 3;

        $this->_oQuery = new BxDolBackgroundJobsQuery();
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolBackgroundJobs();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public static function pruning()
    {
        return BxDolBackgroundJobs::getInstance()->prune(86400 * (int)getParam('sys_bg_jobs_cleanup_timeout'));
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public function add($sName, $mixedServiceCall, $iPriority = 0)
    {
        if(is_array($mixedServiceCall))
            $mixedServiceCall = call_user_func_array(['BxDolService', 'getSerializedService'], $mixedServiceCall);

        if(!$this->_oQuery->addJob($sName, $mixedServiceCall, $iPriority))
            return false;

        bx_log($this->_sObjectLog, "Added: " . $sName, BX_LOG_INFO);

        return true;
    }

    public function delete($sName)
    {
        if(!$this->_oQuery->deleteJob($sName))
            return false;

        bx_log($this->_sObjectLog, "Deleted: " . $sName, BX_LOG_INFO);

        return true;
    }

    public function exists($sName)
    {
        $aJob = $this->_oQuery->getJobs([
            'sample' => 'name', 
            'name' => $sName
        ]);

        return !empty($aJob) && is_array($aJob);
    }

    public function process($mixedJob)
    {
        if(!empty($mixedJob) && !is_array($mixedJob))
            $mixedJob = $this->_oQuery->getJobs(['sample' => 'name', 'name' => $mixedJob]);

        if(empty($mixedJob) || !is_array($mixedJob))
            return false;

        if(empty($mixedJob['service_call']) || !BxDolService::isSerializedService($mixedJob['service_call']))
            return false;

        if($mixedJob['status'] != BX_DOL_BG_JOBS_STATUS_PROCESSING)
            $this->_oQuery->updateJob($mixedJob['name'], [
                'status' => BX_DOL_BG_JOBS_STATUS_PROCESSING
            ]);

        $fStart = microtime(true);
        $mixedResult = BxDolService::callSerialized($mixedJob['service_call']);
        $fEnd = microtime(true);

        if($mixedResult !== true) {
            $iAvailableAt = 0;
            $sStatus = BX_DOL_BG_JOBS_STATUS_ERROR;

            $iAttempts = (int)$mixedJob['attempts'] - 1;
            if($iAttempts) {
                $iAvailableAt = time() + 60 * pow(3, ($this->_iAttemptsMax - $iAttempts));
                $sStatus = BX_DOL_BG_JOBS_STATUS_PENDING;
            }

            $this->_oQuery->updateJob($mixedJob['name'], [
                'reserved_at' => 0,
                'available_at' => $iAvailableAt,
                'attempts' => $iAttempts,
                'error' => $mixedResult ?: 'Exited with an error at ' . $fEnd,
                'status' => $sStatus
            ]);
        }
        else
            $this->_oQuery->deleteJob($mixedJob['name']);

        bx_log($this->_sObjectLog, "Processed: " . $mixedJob['name'] . " / timing: " . ($fEnd - $fStart) . " / memory: " . memory_get_usage(), BX_LOG_INFO);

        return true;
    }

    public function processAll($iLimit = 0)
    {
        $aJobs = $this->_oQuery->getJobs(['sample' => 'process', 'with_priority' => true, 'limit' => $iLimit ?: (getParam('sys_bg_jobs_process_per_run') ?: 5)]);
        if(!$aJobs || !is_array($aJobs))
            return true;

        if(!$this->_startWorker())
            return false;

        $this->_oQuery->updateJobByIds(array_keys($aJobs), [
            'reserved_at' => time()
        ]);

        $iProcessed = 0;
        foreach($aJobs as $aJob)
            if($this->process($aJob))
                $iProcessed += 1;

        bx_log($this->_sObjectLog, "Processed: all (" . $iProcessed . " from " . count($aJobs) . ")", BX_LOG_INFO);

        return $this->_stopWorker();
    }

    public function prune($iTimeout, $sStatus = BX_DOL_BG_JOBS_STATUS_ERROR)
    {
        return (int)$this->_oQuery->deleteJobs([
            'sample' => 'outdated', 
            'timeout' => $iTimeout, 
            'status' => $sStatus
        ]);
    }

    protected function _startWorker()
    {
        $iWorkers = 0;
        if(($iWorkers = (int)getParam($this->_sParamWorkers)) >= (int)getParam($this->_sParamWorkersLimit))
            return false;

        return setParam($this->_sParamWorkers, $iWorkers + 1);
    }

    protected function _stopWorker()
    {
        return setParam($this->_sParamWorkers, (int)getParam($this->_sParamWorkers) - 1);
    }
}

/** @} */
