<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTasksMenuBrowse extends BxTemplMenu
{
    protected $_sModule;
    protected $_oModule;

    protected $_iProfileId;

    protected $_oPermalink;
    protected $_sPageLink;
    protected $_aPageParams;

    protected $_sParamName;
    protected $_iParamValue;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_tasks';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $oTemplate);

        $this->_isMultilevel = true;

        $this->_iProfileId = 0;

        $this->_oPermalink = BxDolPermalinks::getInstance();
        list($this->_sPageLink, $this->_aPageParams) = bx_get_base_url_inline();

        $this->_sParamName = 'context_pid';
        if(($iParamValue = bx_get($this->_sParamName)) !== false)
            $this->_iParamValue = (int)$iParamValue;
    }
    
    public function setProfileId($iProfileId)
    {
        $this->_iProfileId = $iProfileId;
    }

    protected function getMenuItemsRaw ()
    {
        $aContexts = $this->_oModule->getContexts();

        $aMenuItems = [];
        foreach($aContexts as $sName => $sTitle) {
            $aSubmenu = $this->_getMenuSubitems($this->_iProfileId, $sName);
            if(empty($aSubmenu))
                continue;

            $aMenuItems[] = [
                'name' => $sName, 
                'class' => '', 
                'link' => 'javascript:void(0)', 
                'onclick' => "javascript:bx_menu_toggle(this, '" . $this->_sObject . "', '" . $sName . "')",
                'target' => '_self', 
                'icon' => bx_is_srv($sName, 'module_icon') ? bx_srv($sName, 'module_icon') : '',
                'title' => $sTitle,
                'subitems' => $aSubmenu
            ];
        }

        return $aMenuItems;
    }

    protected function _getMenuSubitems($iProfile, $sContextModule)
    {
        $aIds = $this->_oModule->_oDb->getContextsIdsByType($sContextModule, $iProfile);

        $aSubmenu = [];
        foreach($aIds as $iId) {
            $oContext = BxDolProfile::getInstance($iId);
            if(!$oContext)
                continue;

            $sUrl = $this->_oPermalink->permalink(bx_append_url_params($this->_sPageLink, array_merge($this->_aPageParams, [
                $this->_sParamName => $iId
            ])));

            $aSubmenu[] = [
                'id' => 'context-' . $iId, 
                'name' => 'context-' . $iId, 
                'class_add' => $iId == $this->_iParamValue ? 'bx-menu-tab-active' : '',
                'class' => '',
                'link' => $this->_bIsApi ? bx_api_get_relative_url($sUrl) : $sUrl, 
                'onclick' => '', 
                'target' => '_self', 
                'title' => $oContext->getDisplayName(), 
                'icon' => $oContext->getIcon(),
                'active' => 1
            ];
        }

        return $aSubmenu;
    }
}

/** @} */
