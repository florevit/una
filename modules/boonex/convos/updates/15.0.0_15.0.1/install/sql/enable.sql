SET @sName = 'bx_convos';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='comments' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-convos' AND `icon`='comments col-red1';
UPDATE `sys_menu_items` SET `icon`='comments' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-unread-messages' AND `icon`='comments col-red1';
