<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTasksMenuTimer extends BxTemplMenuCustom
{
    protected $_sModule;
    protected $_oModule;

    protected $_iContentId;
    protected $_iProfileId;

    protected $_bShowAsButton;
    protected $_bShowTitle;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_sModule = 'bx_tasks';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_iContentId = 0;
        $this->_iProfileId = 0;

        $this->_bShowAsButton = true;
        $this->_bShowTitle = true;
    }

    public function setParams($iContentId, $iProfileId)
    {
        $this->_iContentId = $iContentId;
        $this->_iProfileId = $iProfileId;
    }

    protected function getMenuItemsRaw ()
    {
        $sPrefix = str_replace('_', '-', $this->_oModule->_oConfig->getName());
        $sJsObject = $this->_oModule->_oConfig->getJsObject('timer');

        $aTimer = $this->_oModule->_oDb->getTimers([
            'sample' => 'content_profile_ids', 
            'content_id' => $this->_iContentId, 
            'profile_id' => $this->_iProfileId
        ]);

        $aItems = [];
        if($aTimer && is_array($aTimer)) {
            if((int)$aTimer['started'] > 0)
                $aItems = [
                    ['name' => 'stop', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.stop(this, ' . $this->_iContentId . ', ' . $this->_iProfileId . ')', 'target' => '_self', 'title' => _t('_bx_tasks_txt_timer_stop')],
                ];
            else
                $aItems = [
                    ['name' => 'resume', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.resume(this, ' . $this->_iContentId . ', ' . $this->_iProfileId . ')', 'target' => '_self', 'title' => _t('_bx_tasks_txt_timer_resume')],
                    ['name' => 'clear', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.clear(this, ' . $this->_iContentId . ', ' . $this->_iProfileId . ')', 'target' => '_self', 'title' => _t('_bx_tasks_txt_timer_clear')],
                    ['name' => 'log', 'class' => '', 'link' => '', 'onclick' => '', 'target' => '', 'title' => ''],
                ];
        }
        else {
            $aItems = [
                ['name' => 'start', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.start(this, ' . $this->_iContentId . ', ' . $this->_iProfileId . ')', 'target' => '_self', 'title' => _t('_bx_tasks_txt_timer_start')],
                ['name' => 'time', 'class' => '', 'link' => '', 'onclick' => '', 'target' => '', 'title' => ''],
            ];
        }

        return $aItems;
    }

    protected function _getMenuItemTime($aItem, $aParams = [])
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iId = $this->_iContentId;

        $sObject = $CNF['OBJECT_REPORTS_TIME'];
        $oObject = !empty($sObject) ? BxDolReport::getObjectInstance($sObject, $iId) : false;
        if(!$oObject || !$oObject->isEnabled())
            return '';

        $aObjectOptions = [
            'dynamic_mode' => $this->_bDynamicMode,
            'show_do_report_as_button' => $this->_bShowAsButton,
            'show_do_report_label' => $this->_bShowTitle,
            'show_do_report_icon' => false,
            'show_counter' => false
        ];
        if($aParams && is_array($aParams))
            $aObjectOptions = array_merge($aObjectOptions, $aParams);

        if($this->_bIsApi)
            return [
                'id' => $aItem['id'],
                'name' => $aItem['name'],
                'display_type' => 'element',
                'data' => $oObject->getElementApi($aObjectOptions)
            ];

    	return $oObject->getElementBlock($aObjectOptions);
    }

    protected function _getMenuItemLog($aItem)
    {
        return $this->_getMenuItemTime($aItem, [
            'do_report_label' => '_bx_tasks_txt_timer_log'
        ]);
    }

    protected function _getMenuItemDefault($aItem)
    {
        if($this->_bIsApi)
            return $aItem;

        $aItem['class_wrp'] = 'bx-tasks-timer-action' . (!empty($aItem['class_wrp']) ? ' ' . $aItem['class_wrp'] : '');

        if($this->_bShowAsButton)
            $aItem['class_link'] = 'bx-btn' . (isset($aItem['primary']) && (int)$aItem['primary'] == 1 ? ' bx-btn-primary' : '') . (!empty($aItem['class_link']) ? ' ' . $aItem['class_link'] : '');

        if(!$this->_bShowTitle)
            $aItem['bx_if:title']['condition'] = false;

        return parent::_getMenuItemDefault ($aItem);
    }
}

/** @} */
