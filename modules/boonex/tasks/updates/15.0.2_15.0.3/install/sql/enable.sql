-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_view_entry' AND `title`='_bx_tasks_page_block_title_entry_timer';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_tasks_view_entry', 3, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_timer', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:12:\"entity_timer\";}', 0, 0, 1, 2);

DELETE FROM `sys_objects_page` WHERE `object`='bx_tasks_home';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_home', 'tasks-home', '_bx_tasks_page_title_sys_home', '_bx_tasks_page_title_home', 'bx_tasks', 13, 2147483647, 1, 'page.php?i=tasks-home', '', '', '', 0, 1, 0, 'BxTasksPageBrowse', 'modules/boonex/tasks/classes/BxTasksPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_home';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_tasks_home', 2, 'bx_tasks', '_bx_tasks_page_block_title_system_menu_browse', '_bx_tasks_page_block_title_menu_browse', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_menu_browse";}', 0, 1, 1, 0),
('bx_tasks_home', 3, 'bx_tasks', '_bx_tasks_page_block_title_sys_home_entries', '_bx_tasks_page_block_title_home_entries', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:11:"browse_home";}', 0, 1, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_tasks_context_pre_values';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_context_pre_values', '_bx_tasks_page_title_sys_context_pre_values', '_bx_tasks_page_title_context_pre_values', 'bx_tasks', 5, 2147483647, 1, 'tasks-context-values', 'page.php?i=tasks-context-values', '', '', '', 0, 1, 0, 'BxTasksPageAuthor', 'modules/boonex/tasks/classes/BxTasksPageAuthor.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_context_pre_values';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_context_pre_values', 1, 'bx_tasks', '_bx_tasks_page_block_title_system_context_pre_values', '_bx_tasks_page_block_title_context_pre_values', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:28:"get_block_context_pre_values";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_tasks_context_settings';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_context_settings', '_bx_tasks_page_title_sys_context_settings', '_bx_tasks_page_title_context_settings', 'bx_tasks', 5, 2147483647, 1, 'tasks-context-settings', 'page.php?i=tasks-context-settings', '', '', '', 0, 1, 0, 'BxTasksPageAuthor', 'modules/boonex/tasks/classes/BxTasksPageAuthor.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_context_settings';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_context_settings', 1, 'bx_tasks', '_bx_tasks_page_block_title_system_context_settings', '_bx_tasks_page_block_title_context_settings', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:26:"get_block_context_settings";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 1),
('bx_tasks_context_settings', 1, 'bx_tasks', '_bx_tasks_page_block_title_system_context_authorize', '_bx_tasks_page_block_title_context_authorize', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:27:"get_block_context_authorize";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 2);

DELETE FROM `sys_objects_page` WHERE `object`='bx_tasks_timers';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_timers', '_bx_tasks_page_title_sys_timers', '_bx_tasks_page_title_timers', 'bx_tasks', 13, 2147483647, 1, 'tasks-timers', 'page.php?i=tasks-timers', '', '', '', 0, 1, 0, 'BxTasksPageBrowse', 'modules/boonex/tasks/classes/BxTasksPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_timers';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_timers', 2, 'bx_tasks', '_bx_tasks_page_block_title_system_menu_browse', '_bx_tasks_page_block_title_menu_browse', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_menu_browse";}', 0, 1, 0),
('bx_tasks_timers', 3, 'bx_tasks', '_bx_tasks_page_block_title_system_timers', '_bx_tasks_page_block_title_timers', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:16:"get_block_timers";}', 0, 1, 0);


