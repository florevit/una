<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Browse entries pages.
 */
class BxBaseModGeneralPageBrowse extends BxTemplPage
{
    protected $MODULE;

    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        
        $CNF = &$this->_oModule->_oConfig->CNF;

        // select module submenu
        if (isset($this->_oModule->_oConfig->CNF['OBJECT_MENU_SUBMENU'])){
            $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
            if($oMenuSubmenu)
                $oMenuSubmenu->setObjectSubmenu($this->_oModule->_oConfig->CNF['OBJECT_MENU_SUBMENU']);
        }

        if(($mixedCategory = bx_get('category')) !== false) {
            $aMarkers = [];

            if(!empty($CNF['OBJECT_CATEGORY']) && is_numeric($mixedCategory)) {
                $mixedCategory = bx_process_input($mixedCategory, BX_DATA_INT);

                $aMarkers = [
                    'category_id' => $mixedCategory,
                    'category_name' => BxDolCategory::getObjectInstance($CNF['OBJECT_CATEGORY'])->getCategoryTitle($mixedCategory),
                ];
            }
            else
                $aMarkers['category_name'] = _t($mixedCategory);

            $this->addMarkers($aMarkers);
        }
    }

}

/** @} */
