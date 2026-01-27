SET @sName = 'bx_notifications';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='bell' WHERE `set_name`='sys_toolbar_member' AND `name`='notifications-preview' AND `icon`='bell col-green3';
UPDATE `sys_menu_items` SET `icon`='bell' WHERE `set_name`='sys_ntfs_submenu' AND `name`='notifications-view' AND `icon`='bell col-green3';
UPDATE `sys_menu_items` SET `icon`='bell' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-notifications' AND `icon`='bell col-green3';
UPDATE `sys_menu_items` SET `icon`='bell' WHERE `set_name`='sys_account_settings' AND `name`='notifications-settings' AND `icon`='bell col-green3';


-- SETTINGS
UPDATE `sys_options` SET `value`='24' WHERE `name`='bx_notifications_events_per_page';
