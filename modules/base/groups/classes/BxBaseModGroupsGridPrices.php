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


class BxBaseModGroupsGridPrices extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_sParamsDivider = '#-#';

    protected $_iGroupProfileId;
    protected $_iGroupContentId;
    protected $_aGroupContentInfo;

    protected $_aRoles;
    protected $_aPeriodUnits;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);

        $CNF = $this->_oModule->_oConfig->CNF;

        $this->_aRoles = $this->_oModule->_oConfig->getRolesPurchasable();
        $this->_aPeriodUnits = BxDolForm::getDataItems($CNF['OBJECT_PRE_LIST_PERIOD_UNITS']);

        $this->_iGroupProfileId = 0;
        $this->_iGroupContentId = 0;
        $this->_aGroupContentInfo = array();
        if(($iGroupProfileId = bx_get('profile_id')) !== false)
            $this->setProfileId($iGroupProfileId);
    }

    public function setProfileId($iProfileId)
    {
        $this->_iGroupProfileId = (int)$iProfileId;
        $this->_aQueryAppend['profile_id'] = $this->_iGroupProfileId;

        $oGroupProfile = BxDolProfile::getInstance($this->_iGroupProfileId);
        if($oGroupProfile) {
            $this->_iGroupContentId = (int)$oGroupProfile->getContentId();
            $this->_aGroupContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iGroupContentId);
        }
    }

    protected function _getCellPeriod($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        if(!$this->_bIsApi) {
            if((int)$mixedValue == 0)
                $mixedValue = _t('_lifetime');
            else
                $mixedValue = _t($CNF['T']['txt_n_unit'], $mixedValue, _t($this->_aPeriodUnits[$aRow['period_unit']]));
        }
        else
            $mixedValue = ['period' => $mixedValue, 'unit' => $aRow['period_unit']];

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellPrice($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = $this->_oModule->_oConfig->CNF;
        
        $aCurrency = $this->_oModule->_oConfig->getCurrency($this->_aGroupContentInfo[$CNF['FIELD_AUTHOR']]);

        if(!$this->_bIsApi) {
            if((float)$mixedValue != 0)
                $mixedValue = html_entity_decode($aCurrency['sign']) . $mixedValue;
            else 
                $mixedValue = _t('_free');
        }
        else
            $mixedValue = ['value' => $mixedValue, 'currency' => $aCurrency['code']];

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _roleIdI2S($iValue)
    {
        return $this->_oModule->_oConfig->roleIdI2S($iValue);
    }

    protected function _roleIdS2I($sValue)
    {
        return $this->_oModule->_oConfig->roleIdS2I($sValue);
    }

    protected function _getIds()
    {
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) 
                return false;

            $aIds = array($iId);
        }

        return $aIds;
    }
}

/** @} */
