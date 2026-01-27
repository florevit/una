<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxForumFormEntry extends BxBaseModTextFormEntry
{
    protected $_sGhostTemplateCover = 'form_ghost_template_cover.html';

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_forum';

        parent::__construct($aInfo, $oTemplate);
    }

    public function insert($aValsToAdd = [], $isIgnore = false)
    {
    	$CNF = $this->_oModule->_oConfig->CNF;

        return parent::insert(array_merge($aValsToAdd, [
            'lr_timestamp' => time(),
            'lr_profile_id' => (isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && $this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1) * bx_get_logged_profile_id()
        ]), $isIgnore);
    }

    public function update ($iContentId, $aValsToAdd = [], &$aTrackTextFieldsChanges = null)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        $aContentInfo = $this->_oModule->_oDb->getContentInfoById ($iContentId);
        if (isset($CNF['FIELD_ANONYMOUS']) && isset($this->aInputs[$CNF['FIELD_ANONYMOUS']]) && !$aContentInfo['lr_comment_id'])
            $aValsToAdd['lr_profile_id'] = ($this->getCleanValue($CNF['FIELD_ANONYMOUS']) ? -1 : 1) * abs($aContentInfo['lr_profile_id']);

        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }
    
    public function delete ($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $mixedResult = parent::delete($iContentId, $aContentInfo);
        if($mixedResult !== false) {
            if(!empty($CNF['OBJECT_CONNECTION_SUBSCRIBERS']))
                BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION_SUBSCRIBERS'])->onDeleteContent($iContentId);
        }

        return $mixedResult;
    }
}

/** @} */
