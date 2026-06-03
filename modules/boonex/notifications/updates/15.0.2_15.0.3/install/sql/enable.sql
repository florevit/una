SET @sName = 'bx_notifications';


-- SETTINGS
UPDATE `sys_options` SET `info`='_bx_ntfs_option_delivery_timeout_inf' WHERE `name`='bx_notifications_delivery_timeout';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_notifications_queue_add_threshold', 'bx_notifications_queue_add_limit');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `info`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_queue_add_threshold', '0', @iCategId, '_bx_ntfs_option_queue_add_threshold', '_bx_ntfs_option_queue_add_threshold_inf', 'digit', '', '', '', '', 21),
('bx_notifications_queue_add_limit', '200', @iCategId, '_bx_ntfs_option_queue_add_limit', '_bx_ntfs_option_queue_add_limit_inf', 'digit', '', '', '', '', 22);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_notifications_etemplates';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_notifications_etemplates', 'Sql', 'SELECT * FROM `bx_notifications_etemplates` WHERE 1 ', 'bx_notifications_etemplates', 'id', '', 'active', '', 100, NULL, 'start', '', '', 'type,action,subject,content', 'auto', '', 2147483647, 'BxNtfsGridEtemplates', 'modules/boonex/notifications/classes/BxNtfsGridEtemplates.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_notifications_etemplates';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_notifications_etemplates', 'switcher', '_bx_ntfs_grid_column_title_ett_active', '10%', 0, '', '', 10),
('bx_notifications_etemplates', 'type', '_bx_ntfs_grid_column_title_ett_type', '15%', 0, '', '', 20),
('bx_notifications_etemplates', 'action', '_bx_ntfs_grid_column_title_ett_action', '15%', 0, '', '', 30),
('bx_notifications_etemplates', 'subject', '_bx_ntfs_grid_column_title_ett_subject', '40%', 0, '', '', 40),
('bx_notifications_etemplates', 'actions', '', '20%', 0, '', '', 50);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_notifications_etemplates';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_notifications_etemplates', 'independent', 'add', '_bx_ntfs_grid_action_title_ett_add', '', 0, 0, 1),
('bx_notifications_etemplates', 'single', 'preview', '_bx_ntfs_grid_action_title_ett_preview', 'eye', 1, 0, 1),
('bx_notifications_etemplates', 'single', 'edit', '_bx_ntfs_grid_action_title_ett_edit', 'pencil-alt', 1, 0, 2),
('bx_notifications_etemplates', 'single', 'delete', '_bx_ntfs_grid_action_title_ett_delete', 'remove', 1, 1, 3);
