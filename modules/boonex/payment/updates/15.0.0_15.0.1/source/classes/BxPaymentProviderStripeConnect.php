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

class BxPaymentProviderStripeConnect extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
    protected $_sModuleSc;

    public function __construct($aConfig)
    {
        $this->MODULE = 'bx_payment';

        parent::__construct($aConfig);

        $this->_sModuleSc = 'bx_stripe_connect';
    }

    public function getMode($iVendorId, $aParams = [])
    {
        if(($sMethod = 'get_option_value_mode') && bx_is_srv($this->_sModuleSc, $sMethod))
            return bx_srv($this->_sModuleSc, $sMethod, [$iVendorId, $aParams]);
        else
            return _t('_undefined');
    }

    public function getConnect($iVendorId, $aParams = [])
    {
        if(($sMethod = 'get_connect_code') && bx_is_srv($this->_sModuleSc, $sMethod))
            return bx_srv($this->_sModuleSc, $sMethod, [$iVendorId, $aParams]);
        else
            return _t('_undefined');
    }

    public function getLiveAccountId($iVendorId, $aParams = [])
    {
        if(($sMethod = 'get_option_value_live_account_id') && bx_is_srv($this->_sModuleSc, $sMethod) && ($sAccountId = bx_srv($this->_sModuleSc, $sMethod, [$iVendorId, $aParams])))
            return $sAccountId;
        else
            return _t('_undefined');
    }

    public function getTestAccountId($iVendorId, $aParams = [])
    {
        if(($sMethod = 'get_option_value_test_account_id') && bx_is_srv($this->_sModuleSc, $sMethod) && ($sAccountId = bx_srv($this->_sModuleSc, $sMethod, [$iVendorId, $aParams])))
            return $sAccountId;
        else
            return _t('_undefined');
    }

    public function initializeCheckout($iPendingId, $aCartInfo, $sRedirect = '')
    {
        return $this->_sLangsPrefix . 'err_not_supported';
    }

    public function finalizeCheckout(&$aData)
    {
        return ['code' => 1, 'message' => $this->_sLangsPrefix . 'err_not_supported'];
    }
}

/** @} */
