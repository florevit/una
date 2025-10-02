SET @sName = 'bx_stripe_connect';

-- TABLES
DROP TABLE IF EXISTS `bx_stripe_connect_accounts`, `bx_stripe_connect_commissions`;

-- FORMS
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN (SELECT `display_name` FROM `sys_form_displays` WHERE `module`=@sName);
DELETE FROM `sys_form_inputs` WHERE `module`=@sName;
DELETE FROM `sys_form_displays` WHERE `module`=@sName;
DELETE FROM `sys_objects_form` WHERE `module`=@sName;

-- LOGS
DELETE FROM `sys_objects_logs` WHERE `module` = @sName;

-- Studio page and widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE `tp`.`name` = @sName;
