-- PAGES
UPDATE `sys_pages_blocks` SET `title_system`='_bx_tasks_page_block_title_sys_entry_comments' WHERE `object`='bx_tasks_view_entry' AND `title`='_bx_tasks_page_block_title_entry_comments';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_tasks_page_block_title_sys_entry_comments' WHERE `object`='bx_tasks_view_entry_comments' AND `title`='_bx_tasks_page_block_title_entry_comments_link';

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_manage_time";s:6:"params";a:2:{i:0;s:6:"common";i:1;s:12:"{profile_id}";}}' WHERE `object`='bx_tasks_context_time' AND `title_system`='_bx_tasks_page_block_title_sys_entries_time_in_context';
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_manage_time";s:6:"params";a:2:{i:0;s:14:"administration";i:1;s:12:"{profile_id}";}}' WHERE `object`='bx_tasks_context_time_administration' AND `title_system`='_bx_tasks_page_block_title_sys_entries_time_in_context_administration';

DELETE FROM `sys_objects_page` WHERE `object`='bx_tasks_manage';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_manage', '_bx_tasks_page_title_sys_manage', '_bx_tasks_page_title_manage', 'bx_tasks', 5, 2147483647, 1, 'tasks-manage', 'page.php?i=tasks-manage', '', '', '', 0, 1, 0, 'BxTasksPageBrowse', 'modules/boonex/tasks/classes/BxTasksPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_manage';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_manage', 1, 'bx_tasks', '_bx_tasks_page_block_title_system_manage', '_bx_tasks_page_block_title_manage', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:12:"manage_tools";}', 0, 1, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_tasks_administration';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_administration', '_bx_tasks_page_title_sys_manage_administration', '_bx_tasks_page_title_manage', 'bx_tasks', 5, 192, 1, 'tasks-administration', 'page.php?i=tasks-administration', '', '', '', 0, 1, 0, 'BxTasksPageBrowse', 'modules/boonex/tasks/classes/BxTasksPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_administration';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_administration', 1, 'bx_tasks', '_bx_tasks_page_block_title_system_manage_administration', '_bx_tasks_page_block_title_manage', 11, 192, 'service', 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:12:"manage_tools";s:6:"params";a:1:{i:0;s:14:"administration";}}', 0, 1, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_tasks_time_manage';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_time_manage', '_bx_tasks_page_title_sys_time_manage', '_bx_tasks_page_title_time_manage', 'bx_tasks', 5, 2147483647, 1, 'tasks-time-manage', 'page.php?i=tasks-time-manage', '', '', '', 0, 1, 0, 'BxTasksPageBrowse', 'modules/boonex/tasks/classes/BxTasksPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_time_manage';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_time_manage', 1, 'bx_tasks', '_bx_tasks_page_block_title_system_time_manage', '_bx_tasks_page_block_title_time_manage', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_manage_time";s:6:"params";a:1:{i:0;s:6:"common";}}', 0, 1, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_tasks_time_administration';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_time_administration', '_bx_tasks_page_title_sys_time_manage_administration', '_bx_tasks_page_title_time_manage', 'bx_tasks', 5, 192, 1, 'tasks-time-administration', 'page.php?i=tasks-time-administration', '', '', '', 0, 1, 0, 'BxTasksPageBrowse', 'modules/boonex/tasks/classes/BxTasksPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_tasks_time_administration';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_time_administration', 1, 'bx_tasks', '_bx_tasks_page_block_title_system_time_manage_administration', '_bx_tasks_page_block_title_time_manage', 11, 192, 'service', 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_block_manage_time";s:6:"params";a:1:{i:0;s:14:"administration";}}', 0, 1, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_site' AND `name`='tasks-manage';
SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 AND `order` < 9999 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_tasks', 'tasks-manage', '_bx_tasks_menu_item_title_system_entries_home', '_bx_tasks_menu_item_title_entries_home', 'page.php?i=tasks-manage', '', '', 'tasks', '', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_tasks_manage_tools_submenu';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_manage_tools_submenu', '_bx_tasks_menu_title_manage_tools_submenu', 'bx_tasks_manage_tools_submenu', 'bx_tasks', 26, 0, 1, '', '');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_tasks_manage_tools_submenu';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_tasks_manage_tools_submenu', 'bx_tasks', '_bx_tasks_menu_set_title_manage_tools_submenu', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_tasks_manage_tools_submenu';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_tasks_manage_tools_submenu', 'bx_tasks', 'tasks-manage', '', '_bx_tasks_menu_item_title_mt_submenu_entries_common', 'page.php?i=tasks-manage', '', '_self', '', '', 2147483647, 1, 0, 1),
('bx_tasks_manage_tools_submenu', 'bx_tasks', 'tasks-administration', '', '_bx_tasks_menu_item_title_mt_submenu_entries_administration', 'page.php?i=tasks-administration', '', '_self', '', '', 128, 1, 0, 2),
('bx_tasks_manage_tools_submenu', 'bx_tasks', 'tasks-time-manage', '', '_bx_tasks_menu_item_title_mt_submenu_time_common', 'page.php?i=tasks-time-manage', '', '_self', '', '', 2147483647, 1, 0, 3),
('bx_tasks_manage_tools_submenu', 'bx_tasks', 'tasks-time-administration', '', '_bx_tasks_menu_item_title_mt_submenu_time_administration', 'page.php?i=tasks-time-administration', '', '_self', '', '', 128, 1, 0, 4);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_tasks_menu_manage_tools';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_menu_manage_tools', '_bx_tasks_menu_title_manage_tools', 'bx_tasks_menu_manage_tools', 'bx_tasks', 6, 0, 1, 'BxTasksMenuManageTools', 'modules/boonex/tasks/classes/BxTasksMenuManageTools.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_tasks_menu_manage_tools';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_tasks_menu_manage_tools', 'bx_tasks', '_bx_tasks_menu_set_title_manage_tools', 0);

UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name`='trigger_group_view_submenu' AND `name`='tasks-context' AND `icon`='tasks col-red3';
UPDATE `sys_menu_items` SET `icon`='tasks' WHERE `set_name` LIKE '%_view_submenu' AND `name`='tasks-context' AND `icon`='tasks col-red3';


-- STATS
UPDATE `sys_statistics` SET `icon`='tasks' WHERE `name`='bx_tasks' AND `icon`='tasks col-red3';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_tasks_administration', 'bx_tasks_common');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_administration', 'Sql', 'SELECT `tt`.*, `tp`.`id` AS `context_id`, `tp`.`type` AS `context_module` FROM `bx_tasks_tasks` AS `tt` INNER JOIN `sys_profiles` AS `tp` ON ABS(`tt`.`allow_view_to`)=`tp`.`id` WHERE 1 ', 'bx_tasks_tasks', 'id', 'added', 'status_admin', '', 20, NULL, 'start', '', 'tt`.`title,tt`.`text', '', 'like', 'reports', '', 192, 'BxTasksGridAdministration', 'modules/boonex/tasks/classes/BxTasksGridAdministration.php'),
('bx_tasks_common', 'Sql', 'SELECT `tt`.*, `tp`.`id` AS `context_id`, `tp`.`type` AS `context_module` FROM `bx_tasks_tasks` AS `tt` INNER JOIN `sys_profiles` AS `tp` ON ABS(`tt`.`allow_view_to`)=`tp`.`id` WHERE 1 ', 'bx_tasks_tasks', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'tt`.`title,tt`.`text', '', 'like', '', '', 2147483647, 'BxTasksGridCommon', 'modules/boonex/tasks/classes/BxTasksGridCommon.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_tasks_administration', 'bx_tasks_common');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_tasks_administration', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_tasks_administration', 'switcher', '_bx_tasks_grid_column_title_adm_active', '8%', 0, 0, '', 2),
('bx_tasks_administration', 'reports', '_sys_txt_reports_title', '5%', 0, 0, '', 3),
('bx_tasks_administration', 'context_module', '_bx_tasks_grid_column_title_adm_context_module', '10%', 0, 0, '', 4),
('bx_tasks_administration', 'title', '_bx_tasks_grid_column_title_adm_title', '25%', 0, 25, '', 5),
('bx_tasks_administration', 'added', '_bx_tasks_grid_column_title_adm_added', '10%', 1, 25, '', 6),
('bx_tasks_administration', 'author', '_bx_tasks_grid_column_title_adm_author', '20%', 0, 25, '', 7),
('bx_tasks_administration', 'actions', '', '20%', 0, 0, '', 8),

('bx_tasks_common', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_tasks_common', 'switcher', '_bx_tasks_grid_column_title_adm_active', '8%', 0, 0, '', 2),
('bx_tasks_common', 'title', '_bx_tasks_grid_column_title_adm_title', '35%', 0, 35, '', 3),
('bx_tasks_common', 'context_module', '_bx_tasks_grid_column_title_adm_context_module', '10%', 0, 0, '', 4),
('bx_tasks_common', 'added', '_bx_tasks_grid_column_title_adm_added', '10%', 1, 25, '', 5),
('bx_tasks_common', 'status_admin', '_bx_tasks_grid_column_title_adm_status_admin', '15%', 0, 16, '', 6),
('bx_tasks_common', 'actions', '', '20%', 0, 0, '', 7);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_tasks_administration', 'bx_tasks_common');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_tasks_administration', 'bulk', 'delete', '_bx_tasks_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_tasks_administration', 'bulk', 'clear_reports', '_bx_tasks_grid_action_title_adm_clear_reports', '', 0, 1, 1),
('bx_tasks_administration', 'single', 'edit', '_bx_tasks_grid_action_title_adm_edit', 'pencil-alt', 1, 0, 1),
('bx_tasks_administration', 'single', 'delete', '_bx_tasks_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_tasks_administration', 'single', 'settings', '_bx_tasks_grid_action_title_adm_more_actions', 'cog', 1, 0, 3),
('bx_tasks_administration', 'single', 'audit_content', '_bx_tasks_grid_action_title_adm_audit_content', 'search', 1, 0, 4),
('bx_tasks_administration', 'single', 'clear_reports', '_bx_tasks_grid_action_title_adm_clear_reports', 'eraser', 1, 0, 5),

('bx_tasks_common', 'bulk', 'delete', '_bx_tasks_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_tasks_common', 'single', 'edit', '_bx_tasks_grid_action_title_adm_edit', 'pencil-alt', 1, 0, 1),
('bx_tasks_common', 'single', 'delete', '_bx_tasks_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_tasks_common', 'single', 'settings', '_bx_tasks_grid_action_title_adm_more_actions', 'cog', 1, 0, 3);


DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_tasks_time', 'bx_tasks_time_context_administration', 'bx_tasks_time_context_common');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_time_context_administration', 'Sql', 'SELECT `ttt`.*, `tt`.`title` FROM `bx_tasks_time_track` AS `ttt` INNER JOIN `bx_tasks_tasks` AS `tt` ON `ttt`.`object_id`=`tt`.`id` WHERE 1 ', 'bx_tasks_time_track', 'id', 'value_date', '', '', 50, NULL, 'start', '', 'ttt`.`text,tt`.`title,tt`.`text', '', 'like', 'date', '', 192, 'BxTasksGridTimeContextAdministration', 'modules/boonex/tasks/classes/BxTasksGridTimeContextAdministration.php'),
('bx_tasks_time_context_common', 'Sql', 'SELECT `ttt`.*, `tt`.`title` FROM `bx_tasks_time_track` AS `ttt` INNER JOIN `bx_tasks_tasks` AS `tt` ON `ttt`.`object_id`=`tt`.`id` WHERE 1 ', 'bx_tasks_time_track', 'id', 'value_date', '', '', 50, NULL, 'start', '', 'ttt`.`text,tt`.`title,tt`.`text', '', 'like', 'date', '', 2147483647, 'BxTasksGridTimeContextCommon', 'modules/boonex/tasks/classes/BxTasksGridTimeContextCommon.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_tasks_time', 'bx_tasks_time_context_administration', 'bx_tasks_time_context_common');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_tasks_time_context_administration', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_tasks_time_context_administration', 'author_id', '_bx_tasks_grid_column_title_tm_author_id', '28%', 0, 0, '', 2),
('bx_tasks_time_context_administration', 'object_id', '_bx_tasks_grid_column_title_tm_object_id', '30%', 0, 0, '', 3),
('bx_tasks_time_context_administration', 'text', '_bx_tasks_grid_column_title_tm_text', '15%', 0, 16, '', 4),
('bx_tasks_time_context_administration', 'value', '_bx_tasks_grid_column_title_tm_value', '10%', 0, 0, '', 5),
('bx_tasks_time_context_administration', 'value_date', '_bx_tasks_grid_column_title_tm_value_date', '15%', 0, 0, '', 6),

('bx_tasks_time_context_common', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_tasks_time_context_common', 'object_id', '_bx_tasks_grid_column_title_tm_object_id', '30%', 0, 0, '', 2),
('bx_tasks_time_context_common', 'text', '_bx_tasks_grid_column_title_tm_text', '25%', 0, 32, '', 3),
('bx_tasks_time_context_common', 'value', '_bx_tasks_grid_column_title_tm_value', '10%', 0, 0, '', 4),
('bx_tasks_time_context_common', 'value_date', '_bx_tasks_grid_column_title_tm_value_date', '15%', 0, 0, '', 5),
('bx_tasks_time_context_common', 'actions', '', '18%', 0, '', '', 6);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_tasks_time', 'bx_tasks_time_context_administration', 'bx_tasks_time_context_common');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_tasks_time_context_administration', 'bulk', 'calculate', '_bx_tasks_grid_action_title_tm_calculate', '', 0, 0, 1),
('bx_tasks_time_context_administration', 'bulk', 'delete', '_bx_tasks_grid_action_title_tm_delete', '', 0, 1, 2),

('bx_tasks_time_context_common', 'independent', 'add', '_bx_tasks_grid_action_title_tm_add', '', 0, 0, 1),
('bx_tasks_time_context_common', 'bulk', 'calculate', '_bx_tasks_grid_action_title_tm_calculate', '', 0, 0, 1),
('bx_tasks_time_context_common', 'bulk', 'delete', '_bx_tasks_grid_action_title_tm_delete', '', 0, 1, 2),
('bx_tasks_time_context_common', 'single', 'edit', '_bx_tasks_grid_action_title_tm_edit', 'pencil-alt', 1, 0, 1),
('bx_tasks_time_context_common', 'single', 'delete', '_bx_tasks_grid_action_title_tm_delete', 'remove', 1, 1, 2);


DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_tasks_time_administration', 'bx_tasks_time_common');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_time_administration', 'Sql', 'SELECT `ttt`.*, `tt`.`title`, `tp`.`id` AS `context_id`, `tp`.`type` AS `context_module` FROM `bx_tasks_time_track` AS `ttt` INNER JOIN `bx_tasks_tasks` AS `tt` ON `ttt`.`object_id`=`tt`.`id` INNER JOIN `sys_profiles` AS `tp` ON ABS(`tt`.`allow_view_to`)=`tp`.`id` WHERE 1 ', 'bx_tasks_time_track', 'id', 'value_date', '', '', 100, NULL, 'start', '', 'ttt`.`text,tt`.`title,tt`.`text', '', 'like', 'date', '', 192, 'BxTasksGridTimeAdministration', 'modules/boonex/tasks/classes/BxTasksGridTimeAdministration.php'),
('bx_tasks_time_common', 'Sql', 'SELECT `ttt`.*, `tt`.`title`, `tp`.`id` AS `context_id`, `tp`.`type` AS `context_module` FROM `bx_tasks_time_track` AS `ttt` INNER JOIN `bx_tasks_tasks` AS `tt` ON `ttt`.`object_id`=`tt`.`id` INNER JOIN `sys_profiles` AS `tp` ON ABS(`tt`.`allow_view_to`)=`tp`.`id` WHERE 1 ', 'bx_tasks_time_track', 'id', 'value_date', '', '', 100, NULL, 'start', '', 'ttt`.`text,tt`.`title,tt`.`text', '', 'like', 'date', '', 2147483647, 'BxTasksGridTimeCommon', 'modules/boonex/tasks/classes/BxTasksGridTimeCommon.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_tasks_time_administration', 'bx_tasks_time_common');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_tasks_time_administration', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_tasks_time_administration', 'author_id', '_bx_tasks_grid_column_title_tm_author_id', '18%', 0, 0, '', 2),
('bx_tasks_time_administration', 'context_module', '_bx_tasks_grid_column_title_tm_context_module', '10%', 0, 0, '', 3),
('bx_tasks_time_administration', 'context_id', '_bx_tasks_grid_column_title_tm_context_id', '15%', 0, 0, '', 4),
('bx_tasks_time_administration', 'object_id', '_bx_tasks_grid_column_title_tm_object_id', '15%', 0, 0, '', 5),
('bx_tasks_time_administration', 'text', '_bx_tasks_grid_column_title_tm_text', '15%', 0, 16, '', 6),
('bx_tasks_time_administration', 'value', '_bx_tasks_grid_column_title_tm_value', '10%', 0, 0, '', 7),
('bx_tasks_time_administration', 'value_date', '_bx_tasks_grid_column_title_tm_value_date', '15%', 0, 0, '', 8),

('bx_tasks_time_common', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_tasks_time_common', 'context_module', '_bx_tasks_grid_column_title_tm_context_module', '10%', 0, 0, '', 2),
('bx_tasks_time_common', 'context_id', '_bx_tasks_grid_column_title_tm_context_id', '20%', 0, 0, '', 3),
('bx_tasks_time_common', 'object_id', '_bx_tasks_grid_column_title_tm_object_id', '23%', 0, 0, '', 4),
('bx_tasks_time_common', 'text', '_bx_tasks_grid_column_title_tm_text', '20%', 0, 32, '', 5),
('bx_tasks_time_common', 'value', '_bx_tasks_grid_column_title_tm_value', '10%', 0, 0, '', 6),
('bx_tasks_time_common', 'value_date', '_bx_tasks_grid_column_title_tm_value_date', '15%', 0, 0, '', 7);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_tasks_time_administration', 'bx_tasks_time_common');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_tasks_time_administration', 'bulk', 'calculate', '_bx_tasks_grid_action_title_tm_calculate', '', 0, 0, 1),
('bx_tasks_time_administration', 'bulk', 'delete', '_bx_tasks_grid_action_title_tm_delete', '', 0, 1, 2),

('bx_tasks_time_common', 'bulk', 'calculate', '_bx_tasks_grid_action_title_tm_calculate', '', 0, 0, 1),
('bx_tasks_time_common', 'bulk', 'delete', '_bx_tasks_grid_action_title_tm_delete', '', 0, 1, 2);
