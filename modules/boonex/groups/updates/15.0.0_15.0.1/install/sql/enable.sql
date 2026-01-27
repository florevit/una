-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_edit_profile_settings';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_edit_profile_settings', 'edit-group-settings', '_bx_groups_page_title_sys_edit_profile_settings', '_bx_groups_page_title_edit_profile_settings', 'bx_groups', 5, 2147483647, 1, 'page.php?i=edit-group-settings', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_edit_profile_settings';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_edit_profile_settings', 1, 'bx_groups', '_bx_groups_page_block_title_edit_profile_settings', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:20:"entity_edit_settings";}', 0, 0, 1, 1);


-- MENUS
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_site' AND `name`='groups-home' AND `icon`='users col-red2';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_homepage' AND `name`='groups-home' AND `icon`='users col-red2';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_add_content_links' AND `name`='create-group-profile' AND `icon`='users col-red2';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_more' AND `name`='edit-group-settings';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions_more', 'bx_groups', 'edit-group-settings', '_bx_groups_menu_item_title_system_edit_settings', '_bx_groups_menu_item_title_edit_settings', 'page.php?i=edit-group-settings&id={content_id}', '', '', 'toolbox', '', 2147483647, '', 1, 0, 42);

UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='bx_groups_view_submenu' AND `name`='view-group-profile' AND `icon`='users col-red2';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-groups' AND `icon`='users col-red2';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_profile_followings' AND `name`='groups' AND `icon`='users col-red2';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='joined-groups' AND `icon`='users col-red2';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='trigger_group_view_submenu' AND `name`='groups-context' AND `icon`='users col-red2';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name` LIKE '%_view_submenu' AND `name`='joined-groups' AND `icon`='users col-red2';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name` LIKE '%_view_submenu' AND `name`='groups-context' AND `icon`='users col-red2';


-- STATS
UPDATE `sys_statistics` SET `icon`='users' WHERE `name`='bx_groups' AND `icon`='users col-red2';
