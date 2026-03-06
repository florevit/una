<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgentsVectorStoreData extends BxDolStudioAgentsVectorStoreData
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        $this->addMarkers($this->_aBrowseParams);
    }

    protected function _delete ($mixedId)
    {
        // TODO: delete files from vector store

        $mixedResult = parent::_delete($mixedId);
        return $mixedResult;
    }

    protected function _getCellSize ($mixedValue, $sKey, $aField, $aRow) 
    {
        $mixedValue = bx_format_bytes($mixedValue);
        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    } 

    protected function _getCellStatus ($mixedValue, $sKey, $aField, $aRow) 
    {
        $mixedValue = $mixedValue == 'processing' ? '<span title="Processing">🕚</span>' : '<span title="Completed">✅</span>';
        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    } 

    static public function processPendingData()
    {
        // TODO: process pending data, e.g. add to vector store, update status, etc.
    }
}

/** @} */
