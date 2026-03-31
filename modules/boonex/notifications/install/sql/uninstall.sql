SET @sName = 'bx_notifications';


DROP TABLE IF EXISTS `bx_notifications_events`, `bx_notifications_events2users`, `bx_notifications_read`;
DROP TABLE IF EXISTS `bx_notifications_handlers`, `bx_notifications_settings`, `bx_notifications_settings2users`;
DROP TABLE IF EXISTS `bx_notifications_queue`, `bx_notifications_etemplates`;


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_notifications_%';


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = @sName;
