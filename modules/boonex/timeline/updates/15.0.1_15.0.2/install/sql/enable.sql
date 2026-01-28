SET @sName = 'bx_timeline';


-- MENUS
UPDATE `sys_menu_items` SET `link`='page.php?i=timeline-view' WHERE `set_name`='sys_add_content_links' AND `name`='create-item';
UPDATE `sys_menu_items` SET `link`='page.php?i=timeline-view' WHERE `set_name`='sys_create_post' AND `name`='create-item';

UPDATE `sys_menu_items` SET `icon`='far clock' WHERE `set_name`='sys_add_content_links' AND `name`='create-item' AND `icon`='far clock col-green1';
UPDATE `sys_menu_items` SET `icon`='far clock' WHERE `set_name`='sys_create_post' AND `name`='create-item' AND `icon`='far clock col-green1';
UPDATE `sys_menu_items` SET `icon`='far clock' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='timeline-view' AND `icon`='far clock col-green1';
UPDATE `sys_menu_items` SET `icon`='far clock' WHERE `set_name`='trigger_group_view_submenu' AND `name`='timeline-view' AND `icon`='far clock col-green1';
UPDATE `sys_menu_items` SET `icon`='far clock' WHERE `set_name` LIKE '%_view_submenu' AND `name`='timeline-view' AND `icon`='far clock col-green1';
UPDATE `sys_menu_items` SET `icon`='far clock' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-timeline' AND `icon`='far clock col-green1';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_browse' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_filters_style', 'bx_timeline_filters_media_hide');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_filters_style', 'default', @iCategId, '_bx_timeline_option_filters_style', 'select', '', '', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:25:"get_options_filters_style";}', 70),
('bx_timeline_filters_media_hide', '', @iCategId, '_bx_timeline_option_filters_media_hide', 'rlist', '', '', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:25:"get_options_filters_media";}', 77);
