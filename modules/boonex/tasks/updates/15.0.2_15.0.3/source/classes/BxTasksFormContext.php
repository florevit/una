<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTasksFormContext extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_tasks';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);
    }

    public function setProfileId($iProfileId)
    {
        if(($sKey = 'gh_app_id') && isset($this->aInputs[$sKey])) {
            $sModule = 'bx_github';
            $sMethod = 'get_apps';
            if(bx_is_srv($sModule, $sMethod)) {
                $aApps = bx_srv($sModule, $sMethod, [$iProfileId]);
                if($aApps !== false) {
                    $this->aInputs[$sKey]['values'][] = [
                        'key' => '',
                        'value' => _t('_sys_please_select'),
                    ];

                    foreach($aApps as $aApp)
                        $this->aInputs[$sKey]['values'][] = [
                            'key' => $aApp['id'],
                            'value' => $aApp['title'],
                        ];
                }
                else
                    unset($this->aInputs[$sKey]);
            }
        }
    }
}

/** @} */
