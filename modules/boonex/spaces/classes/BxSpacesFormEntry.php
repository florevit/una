<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit Space Form.
 */
class BxSpacesFormEntry extends BxBaseModGroupsFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_spaces';
        parent::__construct($aInfo, $oTemplate);
    }
    
    protected function genCustomInputParentSpace($aInput)
    {
        $iCurrent = ($iCurrent = bx_get('id')) !== false ? (int)$iCurrent : - 1;

        if($this->_bIsApi) {
            $aInput = array_merge($aInput, [
                'title' => $aInput['caption'], 
                'ajax_get_suggestions' => $this->_oModule->_oConfig->getName() . "/ajax_get_parent_space&params[]=" . $iCurrent . "&params[]=",
                'value_data' => []
            ]);

            $aInput['value_data'] = [];
            if(!empty($aInput['value'])) {
                if(!is_array($aInput['value']))
                    $aInput['value'] = [$aInput['value']];

                foreach($aInput['value'] as $iProfileId)
                    if(($oProfile = BxDolProfile::getInstance($iProfileId)) !== false)
                        $aInput['value_data'][] = BxDolProfile::getData($oProfile);
            }
        }
        else {
            $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . "modules/?r=" . $this->_oModule->_oConfig->getUri() . "/ajax_get_parent_space&id=" . $iCurrent;
            if (isset($aInput['value']) && !is_array($aInput['value']))
                $aInput['value'] = array($aInput['value']);
        }

        if(($sK = 'custom') && (empty($aInput[$sK]) || !is_array($aInput[$sK])))
            $aInput[$sK] = [];
        $aInput['custom']['only_once'] = 1;

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
    
    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $this->defineLevelById($aValsToAdd);
        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $this->defineLevelById($aValsToAdd);
        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }
    
    function defineLevelById(&$aValsToAdd)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($CNF['FIELD_PARENT']) && isset($this->aInputs[$CNF['FIELD_PARENT']])){
            $iParentId = $this->aInputs[$CNF['FIELD_PARENT']]['value'];
            if(is_array($iParentId))
                $iParentId = array_shift($iParentId);

            $aValsToAdd[$CNF['FIELD_PARENT']] = (int)$iParentId;

            if(isset($CNF['FIELD_LEVEL']) && !empty($iParentId) && ($oParent = BxDolProfile::getInstance($iParentId)) !== false)
                $aValsToAdd[$CNF['FIELD_LEVEL']] = $this->_oModule->_oDb->getLevelById($oParent->getContentId()) + 1;
        }
    }
}

/** @} */
