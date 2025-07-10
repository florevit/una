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

class BxPaymentDetailsFormCheckerHelper extends BxDolFormCheckerHelper
{
    function checkHttps ($s)
    {
        return empty($s) || substr(BX_DOL_URL_ROOT, 0, 5) == 'https';
    }
}

class BxPaymentFormDetails extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    protected $_sLangsPrefix;
    protected $_bCollapseFirst;
    
    protected $_iProfileId;
    protected $_sPaymentProvider;

    function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_sModule = 'bx_payment';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_sLangsPrefix = $this->_oModule->_oConfig->getPrefix('langs');
        $this->_bCollapseFirst = true;
        
        $this->_iProfileId = $this->_oModule->getProfileId();
        $this->_sPaymentProvider = '';
    }

    public function setProfileId($iProfileId = BX_PAYMENT_EMPTY_ID)
    {
        if($iProfileId != BX_PAYMENT_EMPTY_ID)
            $this->_iProfileId = $iProfileId;
    }

    public function setPaymentProvider($sPaymentProvider)
    {
        $this->_sPaymentProvider = $sPaymentProvider;

        $this->aParams['db']['submit_name'] = $this->_getSubmitName();

        $sName = $this->_getName();
        $this->setId($sName);
        $this->setName($sName);
    }

    public function init()
    {
        $this->aInputs = [];
                
        $aInputs = $this->_oModule->_oDb->getForm($this->_sPaymentProvider);
        if(empty($aInputs))
            return false;

        $bSiteAdmin = $this->_oModule->_oConfig->isSiteAdmin();
        $bSingleSeller = $this->_oModule->_oConfig->isSingleSeller();

        $bCollapsed = $this->_bCollapseFirst;
        $iProvider = 0;
        $sProvider = "";
        $oProvider = null;
        foreach($aInputs as $aInput) {
            if((int)$aInput['provider_for_owner_only'] != 0 && !$bSiteAdmin)
                continue;

            if((int)$aInput['provider_single_seller'] == 0 && $bSingleSeller)
                continue;

            if($iProvider != $aInput['provider_id']) {
                $sBlockHeaderName = 'provider_' . $aInput['provider_id'] . '_begin';
                $this->aInputs[$sBlockHeaderName] = array(
                    'type' => 'block_header',
                    'name' => $sBlockHeaderName,
                    'caption' => _t($aInput['provider_caption']),
                    'info' => _t($aInput['provider_description']),
                    'collapsable' => true,
                    'collapsed' => ($sSelected = bx_get('pp')) !== false && $sSelected == $aInput['provider_name'] ? false : $bCollapsed
                );

                $iProvider = $aInput['provider_id'];
                $sProvider = $aInput['provider_name'];
                $oProvider = $this->_oModule->getObjectProvider($sProvider, $this->_iProfileId);
                $bCollapsed = true;
            }

            $this->aInputs[$aInput['name']] = array(
                'type' => $aInput['type'],
                'name' => $aInput['name'],
                'caption' => _t($aInput['caption']),
                'value' => $oProvider->getOption($aInput['name']),
                'info' => _t($aInput['description']),
            	'attrs' => array(
                    'bx-data-provider' => $iProvider
            	),
                'checker' => array (
                    'func' => $aInput['check_type'],
                    'params' => $aInput['check_params'],
                    'error' => _t($aInput['check_error']),
                )
            );

            //--- Make some field dependent actions ---//
            switch($aInput['type']) {
                case 'select':
                    if(empty($aInput['extra']))
                       break;

                    $aAddon = ['values' => []];

                    if(BxDolService::isSerializedService($aInput['extra']))
                        $aAddon['values'] = BxDolService::callSerialized($aInput['extra']);
                    else {
                        $aPairs = explode(',', $aInput['extra']);
                        foreach($aPairs as $sPair) {
                            $aPair = explode('|', $sPair);
                            $aAddon['values'][] = ['key' => $aPair[0], 'value' => _t($aPair[1])];
                        }
                    }
                    break;

                case 'checkbox':
                    $this->aInputs[$aInput['name']]['value'] = 'on';
                    $aAddon = array('checked' => $oProvider->getOption($aInput['name']) == 'on');
                    break;

                case 'value':
                       $sMethod = 'get' . bx_gen_method_name(str_replace($aInput['provider_option_prefix'], '', $aInput['name']));
                       if(method_exists($oProvider, $sMethod))
                            $this->aInputs[$aInput['name']]['value'] = $oProvider->$sMethod($this->_iProfileId);
                       break;

                case 'custom':
                    $sMethod = 'get' . bx_gen_method_name(str_replace($aInput['provider_option_prefix'], '', $aInput['name']));
                    if(method_exists($oProvider, $sMethod)) {
                        $mixedContent = $oProvider->$sMethod($this->_iProfileId);
                        if(is_array($mixedContent))
                            $this->aInputs[$aInput['name']] = array_merge($this->aInputs[$aInput['name']], $mixedContent);
                        else
                            $this->aInputs[$aInput['name']]['content'] = $mixedContent;
                    }
                    break;
            }

            if(!empty($aAddon) && is_array($aAddon))
                $this->aInputs[$aInput['name']] = array_merge($this->aInputs[$aInput['name']], $aAddon);
        }

        $sSubmitName = $this->_getSubmitName();
        $this->aInputs[$sSubmitName] = [
            'type' => 'submit',
            'name' => $sSubmitName,
            'value' => _t($this->_sLangsPrefix . 'form_details_input_do_submit'),
        ];

        $this->aInputs['provider_' . $iProvider . '_end'] = [
            'type' => 'block_end'
        ];

        return true;
    }

    public function initWithParams($iProfileId, $sPaymentProvider)
    {
        $this->setProfileId($iProfileId);
        $this->setPaymentProvider($sPaymentProvider);
        $this->init();
    }

    protected function _getName()
    {
        return $this->_sModule . '_form_details' . ($this->_sPaymentProvider ? '_' . $this->_sPaymentProvider : '');
    }

    protected function _getSubmitName()
    {
        return 'submit' . ($this->_sPaymentProvider ? '_' . $this->_sPaymentProvider : '');
    }
}

/** @} */
