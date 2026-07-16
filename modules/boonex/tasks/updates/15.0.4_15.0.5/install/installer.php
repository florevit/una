<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxTasksUpdater extends BxDolStudioUpdater
{
    public function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_tasks_pre_values', 'active'))
                $this->oDb->query("ALTER TABLE `bx_tasks_pre_values` ADD `active` tinyint(4) NOT NULL DEFAULT '1' AFTER `color`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
