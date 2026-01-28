-- MENUS
UPDATE `sys_menu_items` SET `icon`='object-group' WHERE `set_name`='sys_site' AND `name`='spaces-home' AND `icon`='object-group col-red2';
UPDATE `sys_menu_items` SET `icon`='object-group' WHERE `set_name`='sys_homepage' AND `name`='spaces-home' AND `icon`='object-group col-red2';
UPDATE `sys_menu_items` SET `icon`='object-group' WHERE `set_name`='sys_add_content_links' AND `name`='create-space-profile' AND `icon`='object-group col-red2';
UPDATE `sys_menu_items` SET `icon`='object-group' WHERE `set_name`='bx_spaces_view_submenu' AND `name`='view-space-profile' AND `icon`='object-group col-red2';
UPDATE `sys_menu_items` SET `icon`='object-group' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-spaces' AND `icon`='object-group col-red2';
UPDATE `sys_menu_items` SET `icon`='object-group' WHERE `set_name`='sys_profile_followings' AND `name`='spaces' AND `icon`='object-group col-red2';
UPDATE `sys_menu_items` SET `icon`='object-group' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='joined-spaces' AND `icon`='object-group col-red2';
UPDATE `sys_menu_items` SET `icon`='object-group' WHERE `set_name` LIKE '%_view_submenu' AND `name`='joined-spaces' AND `icon`='object-group col-red2';


-- STATS
UPDATE `sys_statistics` SET `icon`='object-group' WHERE `name`='bx_spaces' AND `icon`='object-group col-red2';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_spaces' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='bx_timeline' AND `action`='get_external_post' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_timeline', 'get_external_post', @iHandler);
