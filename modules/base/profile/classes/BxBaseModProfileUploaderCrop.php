<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfileUploaderCrop extends BxTemplUploaderCrop
{
    protected $_sModule;
    protected $_oModule;

    protected $_sImage;
    protected $_aOtherImages;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        if(!empty($this->_sModule) && empty($this->_oModule))
            $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate ? $oTemplate : $this->_oModule->_oTemplate);
    }

    public function deleteGhostsForProfile($iProfileId, $mixedContent = false)
    {
        if(!$mixedContent)
            return parent::deleteGhostsForProfile($iProfileId, $mixedContent);

        $iCount = 0;

        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);
        if($oStorage === false)
            return $iCount;

        $iContentId = (int)(is_array($mixedContent) ? reset($mixedContent) : $mixedContent);
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        $aGhosts = $oStorage->getGhosts($iProfileId, $mixedContent, $iContentId ? true : false);
        foreach ($aGhosts as $aFile) {
            // for requested image type delete only unassigned ghosts and currently set images
            if ($aFile['id'] == $aContentInfo[$this->_sImage] || !in_array($aFile['id'], array_map(function($sField) use ($aContentInfo) {
                return $aContentInfo[$sField];
            }, $this->_aOtherImages)))
                $iCount += $oStorage->deleteFile($aFile['id']);
        }

        return $iCount;
    }
    
    public function getGhosts($iProfileId, $sFormat, $sImagesTranscoder = false, $iContentId = false)
    {
        $s = parent::getGhosts($iProfileId, $sFormat, $sImagesTranscoder, $iContentId);
        if (!$s || !$iContentId) // if we're creating new profile return all ghosts
            return $s;

        $a = array();
        if ($sFormat == 'array')
            $a = $s;
        else if ($sFormat == 'json')
            $a = json_decode($s, true);

        if (!$a)
            return $s;

        // filter out thumbnails
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

        $aResult = [];
        foreach ($a as $aFile) {
            // for requested image type show only (1) currently set images and (2) unassigned ghosts by uploader
            if ($aFile['file_id'] == $aContentInfo[$this->_sImage] || (!in_array($aFile['file_id'], array_map(function($sField) use ($aContentInfo) {
                return $aContentInfo[$sField];
            }, $this->_aOtherImages)) && $aFile['uploader_id'] == $this->_iId))
                $aResult[$aFile['file_id']] = $aFile;
        }

        if ('array' == $sFormat) {
            return $aResult;
        }
        else if ('json' == $sFormat) {
            return json_encode($aResult);
        } else { // html format is not suported for this data type
            return false;
        }
    }

    protected function isAdmin ($iContentId = 0)
    {
        return $this->_oModule->_isModerator (false);
    }
}

/** @} */
