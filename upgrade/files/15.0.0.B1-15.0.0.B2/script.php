<?php

    if (!$this->oDb->isFieldExists('sys_acl_levels', 'UnavailableTo'))
        $this->oDb->query("ALTER TABLE `sys_acl_levels` ADD `UnavailableTo` varchar(255) NOT NULL default '' AFTER `Removable`");


    if (!$this->oDb->isFieldExists('sys_accounts', 'welcome_sent'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `welcome_sent` tinyint(4) NOT NULL DEFAULT '0' AFTER `phone_confirmed`");


    if (!$this->oDb->isFieldExists('sys_alerts_handlers', 'ts'))
        $this->oDb->query("ALTER TABLE `sys_alerts_handlers` ADD `ts` int(10) UNSIGNED NOT NULL");

    if (!$this->oDb->isFieldExists('sys_alerts_handlers', 'timing'))
        $this->oDb->query("ALTER TABLE `sys_alerts_handlers` ADD `timing` float NOT NULL");

    if (!$this->oDb->isFieldExists('sys_alerts_handlers', 'call_count'))
        $this->oDb->query("ALTER TABLE `sys_alerts_handlers` ADD `call_count` int(10) UNSIGNED NOT NULL");

    if (!$this->oDb->isFieldExists('sys_alerts_handlers', 'calls_per_request'))
        $this->oDb->query("ALTER TABLE `sys_alerts_handlers` ADD `calls_per_request` int(10) UNSIGNED NOT NULL");



    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'description'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `description` varchar(255) NOT NULL DEFAULT '' AFTER `title`");

    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'icon'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `icon` text NOT NULL AFTER `description`");


    if (!$this->oDb->isIndexExists('sys_api_keys', 'key'))
        $this->oDb->query("ALTER TABLE `sys_api_keys` ADD UNIQUE KEY `key` (`key`)");
    
    return true;
