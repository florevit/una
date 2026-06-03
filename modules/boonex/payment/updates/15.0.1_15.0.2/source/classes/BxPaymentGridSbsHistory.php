<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 * 
 * @{
 */


require_once('BxPaymentGridHistory.php');

class BxPaymentGridSbsHistory extends BxPaymentGridHistory
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getCellItemId($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oModule->_oConfig->descriptorA2S([$aRow['seller_id'], $aRow['module_id'], $mixedValue, $aRow['item_count']]);

        return parent::_getCellItems($mixedValue, $sKey, $aField, $aRow);
    }
}

/** @} */
