-- MENUS
UPDATE `sys_menu_items` SET `icon`='far image' WHERE `set_name`='sys_site' AND `name`='albums-home' AND `icon`='far image col-blue1';
UPDATE `sys_menu_items` SET `icon`='far image' WHERE `set_name`='sys_homepage' AND `name`='albums-home' AND `icon`='far image col-blue1';
UPDATE `sys_menu_items` SET `icon`='far image' WHERE `set_name`='sys_add_content_links' AND `name`='create-album' AND `icon`='far image col-blue1';
UPDATE `sys_menu_items` SET `icon`='far image' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-albums' AND `icon`='far image col-blue1';
UPDATE `sys_menu_items` SET `icon`='far image' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='albums-author' AND `icon`='far image col-blue1';
UPDATE `sys_menu_items` SET `icon`='far image' WHERE `set_name`='trigger_group_view_submenu' AND `name`='albums-context' AND `icon`='far image col-blue1';
UPDATE `sys_menu_items` SET `icon`='far image' WHERE `set_name` LIKE '%_view_submenu' AND `name`='albums-author' AND `icon`='far image col-blue1';
UPDATE `sys_menu_items` SET `icon`='far image' WHERE `set_name` LIKE '%_view_submenu' AND `name`='albums-context' AND `icon`='far image col-blue1';


-- STATS
UPDATE `sys_statistics` SET `icon`='far image' WHERE `name`='bx_albums' AND `icon`='far image col-blue1';
UPDATE `sys_statistics` SET `icon`='far image' WHERE `name`='bx_albums_media' AND `icon`='far image col-blue1';
