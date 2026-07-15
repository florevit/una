<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTasksMenuTimers extends BxTemplMenuCustom
{
    protected $_sModule;
    protected $_oModule;

    protected $_iProfileId;

    protected $_bShowAsButton;
    protected $_bShowTitle;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_sModule = 'bx_tasks';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_iProfileId = 0;

        $this->_bShowAsButton = true;
        $this->_bShowTitle = true;
    }

    public function setParams($iProfileId)
    {
        $this->_iProfileId = $iProfileId;
    }

    protected function getMenuItemsRaw()
    {
        $sJsObject = $this->_oModule->_oConfig->getJsObject('timer');

        return [
            ['name' => 'clear-all', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.clearAll(this, ' . $this->_iProfileId . ')', 'target' => '_self', 'title' => _t('_bx_tasks_txt_timers_clear')],
            ['name' => 'log-all', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.logAll(this, ' . $this->_iProfileId . ')', 'target' => '_self', 'title' => _t('_bx_tasks_txt_timers_log')],
        ];
    }

    protected function _getMenuItemClearAll($aItem)
    {
        if($this->_bIsApi) {
            return array_merge($aItem, [
                'display_type' => 'callback',
                'data' => [
                    'request_url' => $this->_sModule . '/process_timer/&params[]=clear_all&params[]=0&params[]=' . $this->_iProfileId, 
                    'on_callback' => 'hide'
                ]
            ]);
        }

        return true;
    }

    protected function _getMenuItemLogAll($aItem)
    {
        if($this->_bIsApi) {
            return array_merge($aItem, [
                'display_type' => 'callback',
                'data' => [
                    'request_url' => $this->_sModule . '/process_timer/&params[]=log_all&params[]=0&params[]=' . $this->_iProfileId, 
                    'on_callback' => 'hide'
                ]
            ]);
        }

        return true;
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
