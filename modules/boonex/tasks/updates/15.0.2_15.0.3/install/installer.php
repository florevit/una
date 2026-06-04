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
            if(!$this->oDb->isFieldExists('bx_tasks_tasks', 'stickers'))
                $this->oDb->query("ALTER TABLE `bx_tasks_tasks` ADD `stickers` int(11) NOT NULL default '0' AFTER `title`");
            if(!$this->oDb->isFieldExists('bx_tasks_tasks', 'gh_issue'))
                $this->oDb->query("ALTER TABLE `bx_tasks_tasks` ADD `gh_issue` int(11) NOT NULL default '0' AFTER `tasks_list`");
            if(!$this->oDb->isFieldExists('bx_tasks_tasks', 'gh_issue_url'))
                $this->oDb->query("ALTER TABLE `bx_tasks_tasks` ADD `gh_issue_url` varchar(255) NOT NULL default '' AFTER `gh_issue`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
