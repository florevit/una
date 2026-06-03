SET @sName = 'bx_channels';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='hashtag' WHERE `set_name`='sys_site' AND `name`='channels-home' AND `icon`='hashtag col-red2';
UPDATE `sys_menu_items` SET `icon`='hashtag' WHERE `set_name`='sys_homepage' AND `name`='channels-home' AND `icon`='hashtag col-red2';
UPDATE `sys_menu_items` SET `icon`='hashtag' WHERE `set_name`='sys_profile_followings' AND `name`='channels' AND `icon`='hashtag col-red2';
UPDATE `sys_menu_items` SET `icon`='hashtag' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='channels-author' AND `icon`='hashtag col-red2';
UPDATE `sys_menu_items` SET `icon`='hashtag' WHERE `set_name` LIKE '%_view_submenu' AND `name`='channels-author' AND `icon`='hashtag col-red2';

-- STATS
UPDATE `sys_statistics` SET `icon`='hashtag' WHERE `name`='bx_channels' AND `icon`='hashtag col-red2';
