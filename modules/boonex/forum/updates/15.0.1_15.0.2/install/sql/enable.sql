SET @sName = 'bx_forum';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name`='sys_site' AND `name`='discussions-home' AND `icon`='far comments col-blue2';
UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name`='sys_homepage' AND `name`='discussions-home' AND `icon`='far comments col-blue2';
UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name`='sys_add_content_links' AND `name`='create-discussion' AND `icon`='far comments col-blue2';
UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name`='sys_create_post' AND `name`='create-discussion' AND `icon`='far comments col-blue2';
UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-forum' AND `icon`='far comments col-blue2';
UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='discussions-author' AND `icon`='far comments col-blue2';
UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name`='trigger_group_view_submenu' AND `name`='discussions-context' AND `icon`='far comments col-blue2';
UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name` LIKE '%_view_submenu' AND `name`='discussions-author' AND `icon`='far comments col-blue2';
UPDATE `sys_menu_items` SET `icon`='far comments' WHERE `set_name` LIKE '%_view_submenu' AND `name`='discussions-context' AND `icon`='far comments col-blue2';


-- STATS
UPDATE `sys_statistics` SET `icon`='far comments' WHERE `name`='bx_forum' AND `icon`='far comments col-blue2';
