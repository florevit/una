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

class BxGitHubConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;

    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
            // database tables
            'TABLE_SETTINGS' => $aModule['db_prefix'] . 'settings',

            // database fields
            'FIELD_' => '',

            // some params
            'PARAM_' => 'bx_github_',

            // objects
            'OBJECT_FORM_SETTINGS' => 'bx_github_form_settings',
            'OBJECT_FORM_SETTINGS_DISPLAY_EDIT' => 'bx_github_form_settings_edit',
            'OBJECT_LOG_API' => 'bx_github_api'
        );
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;

        //NOTE: Some settings can be inited here.
    }
}

/** @} */
