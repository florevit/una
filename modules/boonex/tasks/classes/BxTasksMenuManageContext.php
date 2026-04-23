<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTasksMenuManageContext extends BxTemplMenu
{
    protected $_sModule;
    protected $_oModule;

    protected $_sParamName;
    protected $_iParamValue;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_tasks';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $oTemplate);

        $this->_sParamName = 'context_pid';
        if(($iParamValue = bx_get($this->_sParamName)) !== false)
            $this->_iParamValue = (int)$iParamValue;
        
        $this->addMarkers([
            'js_object_view' => $this->_oModule->_oConfig->getJsObject('tasks'),
            $this->_sParamName => $this->_iParamValue
        ]);
    }
}

/** @} */
