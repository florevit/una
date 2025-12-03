<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxTasksGridTime.php');

class BxTasksGridTimeAdministration extends BxTasksGridTime
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        if($this->_isAdministration())
            $this->_initFilter(1, $this->_oModule->getAssignees());

        $this->_initFilter(2, $this->_oModule->getContexts());
    }

    protected function _getCellContextModule($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_oModule->getModuleTitle($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellContextId($mixedValue, $sKey, $aField, $aRow)
    {
        if(($oContext = BxDolProfile::getInstance($mixedValue)) !== false)
            $mixedValue = $oContext->getDisplayName();

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getFilterControls()
    {
        $this->_getFcDefault();

        $sContent = $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values, '_bx_tasks_grid_filter_item_title_tm_select_one_author_id');
        $sContent .= $this->_getFilterSelectOne($this->_sFilter2Name, $this->_sFilter2Value, $this->_aFilter2Values, '_bx_tasks_grid_filter_item_title_tm_select_one_context_module');
        $sContent .= $this->_getFcDateSearch();
        return $sContent;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_parseFilterValue($sFilter);

    	if(!empty($this->_sFilter1Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(' AND ' . $this->_getSqlField('author_id') . '=?', $this->_sFilter1Value);

        if(!empty($this->_sFilter2Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(' AND `tp`.`type`=?', $this->_sFilter2Value);
        
        if(!empty($this->_sFilter3Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(' AND ' . $this->_getSqlField('value_date') . '>=?', strtotime($this->_sFilter3Value));

        if(!empty($this->_sFilter4Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(' AND ' . $this->_getSqlField('value_date') . '<=?', strtotime($this->_sFilter4Value));

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
