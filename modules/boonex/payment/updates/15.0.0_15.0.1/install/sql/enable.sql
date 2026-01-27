SET @sName = 'bx_payment';


-- OPTIONS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_payment_commissions' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_payment_commissions';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_payment_commissions', '', @iCategoryId, '_bx_payment_option_commissions', 'checkbox', '', '', '', '', 0);


-- MENUS
UPDATE `sys_menu_items` SET `icon`='credit-card' WHERE `set_name`='sys_account_settings' AND `name`='payment-details' AND `icon`='credit-card col-gray-dark';
