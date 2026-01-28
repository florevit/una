<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Profile's timeline page.
 */
class BxTimelinePageView extends BxBaseModTextPageAuthor
{
    public function __construct($aObject, $oTemplate = false)
    {
        if(bx_get('profile_id') === false && ($this->_oProfile = BxDolProfile::getInstance()) !== false)
            $this->_aProfileInfo = $this->_oProfile->getInfo();

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