-- MENUS
UPDATE `sys_menu_items` SET `name`='tasks-home', `link`='page.php?i=tasks-home' WHERE `set_name`='sys_site' AND `name`='tasks-manage';

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_tasks_view_actions' AND `name`='report-time';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_tasks_manage_context_submenu';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_manage_context_submenu', '_bx_tasks_menu_title_manage_context_submenu', 'bx_tasks_manage_context_submenu', 'bx_tasks', 6, 0, 1, 'BxTasksMenuManageContext', 'modules/boonex/tasks/classes/BxTasksMenuManageContext.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_tasks_manage_context_submenu';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_tasks_manage_context_submenu', 'bx_tasks', '_bx_tasks_menu_set_title_manage_context_submenu', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_tasks_manage_context_submenu';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_tasks_manage_context_submenu', 'bx_tasks', 'tasks-context-values', '_bx_tasks_menu_item_title_system_manage_context_pre_values', '_bx_tasks_menu_item_title_manage_context_pre_values', 'page.php?i=tasks-context-values&profile_id={context_pid}', '', '', '', '', 2147483647, '', 1, 0, 1),
('bx_tasks_manage_context_submenu', 'bx_tasks', 'tasks-context-settings', '_bx_tasks_menu_item_title_system_manage_context_settings', '_bx_tasks_menu_item_title_manage_context_settings', 'page.php?i=tasks-context-settings&profile_id={context_pid}', '', '', '', '', 2147483647, '', 1, 0, 2);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_tasks_use_tools_submenu';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_use_tools_submenu', '_bx_tasks_menu_title_use_tools_submenu', 'bx_tasks_use_tools_submenu', 'bx_tasks', 26, 0, 1, '', '');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_tasks_use_tools_submenu';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_tasks_use_tools_submenu', 'bx_tasks', '_bx_tasks_menu_set_title_use_tools_submenu', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_tasks_use_tools_submenu';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_tasks_use_tools_submenu', 'bx_tasks', 'tasks-home', '', '_bx_tasks_menu_item_title_ut_submenu_home', 'page.php?i=tasks-home', '', '_self', '', '', 2147483647, 1, 0, 1),
('bx_tasks_use_tools_submenu', 'bx_tasks', 'tasks-timers', '', '_bx_tasks_menu_item_title_ut_submenu_timers', 'page.php?i=tasks-timers', '', '_self', '', '', 2147483647, 1, 0, 2);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_tasks_manage_tools_submenu' AND `name`='tasks-timers';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_tasks_manage_tools_submenu', 'bx_tasks', 'tasks-timers', '', '_bx_tasks_menu_item_title_mt_submenu_timers', 'page.php?i=tasks-timers', '', '_self', '', '', 2147483647, 1, 0, 5);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_tasks_browse';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_browse', '_bx_tasks_menu_title_browse', 'bx_tasks_browse', 'bx_tasks', 32, 0, 1, 'BxTasksMenuBrowse', 'modules/boonex/tasks/classes/BxTasksMenuBrowse.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_tasks_browse';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_tasks_browse', 'bx_tasks', '_bx_tasks_menu_set_title_browse', 0);


-- CONNECTIONS
UPDATE `sys_objects_connection` SET `profile_initiator`='1', `profile_content`='0' WHERE `object`='bx_tasks_assignments';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_tasks_pre_values';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_pre_values', 'Sql', 'SELECT `tpv`.*, `tpl`.`title` AS `list_title`, `tpl`.`use_color` AS `list_color`, `tpl`.`use_multiselect` AS `list_multiselect` FROM `bx_tasks_pre_values` AS `tpv` INNER JOIN `bx_tasks_pre_lists` AS `tpl` ON `tpv`.`list`=`tpl`.`name` WHERE 1 ', 'bx_tasks_pre_values', 'id', 'order', '', '', 20, NULL, 'start', '', 'tpv`.`name,tpv`.`title,tpl`.`title', '', 'like', '', '', 2147483647, 'BxTasksGridPreValues', 'modules/boonex/tasks/classes/BxTasksGridPreValues.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_tasks_pre_values';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_tasks_pre_values', 'order', '', '10%', 0, 0, '', 1),
('bx_tasks_pre_values', 'title', '_bx_tasks_grid_column_title_pv_title', '70%', 0, 0, '', 2),
('bx_tasks_pre_values', 'actions', '', '20%', 0, 0, '', 3);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_tasks_pre_values';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_tasks_pre_values', 'independent', 'add', '_bx_tasks_grid_action_title_pv_add', '', 0, 0, 1),
('bx_tasks_pre_values', 'bulk', 'delete', '_bx_tasks_grid_action_title_pv_delete', '', 0, 1, 1),
('bx_tasks_pre_values', 'single', 'edit', '_bx_tasks_grid_action_title_pv_edit', 'pencil-alt', 1, 0, 1),
('bx_tasks_pre_values', 'single', 'delete', '_bx_tasks_grid_action_title_pv_delete', 'remove', 1, 1, 2);
