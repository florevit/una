<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxNtfsGridEtemplates extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sModule = 'bx_notifications';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function getCode($isDisplayHeader = true)
    {
        $sResult = parent::getCode($isDisplayHeader);
        if(!$sResult)
            return $sResult;

        return $sResult . $this->_oModule->_oTemplate->getJsCode('main');
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $oForm = $this->_getFormObject($sAction);
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            if(($iId = $oForm->insert()) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_bx_ntfs_txt_err_cannot_perform')];

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('view', 'add_etemplate_popup'), _t('_bx_ntfs_grid_popup_title_ett_add'), $this->_oModule->_oTemplate->parseHtmlByName('etemplate_form.html', [
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            ]));

            echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false, 'removeOnClose' => true]]]);
        }
    }

    public function performActionEdit()
    {
        $sAction = 'edit';

        $iId = $this->_getId();
        $aEtemplate = $this->_oModule->_oDb->getEtemplates(['sample' => 'id', 'id' => $iId]);
        if(empty($aEtemplate) || !is_array($aEtemplate))
            return echoJson([]);

        $oForm = $this->_getFormObject($sAction, $aEtemplate);
        $oForm->initChecker($aEtemplate);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_bx_ntfs_txt_err_cannot_perform')];

            echoJson($aRes);
        }
        else {
            $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('edit_profile_popup'), _t('_bx_ntfs_grid_popup_title_ett_edit'), $this->_oModule->_oTemplate->parseHtmlByName('etemplate_form.html', [
                'form_id' => $oForm->aFormAttrs['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            ]));

            echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false, 'removeOnClose' => true]]]);
        }
    }

    protected function _getCellType($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oTemplate->getTypeTitle($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellAction($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->_oTemplate->getActionTitle($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionsDisabledBehavior($aRow)
    {
        return false;
    }

    protected function _getFormObject($sAction, $aItem = [])
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ETEMPLATE'], $CNF['OBJECT_FORM_ETEMPLATE_DISPLAY_' . strtoupper($sAction)]);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction;
        if(!empty($aItem['id']))
            $oForm->aFormAttrs['action'] .= '&id=' . $aItem['id'];

        if(($sK = 'type') && isset($oForm->aInputs[$sK])) {
            $oForm->aInputs[$sK] = array_merge($oForm->aInputs[$sK], [
                'attrs' => [
                    'onchange' => $this->_oModule->_oConfig->getJsObject('main') . '.onChangeType(this)',
                ],
                'values' => [
                    ['key' => '', 'value' => _t('_sys_please_select')]
                ]
            ]);

            $aTypes = $this->_oModule->_oDb->getHandlerTypes();
            foreach($aTypes as $sType)
                $oForm->aInputs[$sK]['values'][] = ['key' => $sType, 'value' => $this->_oModule->_oTemplate->getTypeTitle($sType)];
        }

        if(($sK = 'action') && isset($oForm->aInputs[$sK])) {
            $oForm->aInputs[$sK] = array_merge($oForm->aInputs[$sK], [
                'attrs' => [
                    'id' => $this->_oModule->_oConfig->getHtmlIds('main', 'field_action'),
                    'disabled' => 'disabled'
                ],
                'values' => [
                    ['key' => '', 'value' => _t('_sys_please_select')]
                ]
            ]);
        }

        if(($sK = 'body') && isset($oForm->aInputs[$sK]))
            $oForm->aInputs[$sK]['code'] = 1;

        return $oForm;
    }

    protected function _getId()
    {
        if(($aIds = bx_get('ids')) && is_array($aIds)) 
            return array_shift($aIds);

        return ($iId = bx_get('id')) !== false ? (int)$iId : false;
    }
}

/** @} */
