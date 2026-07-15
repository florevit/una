-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='13' WHERE `object`='bx_tasks_context_pre_values';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_context_pre_values' AND `title`='_bx_tasks_page_block_title_menu_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_tasks_context_pre_values', 2, 'bx_tasks', '_bx_tasks_page_block_title_sys_menu_in_context', '_bx_tasks_page_block_title_menu_in_context', 13, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:22:"get_block_menu_context";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 1, 1);
UPDATE `sys_pages_blocks` SET `cell_id`='3', `copyable`='0' WHERE `object`='bx_tasks_context_pre_values' AND `title`='_bx_tasks_page_block_title_context_pre_values';

UPDATE `sys_objects_page` SET `layout_id`='13' WHERE `object`='bx_tasks_context_settings';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_context_settings' AND `title`='_bx_tasks_page_block_title_menu_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_tasks_context_settings', 2, 'bx_tasks', '_bx_tasks_page_block_title_sys_menu_in_context', '_bx_tasks_page_block_title_menu_in_context', 13, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:22:"get_block_menu_context";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 1, 1);
UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_tasks_context_settings' AND `title` IN ('_bx_tasks_page_block_title_context_settings', '_bx_tasks_page_block_title_context_authorize');

UPDATE `sys_objects_page` SET `layout_id`='13' WHERE `object`='bx_tasks_manage';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_manage' AND `title`='_bx_tasks_page_block_title_menu_browse';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_manage', 2, 'bx_tasks', '_bx_tasks_page_block_title_system_menu_browse', '_bx_tasks_page_block_title_menu_browse', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_menu_browse";}', 0, 1, 0);
UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_tasks_manage' AND `title`='_bx_tasks_page_block_title_manage';

UPDATE `sys_objects_page` SET `layout_id`='13' WHERE `object`='bx_tasks_administration';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_administration' AND `title`='_bx_tasks_page_block_title_menu_browse';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_administration', 2, 'bx_tasks', '_bx_tasks_page_block_title_system_menu_browse', '_bx_tasks_page_block_title_menu_browse', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_menu_browse";}', 0, 1, 0);
UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_tasks_administration' AND `title`='_bx_tasks_page_block_title_manage';

UPDATE `sys_objects_page` SET `layout_id`='13' WHERE `object`='bx_tasks_time_manage';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_time_manage' AND `title`='_bx_tasks_page_block_title_menu_browse';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_time_manage', 2, 'bx_tasks', '_bx_tasks_page_block_title_system_menu_browse', '_bx_tasks_page_block_title_menu_browse', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_menu_browse";}', 0, 1, 0);
UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_tasks_time_manage' AND `title`='_bx_tasks_page_block_title_time_manage';

UPDATE `sys_objects_page` SET `layout_id`='13' WHERE `object`='bx_tasks_time_administration';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_time_administration' AND `title`='_bx_tasks_page_block_title_menu_browse';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_time_administration', 2, 'bx_tasks', '_bx_tasks_page_block_title_system_menu_browse', '_bx_tasks_page_block_title_menu_browse', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_menu_browse";}', 0, 1, 0);
UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_tasks_time_administration' AND `title`='_bx_tasks_page_block_title_time_manage';


-- MENUS
UPDATE `sys_objects_menu` SET `override_class_name`='BxTasksMenuViewContext', `override_class_file`='modules/boonex/tasks/classes/BxTasksMenuViewContext.php' WHERE `object`='bx_tasks_view_context_submenu';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_tasks_manage_context_submenu';
DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_tasks_manage_context_submenu';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_tasks_manage_context_submenu';

UPDATE `sys_menu_items` SET `visibility_custom`='' WHERE `set_name`='bx_tasks_view_context_submenu' AND `name`='tasks-context-time-administration';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_tasks_view_context_submenu' AND `name` IN ('tasks-context-values', 'tasks-context-settings');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_tasks_view_context_submenu', 'bx_tasks', 'tasks-context-values', '_bx_tasks_menu_item_title_system_manage_context_pre_values', '_bx_tasks_menu_item_title_manage_context_pre_values', 'page.php?i=tasks-context-values&profile_id={profile_id}', '', '', '', '', 2147483647, '', 1, 0, 4),
('bx_tasks_view_context_submenu', 'bx_tasks', 'tasks-context-settings', '_bx_tasks_menu_item_title_system_manage_context_settings', '_bx_tasks_menu_item_title_manage_context_settings', 'page.php?i=tasks-context-settings&profile_id={profile_id}', '', '', '', '', 2147483647, '', 1, 0, 5);


-- GRIDS
UPDATE `sys_objects_grid` SET `field_active`='active' WHERE `object`='bx_tasks_pre_values';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_tasks_pre_values';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_tasks_pre_values', 'checkbox', '_sys_select', '3%', 0, 0, '', 1),
('bx_tasks_pre_values', 'order', '', '3%', 0, 0, '', 2),
('bx_tasks_pre_values', 'switcher', '_bx_tasks_grid_column_title_pv_active', '9%', 0, 0, '', 3),
('bx_tasks_pre_values', 'title', '_bx_tasks_grid_column_title_pv_title', '65%', 0, 0, '', 4),
('bx_tasks_pre_values', 'actions', '', '20%', 0, 0, '', 5);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_tasks_pre_values' AND `type`='bulk';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_tasks_pre_values', 'bulk', 'activate', '_bx_tasks_grid_action_title_pv_activate', '', 0, 0, 1),
('bx_tasks_pre_values', 'bulk', 'deactivate', '_bx_tasks_grid_action_title_pv_deactivate', '', 0, 0, 2),
('bx_tasks_pre_values', 'bulk', 'delete', '_bx_tasks_grid_action_title_pv_delete', '', 0, 1, 3);
