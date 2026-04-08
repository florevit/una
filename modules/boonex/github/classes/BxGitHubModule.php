<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    GitHub GitHub
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGitHubModule extends BxBaseModGeneralModule
{
    protected $_iLoggedId;

    protected $_oApi;

    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
        
        $this->_iLoggedId = bx_get_logged_profile_id();

        bx_import('Api', $aModule);
        $this->_oApi = new BxGitHubApi($this);
        $this->_oApi->init($this->_iLoggedId);
    }

    /**
     * ACTION METHODS
     */

    /**
     * SERVICE METHODS
     */
    public function serviceGetBlockSettings($iProfileId = 0)
    {
        if(!$iProfileId || ($iProfileId != $this->_iLoggedId && !$this->_isAdministrator()))
            $iProfileId = $this->_iLoggedId;

        if(!$iProfileId)
            return MsgBox(_t('_sys_txt_access_denied'));

        return $this->_oTemplate->getBlockSettings($iProfileId);
    }

    public function serviceGetRepositories($sUsername, $iProfileId = 0)
    {
        if($iProfileId && $iProfileId != $this->_iLoggedId)
            $this->_oApi->init($iProfileId);

        return $this->_oApi->getRepositories($sUsername);
    }

    public function serviceGetIssues($sUsername, $sRepository, $sState = 'open')
    {
        return $this->_oApi->getIssues($sUsername, $sRepository, $sState);
    }

    public function serviceGetIssue($sUsername, $sRepository, $iIssue)
    {
        return $this->_oApi->getIssue($sUsername, $sRepository, $iIssue);
    }

    public function serviceCreateIssue($sUsername, $sRepository, $sTitle, $sText = '', $aParams = [], $iProfileId = 0)
    {
        if($iProfileId && $iProfileId != $this->_iLoggedId)
            $this->_oApi->init($iProfileId);

        return $this->_oApi->createIssue($sUsername, $sRepository, $sTitle, $sText, $aParams);
    }

    public function serviceUpdateIssue($sUsername, $sRepository, $iIssue, $aParams, $iProfileId = 0)
    {
        if($iProfileId && $iProfileId != $this->_iLoggedId)
            $this->_oApi->init($iProfileId);

        return $this->_oApi->updateIssue($sUsername, $sRepository, $iIssue, $aParams);
    }

    public function serviceCloseIssue($sUsername, $sRepository, $iIssue, $iProfileId = 0)
    {
        if($iProfileId && $iProfileId != $this->_iLoggedId)
            $this->_oApi->init($iProfileId);

        return $this->_oApi->updateIssue($sUsername, $sRepository, $iIssue, ['state' => 'closed']);
    }

    public function serviceReopenIssue($sUsername, $sRepository, $iIssue, $iProfileId = 0)
    {
        if($iProfileId && $iProfileId != $this->_iLoggedId)
            $this->_oApi->init($iProfileId);

        return $this->_oApi->updateIssue($sUsername, $sRepository, $iIssue, ['state' => 'open']);
    }
     
    /*
     * COMMON METHODS
     */
}

/** @} */
