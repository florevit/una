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

require_once('BxStripeConnectApi.php');

class BxStripeConnectTemplate extends BxBaseModGeneralTemplate
{
    public function __construct($oConfig, $oDb)
    {
    	$this->MODULE = 'bx_stripe_connect';

        parent::__construct($oConfig, $oDb);
    }

    public function getConnectCode($iVendorId, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $sJsObject = $this->_oConfig->getJsObject('main');
 
        $sModeUc = strtoupper($this->_oConfig->getMode());
        $sAccIdField = $CNF['FIELD_' . $sModeUc . '_ACCOUNT_ID'];
        $sAccDetailsField = $CNF['FIELD_' . $sModeUc . '_DETAILS'];
 
        $aApiButtons = [];
        $bShowContinue = false;
        $sActionMethod = $sActionTitle = '';
        if(($aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iVendorId])) && is_array($aAccount) && $aAccount[$sAccIdField] != '') {
            if(($bShowContinue = (int)$aAccount[$sAccDetailsField] == 0)) {
                $oAccount = BxStripeConnectApi::getInstance()->retrieveAccount($aAccount[$sAccIdField]);
                if($oAccount->details_submitted) {
                    $this->_oDb->updateAccount([$sAccDetailsField => 1], [$CNF['FIELD_ID'] => $aAccount[$CNF['FIELD_ID']]]);

                    $bShowContinue = false;
                }
            }

            $sActionMethod = 'accountDelete';
            $sActionTitle = '_bx_stripe_connect_btn_account_delete';

            if($this->_bIsApi) {
                if($bShowContinue)
                    $aApiButtons[] = [
                        'title' => _t('_bx_stripe_connect_btn_account_continue'),
                        'callback' => '/api.php?r=' . $this->MODULE . '/account_continue&params[0]=' . $iVendorId
                    ];

                $aApiButtons[] = [
                    'title' => _t($sActionTitle),
                    'callback' => '/api.php?r=' . $this->MODULE . '/account_delete&params[0]=' . $iVendorId
                ];
            }
        }
        else {
            $sActionMethod = 'accountCreate';
            $sActionTitle = '_bx_stripe_connect_btn_account_create';

            if($this->_bIsApi)
                $aApiButtons[] = [
                    'title' => _t($sActionTitle),
                    'callback' => '/api.php?r=' . $this->MODULE . '/account_create&params[0]=' . $iVendorId
                ];
        }

        if($this->_bIsApi)
            return [
                'type' => 'stripe_connect',
                'content' =>  $aApiButtons
            ];

        $this->addJs(['main.js']);
        return $this->parseHtmlByName('connect_code.html', [
            'js_object' => $sJsObject,
            'js_code' => $this->getJsCode('main'),
            'profile_id' => $iVendorId,
            'action_method' => $sActionMethod,
            'action_title' => _t($sActionTitle),
            'bx_if:show_continue' => [
                'condition' => $bShowContinue,
                'content' => [
                    'js_object' => $sJsObject,
                    'profile_id' => $iVendorId,
                ]
            ]
        ]);
    }

    public function getJsCodeEmbeds($iProfileId)
    {
        $this->addJs([
            'https://connect-js.stripe.com/v1.0/connect.js',
            'embeds.js'
        ]);

        return $this->getJsCode('embeds', [
            'aHtmlIds' => $this->_oConfig->getHtmlIds(),
            'sPublicKey' => $this->_oConfig->getApiPublicKey(),
            'iProfileId' => $iProfileId
        ], [
            'wrap' => true,
            'mask' => "if(window['{object}'] == undefined) {var} {object} = new {class}({params});"
        ]);
    }

    public function getBlockReportingChart()
    {
        $sEmbed = 'reporting-chart';

        return $this->parseHtmlByName('embed_block.html', [
            'js_object' => $this->_oConfig->getJsObject('embeds'),
            'html_id' => $this->_oConfig->getHtmlIds($sEmbed),
            'embed' => $sEmbed
        ]);
    }

    public function getBlockPayments()
    {
        $sEmbed = 'payments';

        return $this->parseHtmlByName('embed_block.html', [
            'js_object' => $this->_oConfig->getJsObject('embeds'),
            'html_id' => $this->_oConfig->getHtmlIds($sEmbed),
            'embed' => $sEmbed
        ]);
    }

    public function getBlockBalances()
    {
        $sEmbed = 'balances';

        return $this->parseHtmlByName('embed_block.html', [
            'js_object' => $this->_oConfig->getJsObject('embeds'),
            'html_id' => $this->_oConfig->getHtmlIds($sEmbed),
            'embed' => $sEmbed
        ]);
    }
    
    public function getBlockNotifications()
    {
        $sEmbed = 'notification-banner';

        return $this->parseHtmlByName('embed_block.html', [
            'js_object' => $this->_oConfig->getJsObject('embeds'),
            'html_id' => $this->_oConfig->getHtmlIds($sEmbed),
            'embed' => $sEmbed
        ]);
    }

    public function displayProfileLink($mixedProfile)
    {
    	if(!is_array($mixedProfile))
            $mixedProfile = BxDolModule::getInstance($this->MODULE)->getProfileInfo((int)$mixedProfile);

    	return $this->parseHtmlByName('link.html', array(
            'href' => $mixedProfile['link'],
            'title' => bx_html_attribute(!empty($mixedProfile['title']) ? $mixedProfile['title'] : $mixedProfile['name']),
            'content' => $mixedProfile['name']
        ));
    }

}

/** @} */
