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

    protected $_oPermalink;
    protected $_sPageLink;
    protected $_aPageParams;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_tasks';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $oTemplate);

        $this->_isMultilevel = true;

        $this->_oPermalink = BxDolPermalinks::getInstance();
        list($this->_sPageLink, $this->_aPageParams) = bx_get_base_url_inline();
    }

    protected function getMenuItemsRaw ()
    {
        $iProfile = bx_get_logged_profile_id();
        $aContexts = $this->_oModule->getContexts();

        $aMenuItems = [];
        foreach($aContexts as $sName => $sTitle) {
            $aSubmenu = $this->_getMenuSubitems($iProfile, $sName);
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

            $aSubmenu[] = [
                'id' => 'context-' . $iId, 
                'name' => 'context-' . $iId, 
                'class' => '', 
                'link' => $this->_oPermalink->permalink(bx_append_url_params($this->_sPageLink, array_merge($this->_aPageParams, [
                    'cxt_m' => $sContextModule,
                    'cxt_id' => $iId
                ]))), 
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
