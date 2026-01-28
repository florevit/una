-- MENUS
UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name`='sys_site' AND `name`='polls-home' AND `icon`='tasks col-green1';
UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name`='sys_homepage' AND `name`='polls-home' AND `icon`='tasks col-green1';
UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name`='sys_add_content_links' AND `name`='create-poll' AND `icon`='tasks col-green1';
UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-polls' AND `icon`='tasks col-green1';
UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='polls-author' AND `icon`='tasks col-green1';
UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name`='trigger_group_view_submenu' AND `name`='polls-context' AND `icon`='tasks col-green1';
UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name` LIKE '%_view_submenu' AND `name`='polls-author' AND `icon`='tasks col-green1';
UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name` LIKE '%_view_submenu' AND `name`='polls-context' AND `icon`='tasks col-green1';


-- STATS
UPDATE `sys_statistics` SET `icon`='tasks' WHERE `name`='bx_polls' AND `icon`='tasks col-green1';
