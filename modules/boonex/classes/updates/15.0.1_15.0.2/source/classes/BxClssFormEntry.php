<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxClssFormEntry extends BxBaseModTextFormEntry
{
    protected $_sGhostTemplateCover = 'form_ghost_template_cover.html';
    protected $_oProfileContext = null;
	
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_classes';

        parent::__construct($aInfo, $oTemplate);
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bValues = $aValues && !empty($aValues['id']);
        $aContentInfo = $bValues ? $this->_oModule->_oDb->getContentInfoById($aValues['id']) : false;

        parent::initChecker ($aValues, $aSpecificValues);

        // try to get context from form POST
        $iProfileId = bx_get($CNF['FIELD_ALLOW_VIEW_TO']);

        // try to get context from the form saved value (in case of "edit" form)
        if ($iProfileId >= 0 && isset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]) && $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]['value'] < 0)
            $iProfileId = $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]['value'];

        // try to get context from GET param (in case of "create" form)
        if ($iProfileId >= 0)
            $iProfileId = bx_get('profile_id');

        // try to get context from class id
        if (($iClassId = (int)bx_get('id')) && $aClass = $this->_oModule->_oDb->getContentInfoById($iClassId)) {
            $iProfileId = $aClass[$CNF['FIELD_ALLOW_VIEW_TO']];
        }

        $iProfileId = abs($iProfileId);
        if (isset($this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']])) {
            $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]['value'] = -$iProfileId;
            $this->aInputs[$CNF['FIELD_ALLOW_VIEW_TO']]['type'] = 'hidden';
        }

        if ($iProfileId)
            $this->_oProfileContext = BxDolProfile::getInstance($iProfileId);

        if (isset($this->aInputs[$CNF['FIELD_MODULE']]) && $this->_oProfileContext) {
            $this->aInputs[$CNF['FIELD_MODULE']]['values'] = $this->_oModule->_oDb->getEntriesModulesByContext($this->_oProfileContext->id(), true);
        }
    }

    public function insert ($aValsToAdd = [], $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aValsToAdd['order'] = $this->_oModule->_oDb->getClassMaxOrder(abs($this->getCleanValue($CNF['FIELD_ALLOW_VIEW_TO'])), $this->getCleanValue($CNF['FIELD_MODULE']));

        return parent::insert ($aValsToAdd, $isIgnore);
    }

    public function getCode($bDynamicMode = false)
    {
        if (!$this->_bViewMode) {
            if (!$this->_oProfileContext)
                return MsgBox(_t('_bx_classes_txt_err_cant_add_class_without_context'));

            if (isset($this->aInputs['module_id']) && !$this->aInputs['module_id']['values'])
                return MsgBox(_t('_bx_classes_txt_err_cant_add_class_without_modules'));

            if (!isAdmin() && !$this->_oModule->serviceIsCourseAdmin($this->_oProfileContext->id()))
                return MsgBox(_t('_sys_txt_access_denied'));
        }

        return parent::getCode($bDynamicMode);
    }

    protected function genCustomViewRowValueCompletedWhen($aInput)
    {
        unset($aInput['values_list_name']);
        return $aInput['value'] ? '<div class="bx-classes-field-view-completed-when">' . $this->genViewRowValueForSelect($aInput) . '</div>' : '';
    }

    protected function genCustomViewRowValueModuleId($aInput)
    {
        return $aInput['value'] ? '<div class="bx-classes-field-view-module">' . bx_process_output($this->_oModule->_oDb->getClassModuleTitleById ($aInput['value'])) . '</div>' : '';
    }

    protected function genCustomViewRowValueStartDate($aInput)
    {
        return $this->_genCustomViewRowValue($aInput, 'col-green1-dark');
    }

    protected function genCustomViewRowValueEndDate($aInput)
    {
        return $this->_genCustomViewRowValue($aInput, 'col-red1');
    }

    protected function _genCustomViewRowValue($aInput, $sClassAdd = '')
    {
        $sClass = "bx-classes-field-view-" . str_replace('_', '-', $aInput['name']) . ' ' . $sClassAdd;
        $sClass .= ' ' . $sClassAdd;

        $aInput['name'] .= '!';
        $s = parent::genViewRowValue($aInput);
        return $aInput['value'] ? '<div class="' . $sClass . '">' . $s . '</div>' : $s;
    }
}

/** @} */
