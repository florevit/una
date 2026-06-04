SET @sName = 'bx_payment';


-- OPTIONS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_payment_general' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_payment_extended_mode', 'bx_payment_sbs_cancel_survey');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_payment_extended_mode', '', @iCategoryId, '_bx_payment_option_extended_mode', 'checkbox', '', '', '', '', 9),
('bx_payment_sbs_cancel_survey', '', @iCategoryId, '_bx_payment_option_sbs_cancel_survey', 'checkbox', '', '', '', '', 20);


-- MENUS
UPDATE `sys_menu_items` SET `onclick`='{js_object}.cancel(this, {id}, \'{grid}\', 1)' WHERE `set_name`='bx_payment_menu_sbs_actions' AND `name`='sbs-cancel';
