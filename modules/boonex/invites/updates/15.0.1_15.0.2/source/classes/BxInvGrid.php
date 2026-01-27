<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Invites Invites
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxInvGrid extends BxBaseModGeneralGrid
{
    protected $_sFilter1Name;
    protected $_sFilter1Value;
    protected $_aFilter1Values;
    protected $_sParamsDivider;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_invites';

        parent::__construct ($aOptions, $oTemplate);
    }

    public function getFormCallBackUrlAPI($sAction, $iId = 0)
    {
         return '/api.php?r=system/perfom_action_api/TemplServiceGrid/&params[]=&o=' . $this->_sObject . '&a=' . $sAction;
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $oForm = $this->_oModule->getFormObjectInvite();
        if(!$oForm)
            return $this->_getActionResult([]);

        $oForm->aInputs['text']['value'] = _t('_bx_invites_msg_invitation');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?' . bx_encode_url_params($_GET, array('ids', '_r'));
        $oForm->initChecker();

        $aResult = [];
        if($oForm->isSubmittedAndValid()) {
            $sResult = $this->_oModule->processFormObjectInvite($oForm);
            if($this->_bIsApi)
                $aResult = [bx_api_get_msg($sResult)];
            else
                $aResult = ['msg' => $sResult];
        }
        else {
            if($this->_bIsApi)
                $aResult = $this->getFormBlockAPI($oForm, $sAction);
            else
                $aResult = ['popup' => [
                    'html' => BxTemplFunctions::getInstance()->popupBox('_bx_invites_form_invite', _t('_bx_invites_form_invite'), $this->_oModule->_oTemplate->parseHtmlByName('popup_invite.html', [
                        'form_id' => $oForm->id,
                        'form' => $oForm->getCode(true),
                        'object' => $this->_sObject,
                        'action' => $sAction
                    ])), 
                    'options' => ['closeOnOuterClick' => true]
                ]];
        }

        return $this->_getActionResult($aResult);
    }

    protected function _getFilterControls ()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getSearchInput();
    }
    
    protected function _getFilterOnChange()
    {
        return $this->_oModule->_oConfig->getJsObject('main') . '.onChangeFilter(this)';
    }

    protected function _getId()
    {
        $iId = 0;

        if(($aIds = bx_get('ids')) !== false) {
            if(!$aIds || !is_array($aIds))
                return 0;

            $iId = (int)array_shift($aIds);
        }
        else if(($iId = bx_get('id')) !== false)
            $iId = (int)$iId;
        
        return $iId;
    }

    protected function _getIds()
    {
        $aIds = [];

        if(($aIds = bx_get('ids')) !== false) {
            if(!$aIds || !is_array($aIds))
                return [];

            $aIds = bx_process_input($aIds, BX_DATA_INT);
        }
        else if(($iId = bx_get('id')) !== false)
            $aIds = [(int)$iId];

        return $aIds;
    }
}

/** @} */
