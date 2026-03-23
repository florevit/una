<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTasksGridCommon extends BxBaseModTextGridCommon
{
    /**
     * Context module
     */
    protected $_sFilter3Name;
    protected $_sFilter3Value;
    protected $_aFilter3Values;

    /**
     * Context entry
     */
    protected $_sFilter4Name;
    protected $_sFilter4Value;
    protected $_aFilter4Values;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_tasks';
        parent::__construct ($aOptions, $oTemplate);

        $this->_sTableAlias = 'tt';

        $this->_initFilter(3, $this->_oModule->getContexts());
        if(($sValue = bx_get('cxt_m')) !== false)
            $this->_setFilterValue(3, $sValue);

        if(($sValue = bx_get('cxt_id')) !== false)
            $this->_setFilterValue(4, $sValue);
    }

    protected function _getCellContextModule($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->getModuleTitle($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getFilterControls()
    {
        $sContent = $this->_getFilterSelectOne($this->_sFilter3Name, $this->_sFilter3Value, $this->_aFilter3Values);
        return $sContent . parent::_getFilterControls();
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_parseFilterValue($sFilter);

        if(!empty($this->_sFilter3Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`type`=?", $this->_sFilter3Value);

        if(!empty($this->_sFilter4Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`id`=?", $this->_sFilter4Value);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
