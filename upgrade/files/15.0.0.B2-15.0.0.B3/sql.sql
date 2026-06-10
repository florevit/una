
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Options

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_alerts_logger', '_adm_stg_cpt_option_sys_alerts_logger', 'on', 'checkbox', '', '', '', '', 310),
(@iCategoryId, 'sys_alerts_stats', '_adm_stg_cpt_option_sys_alerts_stats', '', 'checkbox', '', '', '', '', 312);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '15.0.0-B3' WHERE (`version` = '15.0.0.B2' OR `version` = '15.0.0-B2') AND `name` = 'system';

