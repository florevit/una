-- MENUS
UPDATE `sys_menu_items` SET `icon`='camera-retro' WHERE `set_name`='sys_site' AND `name`='photos-home' AND `icon`='camera-retro col-blue1';
UPDATE `sys_menu_items` SET `icon`='camera-retro' WHERE `set_name`='sys_homepage' AND `name`='photos-home' AND `icon`='camera-retro col-blue1';
UPDATE `sys_menu_items` SET `icon`='camera-retro' WHERE `set_name`='sys_add_content_links' AND `name`='create-photo' AND `icon`='camera-retro col-blue1';
UPDATE `sys_menu_items` SET `icon`='camera-retro' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-photos' AND `icon`='camera-retro col-blue1';
UPDATE `sys_menu_items` SET `icon`='camera-retro' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='photos-author' AND `icon`='camera-retro col-blue1';
UPDATE `sys_menu_items` SET `icon`='camera-retro' WHERE `set_name`='trigger_group_view_submenu' AND `name`='photos-context' AND `icon`='camera-retro col-blue1';
UPDATE `sys_menu_items` SET `icon`='camera-retro' WHERE `set_name` LIKE '%_view_submenu' AND `name`='photos-author' AND `icon`='camera-retro col-blue1';
UPDATE `sys_menu_items` SET `icon`='camera-retro' WHERE `set_name` LIKE '%_view_submenu' AND `name`='photos-context' AND `icon`='camera-retro col-blue1';


-- STATS
UPDATE `sys_statistics` SET `icon`='camera-retro' WHERE `name`='bx_photos' AND `icon`='camera-retro col-blue1';
