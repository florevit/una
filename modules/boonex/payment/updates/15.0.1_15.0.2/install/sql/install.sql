SET @sName = 'bx_payment';


-- TABLES
ALTER TABLE `bx_payment_subscriptions_deleted` MODIFY `reason` varchar(255) NOT NULL default '';

UPDATE `bx_payment_providers_options` SET `value`='on', `extended`='2' WHERE `name`='strp_v3_active';
UPDATE `bx_payment_providers_options` SET `value`='', `extended`='1' WHERE `name`='strp_v3_hidden';
UPDATE `bx_payment_providers_options` SET `value`='1', `extended`='2' WHERE `name`='strp_v3_mode';
UPDATE `bx_payment_providers_options` SET `value`='', `extended`='1' WHERE `name`='strp_v3_test_pub_key';
UPDATE `bx_payment_providers_options` SET `value`='', `extended`='1' WHERE `name`='strp_v3_test_sec_key';
UPDATE `bx_payment_providers_options` SET `value`='', `extended`='1' WHERE `name`='strp_v3_check_amount';
UPDATE `bx_payment_providers_options` SET `value`='on', `extended`='1' WHERE `name`='strp_v3_ssl';
UPDATE `bx_payment_providers_options` SET `value`='', `extended`='1' WHERE `name`='strp_v3_expiration_email';

UPDATE `bx_payment_providers_options` SET `value`='', `extended`='1' WHERE `name`='strp_cnnt_test_account_id';


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `ttp`.`id` AS `id`, `ttp`.`client_id` AS `client_id`, `ttp`.`seller_id` AS `seller_id`, `ttp`.`items` AS `items`, `ts`.`date_add` AS `date_add`, `ts`.`date_next` AS `date_next`, `ts`.`status` AS `status`, `ts`.`reason` AS `reason` FROM (SELECT `id`, `pending_id`, `customer_id`, `subscription_id`, `date_add`, `date_next`, `status`, '''' AS `reason` FROM `bx_payment_subscriptions` UNION SELECT `id`, `pending_id`, `customer_id`, `subscription_id`, `date_add`, `date_next`, ''canceled'' AS `status`, `reason` FROM `bx_payment_subscriptions_deleted`) AS `ts` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `ts`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ' WHERE `object`='bx_payment_grid_sbs_list_my';
UPDATE `sys_objects_grid` SET `source`='SELECT `ttp`.`id` AS `id`, `ttp`.`client_id` AS `client_id`, `tac`.`email` AS `client_email`, `ttp`.`seller_id` AS `seller_id`, `ttp`.`items` AS `items`, `ts`.`date_add` AS `date_add`, `ts`.`date_next` AS `date_next`, `ts`.`status` AS `status`, `ts`.`reason` AS `reason` FROM (SELECT `id`, `pending_id`, `customer_id`, `subscription_id`, `date_add`, `date_next`, `status`, '''' AS `reason` FROM `bx_payment_subscriptions` UNION SELECT `id`, `pending_id`, `customer_id`, `subscription_id`, `date_add`, `date_next`, ''canceled'' AS `status`, `reason` FROM `bx_payment_subscriptions_deleted`) AS `ts` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `ts`.`pending_id`=`ttp`.`id` LEFT JOIN `sys_profiles` AS `tpc` ON `ttp`.`client_id`=`tpc`.`id` LEFT JOIN `sys_accounts` AS `tac` ON `tpc`.`account_id`=`tac`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ' WHERE `object`='bx_payment_grid_sbs_list_all';
UPDATE `sys_objects_grid` SET `source`='SELECT `tt`.`id` AS `id`, `ttp`.`client_id` AS `client_id`, `tt`.`seller_id` AS `seller_id`, `tt`.`module_id` AS `module_id`, `tt`.`item_id` AS `item_id`, `tt`.`item_count` AS `item_count`, `tt`.`amount` AS `amount`, `tt`.`currency` AS `currency`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ' WHERE `object`='bx_payment_grid_sbs_history';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_sbs_list_my' AND `name` IN ('customer_id', 'subscription_id', 'provider', 'items');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_sbs_list_my', 'items', '_bx_payment_grid_column_title_sbs_items', '30%', 0, '', '', 3);

DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_sbs_list_all' AND `name` IN ('customer_id', 'subscription_id', 'provider', 'items');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_sbs_list_all', 'items', '_bx_payment_grid_column_title_sbs_items', '15%', 0, '', '', 4);

DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_sbs_history' AND `name` IN ('transaction', 'license', 'item_id');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_sbs_history', 'item_id', '_bx_payment_grid_column_title_sbs_item_id', '44%', 0, '', '', 2);


-- PRE VALUES
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_payment_reasons_cancel';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`, `extendable`) VALUES
('bx_payment_reasons_cancel', '_bx_payment_pre_lists_reasons_cancel', 'bx_payment', '0', '1');

DELETE FROM `sys_form_pre_values` WHERE `key`='bx_payment_reasons_cancel';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_payment_reasons_cancel', 'rc_0', 7, '_bx_payment_pre_values_rc_0', ''),
('bx_payment_reasons_cancel', 'rc_1', 1, '_bx_payment_pre_values_rc_1', ''),
('bx_payment_reasons_cancel', 'rc_2', 2, '_bx_payment_pre_values_rc_2', ''),
('bx_payment_reasons_cancel', 'rc_3', 3, '_bx_payment_pre_values_rc_3', ''),
('bx_payment_reasons_cancel', 'rc_4', 4, '_bx_payment_pre_values_rc_4', ''),
('bx_payment_reasons_cancel', 'rc_5', 5, '_bx_payment_pre_values_rc_5', ''),
('bx_payment_reasons_cancel', 'rc_6', 6, '_bx_payment_pre_values_rc_6', '');
