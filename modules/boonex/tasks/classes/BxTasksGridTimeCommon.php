<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxTasksGridTimeAdministration.php');

class BxTasksGridTimeCommon extends BxTasksGridTimeAdministration
{
    protected $_iLogged;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_iLogged = bx_get_logged_profile_id();
    }

    protected function _getFilterControls()
    {
        $this->_getFcDefault();

        $sContent = $this->_getFilterSelectOne($this->_sFilter2Name, $this->_sFilter2Value, $this->_aFilter2Values, '_bx_tasks_grid_filter_item_title_tm_select_one_context_module');
        $sContent .= $this->_getFcDateSearch();
        return $sContent;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(' AND ' . $this->_getSqlField('author_id') . '=?', $this->_iLogged);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
