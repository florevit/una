<?php

    if (!$this->oDb->isFieldExists('sys_storage_ghosts', 'uploader_id'))
        $this->oDb->query("ALTER TABLE `sys_storage_ghosts` ADD `uploader_id` int(11) NOT NULL default '0' AFTER `object`");

    return true;
