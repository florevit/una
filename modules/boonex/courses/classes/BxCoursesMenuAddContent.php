<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

class BxCoursesMenuAddContent extends BxBaseModGroupsAddContent
{    
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_courses';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
