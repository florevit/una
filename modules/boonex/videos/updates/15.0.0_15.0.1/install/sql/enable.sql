-- MENUS
UPDATE `sys_menu_items` SET `icon`='film' WHERE `set_name`='sys_site' AND `name`='videos-home' AND `icon`='film col-gray';
UPDATE `sys_menu_items` SET `icon`='film' WHERE `set_name`='sys_homepage' AND `name`='videos-home' AND `icon`='film col-gray';
UPDATE `sys_menu_items` SET `icon`='film' WHERE `set_name`='sys_add_content_links' AND `name`='create-video' AND `icon`='film col-gray';
UPDATE `sys_menu_items` SET `icon`='film' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-videos' AND `icon`='film col-gray';
UPDATE `sys_menu_items` SET `icon`='film' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='videos-author' AND `icon`='film col-gray';
UPDATE `sys_menu_items` SET `icon`='film' WHERE `set_name`='trigger_group_view_submenu' AND `name`='videos-context' AND `icon`='film col-gray';
UPDATE `sys_menu_items` SET `icon`='film' WHERE `set_name` LIKE '%_view_submenu' AND `name`='videos-author' AND `icon`='film col-gray';
UPDATE `sys_menu_items` SET `icon`='film' WHERE `set_name` LIKE '%_view_submenu' AND `name`='videos-context' AND `icon`='film col-gray';


-- STATS
UPDATE `sys_statistics` SET `icon`='film' WHERE `name`='bx_videos' AND `icon`='film col-gray';
