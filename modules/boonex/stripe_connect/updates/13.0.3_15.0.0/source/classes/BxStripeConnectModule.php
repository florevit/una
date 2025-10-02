<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     TridentModules
 *
 * @{
 */

define('BX_STRIPE_CONNECT_MODE_LIVE', 'live');
define('BX_STRIPE_CONNECT_MODE_TEST', 'test');

define('BX_STRIPE_CONNECT_PMODE_DIRECT', 'direct');
define('BX_STRIPE_CONNECT_PMODE_PLATFORM', 'platform');

class BxStripeConnectModule extends BxBaseModGeneralModule
{
    public function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    /**
     * 
     * SERVICE METHODS
     * 
     */
    public function serviceGetSafeServices()
    {
        return array_merge(parent::serviceGetSafeServices(), [
            'AccountSessionCreate' => '',
            'AccountCreate' => '',
            'AccountContinue' => '',
            'AccountDelete' => ''
        ]);
    }

    public function serviceAccountCreate($iId)
    {
        $CNF = &$this->_oConfig->CNF;

        $sError = _t('_bx_stripe_connect_err_perform');
    	if(empty($iId) || $iId != bx_get_logged_profile_id())
            return ['code' => 1, 'message' => $sError];

        $oProfile = BxDolProfile::getInstance($iId);
        if(!$oProfile)
            return ['code' => 1, 'message' => $sError];

        $sAccIdField = $CNF['FIELD_' . strtoupper($this->_oConfig->getMode()) . '_ACCOUNT_ID'];

        $oApi = BxStripeConnectApi::getInstance();        
        $oAccount = $oApi->createAccount($oProfile->getAccountObject()->getEmail());
        if(!$oAccount)
            return ['code' => 1, 'message' => $sError];

        $this->_oDb->insertAccount([
            'profile_id' => $iId,
            $sAccIdField => $oAccount->id
        ]);

        $sRefreshLink = $this->_oConfig->getRefreshLink();
        $sReturnLink = $this->_oConfig->getReturnLink();
        $oAccountLink = $oApi->createAccountLinks($oAccount->id, $sRefreshLink, $sReturnLink);
        if(!$oAccountLink)
            return ['code' => 1, 'message' => $sError];

        return ['code' => 0, 'redirect' => $oAccountLink->url];
    }

    public function serviceAccountContinue($iId)
    {
        $CNF = &$this->_oConfig->CNF;

        $sError = _t('_bx_stripe_connect_err_perform');
    	if(empty($iId) || $iId != bx_get_logged_profile_id())
            return ['code' => 1, 'message' => $sError];

        $sModeUc = strtoupper($this->_oConfig->getMode());
        $sAccIdField = $CNF['FIELD_' . $sModeUc . '_ACCOUNT_ID'];
        $sAccDetailsField = $CNF['FIELD_' . $sModeUc . '_DETAILS'];

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iId]);
        if(empty($aAccount) || !is_array($aAccount) || $aAccount[$sAccIdField] == '')
            return ['code' => 1, 'message' => $sError];

        if((int)$aAccount[$sAccDetailsField] != 0)
            return ['code' => 0];

        $sRefreshLink = $this->_oConfig->getRefreshLink();
        $sReturnLink = $this->_oConfig->getReturnLink();
        $oAccountLink = BxStripeConnectApi::getInstance()->createAccountLinks($aAccount[$sAccIdField], $sRefreshLink, $sReturnLink);
        if(!$oAccountLink)
            return ['code' => 1, 'message' => $sError];

        return ['code' => 0, 'redirect' => $oAccountLink->url];
    }

    public function serviceAccountDelete($iId)
    {
        $CNF = &$this->_oConfig->CNF;

        $sError = _t('_bx_stripe_connect_err_perform');
    	if(empty($iId) || $iId != bx_get_logged_profile_id())
            return ['code' => 1, 'message' => $sError];

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iId]);
        if(empty($aAccount) || !is_array($aAccount))
            return ['code' => 1, 'message' => $sError];

        $sAccIdField = $CNF['FIELD_' . strtoupper($this->_oConfig->getMode()) . '_ACCOUNT_ID'];

        $oAccount = BxStripeConnectApi::getInstance()->deleteAccount($aAccount[$sAccIdField]);
        if(!$oAccount || !$oAccount->deleted)
            return ['code' => 2, 'message' => _t('_bx_stripe_connect_err_delete')];

        $this->_oDb->deleteAccount([
            'profile_id' => $iId,
            $sAccIdField => $oAccount->id
        ]);

        return ['code' => 0, 'reload' => 1];
    }

    public function serviceAccountSessionCreate($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$iProfileId || ($iProfileId != $this->_iProfileId && !isAdmin()))
            $iProfileId = $this->_iProfileId;
        if(!$iProfileId)
            return ['code' => 1];

        $sModeUc = strtoupper($this->_oConfig->getMode());
        $sAccIdField = $CNF['FIELD_' . $sModeUc . '_ACCOUNT_ID'];

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iProfileId]);
        if(empty($aAccount) || !is_array($aAccount) || $aAccount[$sAccIdField] == '')
            return ['code' => 1];

        $oAccountSession = BxStripeConnectApi::getInstance()->createAccountSessions($aAccount[$sAccIdField]);
        if(!$oAccountSession)
            return ['code' => 1];

        return [
            'code' => 0,
            'secret' => $oAccountSession->client_secret
        ];
    }

    /**
     * @page service Service Calls
     * @section bx_stripe_connect Stripe Connect
     * @subsection bx_stripe_connect-other Other
     * @subsubsection bx_stripe_connect-get_options_mode get_options_mode
     * 
     * @code bx_srv('bx_stripe_connect', 'get_options_mode', [...]); @endcode
     * 
     * Get an array with available modes. Is used in forms.
     *
     * @return an array with available modes represented as key => value pairs.
     * 
     * @see BxStripeConnectModule::serviceGetOptionsMode
     */
    /** 
     * @ref bx_stripe_connect-get_options_mode "get_options_mode"
     */
    public function serviceGetOptionsMode()
    {
        return [
            BX_STRIPE_CONNECT_MODE_LIVE => _t('_bx_stripe_connect_option_mode_live'),
            BX_STRIPE_CONNECT_MODE_TEST => _t('_bx_stripe_connect_option_mode_test'),
        ];
    }

    /**
     * @page service Service Calls
     * @section bx_stripe_connect Stripe Connect
     * @subsection bx_stripe_connect-other Other
     * @subsubsection bx_stripe_connect-get_options_pmode get_options_pmode
     * 
     * @code bx_srv('bx_stripe_connect', 'get_options_pmode', [...]); @endcode
     * 
     * Get an array with available processing modes for payments. Is used in forms.
     *
     * @return an array with available modes represented as key => value pairs.
     * 
     * @see BxStripeConnectModule::serviceGetOptionsPmode
     */
    /** 
     * @ref bx_stripe_connect-get_options_pmode "get_options_pmode"
     */
    public function serviceGetOptionsPmode()
    {
        return [
            BX_STRIPE_CONNECT_PMODE_DIRECT => _t('_bx_stripe_connect_option_pmode_direct'),
            //BX_STRIPE_CONNECT_PMODE_PLATFORM => _t('_bx_stripe_connect_option_pmode_platform'),
        ];
    }

    public function serviceGetOptionValueMode($iVendorId, $aParams = [])
    {
        return _t('_bx_stripe_connect_option_mode_' . $this->_oConfig->getMode());
    }

    public function serviceGetOptionValueLiveAccountId($iVendorId, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iVendorId]);
        if(empty($aAccount) || !is_array($aAccount))
            return '';

        return $aAccount[$CNF['FIELD_LIVE_ACCOUNT_ID']];
    }

    public function serviceGetOptionValueTestAccountId($iVendorId, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iVendorId]);
        if(empty($aAccount) || !is_array($aAccount))
            return '';

        return $aAccount[$CNF['FIELD_TEST_ACCOUNT_ID']];
    }

    public function serviceGetConnectCode($iVendorId, $aParams = [])
    {
        return $this->_oTemplate->getConnectCode($iVendorId, $aParams);
    }

    public function serviceGetBlockPayments()
    {
        if(!isLogged())
            return $this->_bIsApi ? [] : '';
        
        if($this->_bIsApi)
            return [bx_api_get_block('stripe_connect', [
                'key' => $this->_oConfig->getApiPublicKey(),
                'embed' => 'payments'
            ])];

        return $this->_oTemplate->getBlockPayments();
    }

    /*
     * Isn't supported with the current SDK. It's still in Beta mode.
     * https://docs.stripe.com/connect/supported-embedded-components/reporting-chart
     */
    public function serviceGetBlockReportingChart()
    {
        if(!isLogged())
            return $this->_bIsApi ? [] : '';

        if($this->_bIsApi)
            return [bx_api_get_block('stripe_connect', [
                'key' => $this->_oConfig->getApiPublicKey(),
                'embed' => 'reporting-chart'
            ])];
        
        return $this->_oTemplate->getBlockReportingChart();
    }

    public function serviceGetBlockBalances()
    {
        if(!isLogged())
            return $this->_bIsApi ? [] : '';

        if($this->_bIsApi)
            return [bx_api_get_block('stripe_connect', [
                'key' => $this->_oConfig->getApiPublicKey(),
                'embed' => 'balances'
            ])];

        return $this->_oTemplate->getBlockBalances();
    }

    public function serviceGetBlockNotifications()
    {
        if(!isLogged())
            return $this->_bIsApi ? [] : '';

        if($this->_bIsApi)
            return [bx_api_get_block('stripe_connect', [
                'key' => $this->_oConfig->getApiPublicKey(),
                'embed' => 'notification-banner'
            ])];

        return $this->_oTemplate->getBlockNotifications();
    }

    /**
     * 
     * ACTION METHODS
     * 
     */
    public function actionNotify()
    {
        $iResult = $this->_processEvent();
        http_response_code($iResult);
    }

    public function actionAccountCreate()
    {
        return echoJson($this->serviceAccountCreate((int)bx_get('id')));
    }

    public function actionAccountContinue()
    {
        return echoJson($this->serviceAccountContinue((int)bx_get('id')));
    }

    public function actionAccountDelete()
    {
        return echoJson($this->serviceAccountDelete((int)bx_get('id')));
    }

    public function actionAccountSessionCreate($iProfileId)
    {
        return echoJson($this->serviceAccountSessionCreate($iProfileId));
    }

    /**
     * 
     * OTHER METHODS
     * 
     */
    public function hasAccount($iVendorId)
    {
        return $this->_oDb->hasAccount($iVendorId, $this->_oConfig->getMode());
    }

    public function processAlertStripeV3GetButton($iObject, $iSender, $aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $iVendorId = 0;
        if(empty($aParams['vendor_id']) || ($iVendorId = (int)$aParams['vendor_id']) == $this->_oConfig->getMainSeller())
            return false;

        $sModeUc = strtoupper($this->_oConfig->getMode());
        $sAccIdField = $CNF['FIELD_' . $sModeUc . '_ACCOUNT_ID'];

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iVendorId]);
        if(empty($aAccount) || !is_array($aAccount) || empty($aAccount[$sAccIdField]))
            return false;

        $aParams['public_key'] = $this->_oConfig->getApiPublicKey();
        $aParams['connected_account_id'] = $aAccount[$sAccIdField];

        return !empty($aParams['public_key']);
    }

    public function processAlertStripeV3CreateSession($iObject, $iSender, $aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParams['session_params']))
            return false;

        try {
            $sModeUc = strtoupper($this->_oConfig->getMode());
            $sAccIdField = $CNF['FIELD_' . $sModeUc . '_ACCOUNT_ID'];
            
            $iVendorId = (int)$aParams['session_params']['metadata']['vendor'];
            if($iVendorId == $this->_oConfig->getMainSeller())
                return false;

            $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iVendorId]);
            if(empty($aAccount) || !is_array($aAccount) || empty($aAccount[$sAccIdField]))
                return false;

            switch($aParams['session_params']['mode']) {
                case 'payment':
                    $fAmount = 0;
                    foreach($aParams['session_params']['line_items'] as $aLineItem)
                        $fAmount += (float)$aLineItem['price_data']['unit_amount'] * (int)$aLineItem['quantity'];

                    if($fAmount && ($fAmount = $this->_oConfig->getFee(BX_PAYMENT_TYPE_SINGLE, $iVendorId, $fAmount))) {
                        if(!isset($aParams['session_params']['payment_intent_data']))
                            $aParams['session_params']['payment_intent_data'] = [];

                        $aParams['session_params']['payment_intent_data']['application_fee_amount'] = $fAmount;
                    }
                    break;

                case 'subscription':
                    $fAmount = 0;
                    foreach($aParams['session_params']['line_items'] as $aLineItem)
                        $fAmount += (float)$aLineItem['price_data']['unit_amount'] * (int)$aLineItem['quantity'];

                    if($fAmount && ($fAmount = $this->_oConfig->getFee(BX_PAYMENT_TYPE_RECURRING, $iVendorId, $fAmount))) {
                        if(!isset($aParams['session_params']['subscription_data']))
                            $aParams['session_params']['subscription_data'] = [];

                        $aParams['session_params']['subscription_data']['application_fee_percent'] = $fAmount;
                    }

                    break;
            }

            $aParams['session_object'] = BxStripeConnectApi::getInstance()->getStripe()->checkout->sessions->create($aParams['session_params'], [
                'stripe_account' => $aAccount[$sAccIdField]
            ]);
        }
        catch (Exception $oException) {
            $aParams['session_object'] = null;

            return $this->_processException('Create Session Error: ', $oException);
        }

        return !empty($aParams['session_object']);
    }

    protected function _processEvent()
    {
        $sInput = @file_get_contents("php://input");
        $aEvent = json_decode($sInput, true);
        if(empty($aEvent) || !is_array($aEvent)) 
            return 404;

        $sType = $aEvent['type'];
        if(!in_array($sType, ['account.updated']))
            return 200;

        $this->_log('Webhooks: ' . (!empty($sType) ? $sType : ''));
        $this->_log($aEvent);

        $sMethod = '_processEvent' . bx_gen_method_name($sType, array('.', '_', '-'));
        if(!method_exists($this, $sMethod))
            return 200;

    	return $this->$sMethod($aEvent) ? 200 : 403;
    }

    protected function _processEventAccountUpdated(&$aEvent)
    {
        $CNF = $this->_oConfig->CNF;

        $sAccountId = '';
        if(!isset($aEvent['data']['object']['id']) || !($sAccountId = bx_process_input($aEvent['data']['object']['id'])))
            return false;

        $sMode = $this->_oConfig->getMode();
        $sAccDetailsField = $CNF['FIELD_' . strtoupper($sMode) . '_DETAILS'];

        $aAccount = $this->_oDb->getAccount(['sample' => 'account_id', $sMode . '_account_id' => $sAccountId]);
        if((int)$aAccount[$sAccDetailsField] == 0 && $aEvent['data']['object']['details_submitted'])
            return $this->_oDb->updateAccount([$sAccDetailsField => 1], [$CNF['FIELD_ID'] => $aAccount[$CNF['FIELD_ID']]]);

        return true;
    }

    protected function _processException($sMessage, &$oException)
    {
        $aError = $oException->getJsonBody();

        $sMessage = $aError['error']['message'];
        if(empty($sMessage))
            $sMessage = $oException->getMessage();

        $this->_log($sMessage . $aError['error']['message']);
        $this->_log($aError);

        return false;
    }

    protected function _log($sContents)
    {
        if (is_array($sContents))
            $sContents = var_export($sContents, true);	
        else if (is_object($sContents))
            $sContents = json_encode($sContents);

        bx_log('bx_stripe_connect', $sContents, BX_LOG_ERR);
    }
}

/** @} */
