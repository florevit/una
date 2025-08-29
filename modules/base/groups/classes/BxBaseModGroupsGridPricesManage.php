<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 * 
 * @{
 */

require_once('BxBaseModGroupsGridPrices.php');

class BxBaseModGroupsGridPricesManage extends BxBaseModGroupsGridPrices
{
    protected $_iRoleId;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_iRoleId = false;
        if(($iRoleId = bx_get('role_id')) !== false)
            $this->setRoleId($iRoleId);
    }

    public function getFormBlockTitleAPI($sAction, $iId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sResult = '';

        switch($sAction) {
            case 'add':
                $sResult = _t($CNF['T']['popup_title_price_add']);
                break;

            case 'edit':
                $sResult = _t($CNF['T']['popup_title_price_edit']);
                break;
        }

        return $sResult;
    }

    public function getFormCallBackUrlAPI($sAction, $iId = 0)
    {
         return '/api.php?r=system/perfom_action_api/TemplServiceGrid/&params[]=&o=' . $this->_sObject . '&a=' . $sAction . '&profile_id=' . $this->_iGroupProfileId . '&role_id=' . $this->_iRoleId . '&id=' . $iId;
    }

    public function setRoleId($mixedRoleId)
    {
        if(is_string($mixedRoleId)) {
            if($mixedRoleId === '')
                return;

            if(!is_numeric($mixedRoleId))
                $mixedRoleId = $this->_roleIdS2I($mixedRoleId);
        }

        $this->_iRoleId = (int)$mixedRoleId;
        $this->_aQueryAppend['role_id'] = $this->_iRoleId;
    }

    public function getCode($isDisplayHeader = true)
    {
        $sResult = parent::getCode($isDisplayHeader);
        if(empty($sResult))
            return $sResult;

        $sJsCode = '';
        if($isDisplayHeader) {
            $this->_oModule->_oTemplate->addJs([
                'modules/base/groups/js/|prices.js',
                'prices.js'
            ]);

            $sJsCode = $this->_oModule->_oTemplate->getJsCode('prices', array(
                'sObjNameGrid' => $this->_sObject, 
                'aHtmlIds' => $this->_oModule->_oConfig->getHtmlIds()
            ));
        }

        return $sJsCode . $sResult;
    }

    public function performActionAdd()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'add';

        if(($mixedResult = $this->_oModule->checkAllowedEdit($this->_aGroupContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return $this->_bIsApi ? [bx_api_get_msg($mixedResult)] : echoJson(['msg' => $mixedResult]);

        $sFilter = bx_get('filter');
        if(strpos($sFilter, $this->_sParamsDivider) !== false) {
            list($sRoleId, $sFilter) = explode($this->_sParamsDivider, $sFilter);
            if($sRoleId)
                $this->setRoleId($sRoleId);
        }

        if($this->_iRoleId === false && ($sRoleId = bx_get('role_id')) !== false)
            $this->setRoleId($sRoleId);

        $sForm = $CNF['OBJECT_FORM_PRICE_DISPLAY_ADD'];
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_PRICE'], $CNF['OBJECT_FORM_PRICE_DISPLAY_ADD']);
        $oForm->setId($sForm);
        $oForm->setName($sForm);
        $oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => $this->_sObject, 'a' => $sAction, 'profile_id' => $this->_iGroupProfileId]));
        $oForm->setRoleId($this->_iRoleId);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sRoleId = $oForm->getCleanValue('role_id');
            BxDolForm::setSubmittedValue('role_id', $this->_roleIdS2I($sRoleId), $oForm->aFormAttrs['method']);

            $iPeriod = $oForm->getCleanValue('period');
            $sPeriodUnit = $oForm->getCleanValue('period_unit');

            if(!empty($iPeriod) && empty($sPeriodUnit)) 
                return ($sMsg = _t($CNF['T']['err_period_unit'])) && $this->_bIsApi ? [bx_api_get_msg($sMsg)] : echoJson(['msg' => $sMsg]);

            $aPrice = $this->_oModule->_oDb->getPrices(['type' => 'by_prpp', 'profile_id' => $this->_iGroupProfileId, 'role_id' => $this->_iRoleId, 'period' => $iPeriod, 'period_unit' => $sPeriodUnit]);
            if(!empty($aPrice) && is_array($aPrice))
                return ($sMsg = _t($CNF['T']['err_price_duplicate'])) && $this->_bIsApi ? [bx_api_get_msg($sMsg)] : echoJson(['msg' => $sMsg]);

            if($oForm->getCleanValue('default') != 0)
                $this->_oModule->_oDb->updatePrices(['default' => 0], ['profile_id' => $this->_iGroupProfileId]);

            $iId = (int)$oForm->insert(['profile_id' => $this->_iGroupProfileId, 'added' => time(), 'order' => $this->_oModule->_oDb->getPriceOrderMax($this->_iRoleId) + 1]);
            if($iId != 0)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t($CNF['T']['err_cannot_perform'])];

            return $this->_bIsApi ? [] : echoJson($aRes);
        }

        if($this->_bIsApi)
            return $this->getFormBlockAPI($oForm, $sAction);

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_price'), _t($CNF['T']['popup_title_price_add']), $this->_oModule->_oTemplate->parseHtmlByName('popup_price.html', [
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionEdit()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'edit';

        if(($mixedResult = $this->_oModule->checkAllowedEdit($this->_aGroupContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return $this->_bIsApi ? [bx_api_get_msg($mixedResult)] : echoJson(['msg' => $mixedResult]);

        $aIds = $this->_getIds();
        if($aIds === false)
            return $this->_bIsApi ? [] : echoJson([]);

        $aItem = $this->_oModule->_oDb->getPrices(['type' => 'by_id', 'value' => array_shift($aIds)]);
        if(!is_array($aItem) || empty($aItem))
            return $this->_bIsApi ? [] : echoJson([]);

        $aItem['role_id'] = $this->_roleIdI2S($aItem['role_id']);

        $sForm = $CNF['OBJECT_FORM_PRICE_DISPLAY_EDIT'];
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_PRICE'], $CNF['OBJECT_FORM_PRICE_DISPLAY_EDIT']);
        $oForm->setId($sForm);
        $oForm->setName($sForm);
        $oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', ['o' => $this->_sObject, 'a' => $sAction, 'profile_id' => $this->_iGroupProfileId]));

        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->getCleanValue('default') != 0)
                $this->_oModule->_oDb->updatePrices(['default' => 0], ['profile_id' => $this->_iGroupProfileId]);

            if($oForm->update($aItem['id']) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $aItem['id']];
            else
                $aRes = ['msg' => _t($CNF['T']['err_cannot_perform'])];

            return $this->_bIsApi ? [] : echoJson($aRes);
        }

        if($this->_bIsApi)
            return $this->getFormBlockAPI($oForm, $sAction, $iItem);

        bx_import('BxTemplFunctions');
        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('popup_price'), _t($CNF['T']['popup_title_price_edit']), $this->_oModule->_oTemplate->parseHtmlByName('popup_price.html', [
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionDelete()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds))
            return echoJson([]);

        $iAffected = 0;
        $aIdsAffected = [];
        foreach($aIds as $iId)
            if($this->_oModule->_oDb->deletePrices(['id' => $iId])) {
                $aIdsAffected[] = $iId;
                $iAffected++;
            }

        return echoJson($iAffected ? ['grid' => $this->getCode(false), 'blink' => $aIdsAffected] : ['msg' => _t($CNF['T']['err_cannot_perform'])]);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oModule->_oTemplate->addJs([
            'jquery.form.min.js', 
            'modules/base/groups/js/|prices.js', 
            'prices.js'
        ]);

        $this->_oModule->_oTemplate->addCss([
            'prices.css'
        ]);
    }

    protected function _getCellDefaultPrice($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault((int)$mixedValue != 0 ? _t('_Yes') : '', $sKey, $aField, $aRow);
    }

    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        $sContent = '';
        $oForm = new BxTemplFormView([]);

        $aInputRoles = [
            'type' => 'select',
            'name' => 'role',
            'attrs' => [
                'id' => 'bx-grid-level-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->_oModule->_oConfig->getJsObject('prices') . '.onChangeRole()'
            ],
            'value' => $this->_iRoleId,
            'values' => ['' => _t('_Select_all')]
        ];
        foreach($this->_aRoles as $iId => $sTitle)
            $aInputRoles['values'][$this->_roleIdI2S($iId)] = $sTitle;
        $sContent .=  $oForm->genRow($aInputRoles);

        $aInputSearch = [
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => [
                'id' => 'bx-grid-search-' . $this->_sObject,
            ]
        ];
        $sContent .= $oForm->genRow($aInputSearch);

        return $sContent;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString("AND `profile_id`=?", $this->_iGroupProfileId);

        if(strpos($sFilter, $this->_sParamsDivider) !== false) {
            list($sRoleId, $sFilter) = explode($this->_sParamsDivider, $sFilter);
            if(!empty($sRoleId))
                $this->setRoleId($this->_roleIdS2I($sRoleId));
        }

        if($this->_iRoleId !== false)
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `role_id`=? ", $this->_iRoleId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _isVisibleGrid ($a)
    {
        return $this->_oModule->checkAllowedManageAdmins($this->_aGroupContentInfo) == CHECK_ACTION_RESULT_ALLOWED;
    }
}

/** @} */
