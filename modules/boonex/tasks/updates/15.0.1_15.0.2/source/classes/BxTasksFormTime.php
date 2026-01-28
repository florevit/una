<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxTasksFormTime extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_tasks';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aInfo, $oTemplate);

    	if(($sKey = 'value') && isset($this->aInputs[$sKey])) { 
            foreach($this->aInputs[$sKey] as $mixedKey => $mixedValue) {
                if(!is_numeric($mixedKey) || !is_array($mixedValue))
                    continue;

                if(!empty($mixedValue['attrs']['placeholder']))
                    $this->aInputs[$sKey][$mixedKey]['attrs']['placeholder'] = _t($mixedValue['attrs']['placeholder']);
            }
        }
    }
}

/** @} */
