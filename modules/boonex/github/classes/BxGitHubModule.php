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

/*
 * 
 * Creating a fine-grained personal access token
 * https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens#creating-a-fine-grained-personal-access-token
 * 
 * Creating a personal access token (classic)
 * https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens#creating-a-personal-access-token-classic
 * 
 * Web application flow
 * https://docs.github.com/en/apps/oauth-apps/building-oauth-apps/authorizing-oauth-apps#web-application-flow
 * 
 */

class BxGitHubModule extends BxBaseModGeneralModule
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    /**
     * ACTION METHODS
     */

    /**
     * SERVICE METHODS
     */
    public function serviceGetBlockSettings($iProfileId = 0)
    {
        $iLoggedId = bx_get_logged_profile_id();
        if(!$iProfileId || ($iProfileId != $iLoggedId && !$this->_isAdministrator()))
            $iProfileId = $iLoggedId;

        if(!$iProfileId)
            return MsgBox(_t('_sys_txt_access_denied'));

        return $this->_oTemplate->getBlockSettings($iProfileId);
    }

    public function serviceAddIssue($iProfileId, $sRepository, $sTitle, $sText = '')
    {
        //TODO: Create Issue in Repository.
    }

    /*
     * COMMON METHODS
     */
}

/** @} */
