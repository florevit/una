-- MENUS
UPDATE `sys_menu_items` SET `icon`='far file' WHERE `set_name`='sys_site' AND `name`='files-home' AND `icon`='far file col-red3';
UPDATE `sys_menu_items` SET `icon`='far file' WHERE `set_name`='sys_homepage' AND `name`='files-home' AND `icon`='far file col-red3';
UPDATE `sys_menu_items` SET `icon`='far file' WHERE `set_name`='sys_add_content_links' AND `name`='create-file' AND `icon`='far file col-red3';
UPDATE `sys_menu_items` SET `icon`='far file' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-files' AND `icon`='far file col-red3';
UPDATE `sys_menu_items` SET `icon`='far file' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='files-author' AND `icon`='far file col-red3';
UPDATE `sys_menu_items` SET `icon`='far file' WHERE `set_name`='trigger_group_view_submenu' AND `name`='files-context' AND `icon`='far file col-red3';
UPDATE `sys_menu_items` SET `icon`='far file' WHERE `set_name` LIKE '%_view_submenu' AND `name`='files-author' AND `icon`='far file col-red3';
UPDATE `sys_menu_items` SET `icon`='far file' WHERE `set_name` LIKE '%_view_submenu' AND `name`='files-context' AND `icon`='far file col-red3';


-- STATS
UPDATE `sys_statistics` SET `icon`='far file' WHERE `name`='bx_files' AND `icon`='far file col-red3';
