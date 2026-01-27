-- MENUS
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-classes' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='trigger_group_view_submenu' AND `name`='classes-context' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name` LIKE '%_view_submenu' AND `name`='classes-context' AND `icon`='file-alt col-red3';

-- STATS
UPDATE `sys_statistics` SET `icon`='file-alt' WHERE `name`='bx_classes' AND `icon`='file-alt col-red3';
