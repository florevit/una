<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxTasksTimeQuery.php');

class BxTasksTime extends BxTemplReport
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_sModule = 'bx_tasks';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_oQuery = new BxTasksTimeQuery($this);

        $this->_bUndo = false;
        $this->_bProcessible = false;

        $this->_sFormObject = $CNF['OBJECT_FORM_TIME'];
        $this->_sFormDisplayPost = $CNF['OBJECT_FORM_TIME_DISPLAY_ADD'];

        array_walk($this->_aHtmlIds, function(&$sVa1ue) {
            $sVa1ue = str_replace('bx-report', 'bx-tasks', $sVa1ue);
        });
    }

    public function report($aParams = [])
    {
        $mixedResult = parent::report($aParams);
        if(!empty($mixedResult) && is_array($mixedResult) && isset($mixedResult['popup'], $mixedResult['popup_id']))
            $mixedResult['popup'] = [
                'html' => $mixedResult['popup'], 
                'options' => ['closeOnOuterClick' => false]
            ];

        return $mixedResult;
    }

    /**
     * Should always return false to allow any number of time reports per user.
     */
    public function isPerformed($iObjectId, $iAuthorId, $iAuthorIp = 0)
    {
        return false;
    }

    public function putReport($iObjectId, $iAuthorId, $mixedTrack, $bUndo = false)
    {
        if(!$this->_oQuery->putReport($iObjectId, $iAuthorId, $bUndo))
            return false;

        $aTrack = is_array($mixedTrack) ? $mixedTrack : $this->_oQuery->getTrackBy(['type' => 'id', 'id' => (int)$mixedTrack]);
        if(empty($aTrack) || !is_array($aTrack))
            return false;

        if(!$bUndo && empty($aTrack['value_date']) && !$this->_oQuery->updateTrack(['value_date' => time()], ['id' => $aTrack['id']]))
            return false;

        if(!$this->_oQuery->updateReport($iObjectId, $aTrack['value'], $bUndo))
            return false;

        $iObjectAuthorId = $this->_oQuery->getObjectAuthorId($iObjectId);

        /*
         * TODO: May be we need to send notification to Context admins.
         * 
        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_Reported', [
           'report_type' => $sType,
           'report_text' => $sText,
           'report_url' => $this->getBaseUrl(),
        ]);
        if($aTemplate)
           sendMail(getParam('site_email'), $aTemplate['Subject'], $aTemplate['Body']);
        */          

        $this->_trigger();

        /**
         * @hooks
         * @hookdef bx_tasks-report_time '{module_name}', 'report_time' - hook on create new time report 
         * - $unit_name - module name
         * - $action - equals `report_time` 
         * - $object_id - reported entry ID
         * - $sender_id - profile id for report's author
         * - $extra_params - array of additional params with the following array keys:
         *      - `object_system` - [string] system name
         *      - `object_author_id` - [int] author's profile_id for reported object_id 
         *      - `report_id` - [int] report id
         *      - `report_author_id` - [int] profile id for report's author
         *      - `type` - [string] reported time
         * @hook @ref hook-bx_tasks-report_time
         */
        bx_alert($this->_sModule, 'report_time', $iObjectId, $iAuthorId, [
            'object_system' => $this->_sSystem, 
            'object_author_id' => $iObjectAuthorId, 
            'report_id' => $aTrack['id'], 
            'report_author_id' => $iAuthorId, 
            'value' => $aTrack['value']
        ]);

        return true;
    }

    protected function _report($bPerformed, $aParams, &$oForm)
    {
        $iAuthorId = $this->_getAuthorId();
        $iAuthorNip = bx_get_ip_hash($this->_getAuthorIp());

        $iObjectId = $this->_bApi ? $this->_iId : $oForm->getCleanValue('object_id');

        if(!$this->isAllowedReport(true))
            return ['code' => 2, 'message' => $this->msgErrAllowedReport()];

        $iVh = ($sKey = 'value_h') && $this->_bApi ? $aParams[$sKey] : $oForm->getCleanValue($sKey);
        $iVm = ($sKey = 'value_m') && $this->_bApi ? $aParams[$sKey] : $oForm->getCleanValue($sKey);
        $iValue = $this->_oModule->_oConfig->timeA2I([$iVh, $iVm]);

        $sText = $this->_bApi ? $aParams['text'] : $oForm->getCleanValue('text');
        $sText = bx_process_input($sText);

        $iId = (int)$oForm->insert(['object_id' => $iObjectId, 'author_id' => $iAuthorId, 'author_nip' => $iAuthorNip, 'value' => $iValue,  'text' => $sText,  'date' => time()]);
        if($iId != 0 && $this->putReport($iObjectId, $iAuthorId, $iId)) {
            $aReport = $this->_getReport($iObjectId, true);
            $aResult = $this->_returnReportData($iObjectId, $iAuthorId, $iId, $aReport, !$bPerformed);

            if(($oSockets = BxDolSockets::getInstance()) && $oSockets->isEnabled())
                $oSockets->sendEvent($this->getSocketName(), $iObjectId, 'reported', json_encode($this->_returnReportDataForSocket($aResult)));

            return $aResult;
        }

        return ['code' => 3, 'message' => _t('_report_err_cannot_perform_action')];
    }

    protected function _returnReportData($iObjectId, $iAuthorId, $iReportId, $aData, $bPerformed)
    {
        return parent::_returnReportData($iObjectId, $iAuthorId, $iReportId, $aData, false);
    }

    protected function _getReportedBy()
    {
        $aTmplReports = [];

        $aReports = $this->_oQuery->getPerformedBy($this->getId());
        foreach($aReports as $aReport) {
            list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $this->_getAuthorInfo($aReport['author_id']);

            $sText = bx_process_output($aReport['text'], BX_DATA_TEXT_MULTILINE);

            $aTmplReports[] = [
                'style_prefix' => $this->_sStylePrefix,
                'user_unit' => $sUserUnit,
                'value' => $this->_oModule->_oConfig->timeI2S($aReport['value']),
                'date' => $aReport['value_date'] ? bx_time_js($aReport['value_date'], BX_FORMAT_DATE, true) : '',
            	'bx_if:show_text' => [
                    'condition' => strlen($sText) > 0,
                    'content' => [
                        'text' => $sText
                    ]
            	]
            ];
        }

        if(empty($aTmplReports))
            $aTmplReports = MsgBox(_t('_Empty'));

        return $this->_oModule->_oTemplate->parseHtmlByName('report_by_list.html', [
            'style_prefix' => $this->_sStylePrefix,
            'bx_repeat:list' => $aTmplReports
        ]);
    }

    protected function _getIconDoReport($bPerformed)
    {
    	return $bPerformed && $this->isUndo() ? 'stopwatch' : 'stopwatch';
    }

    protected function _getTitleDoReport($bPerformed)
    {
        return ['_bx_tasks_report_time_do_' . ($bPerformed && $this->isUndo() ? 'un' : '') . 'report'];
    }
}

/** @} */
