SET @sName = 'bx_notifications';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_notifications_etemplates` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `active` tinyint(4) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `etemplate` (`type`(32), `action`(127))
);


-- FORMS
DELETE FROM `sys_objects_form` WHERE `object`='bx_notifications_etemplate';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_notifications_etemplate', @sName, '_bx_ntfs_form_etemplate', '', '', 'do_submit', 'bx_notifications_etemplates', 'id', '', '', '', 0, 1, '', '');

DELETE FROM `sys_form_displays` WHERE `object`='bx_notifications_etemplate';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_notifications_etemplate_add', @sName, 'bx_notifications_etemplate', '_bx_ntfs_form_etemplate_display_add', 0),
('bx_notifications_etemplate_edit', @sName, 'bx_notifications_etemplate', '_bx_ntfs_form_etemplate_display_edit', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_notifications_etemplate';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_notifications_etemplate', @sName, 'type', '', '', 0, 'select', '_bx_ntfs_form_etemplate_input_sys_type', '_bx_ntfs_form_etemplate_input_type', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ntfs_form_etemplate_input_err_select', 'Xss', '', 0, 0),
('bx_notifications_etemplate', @sName, 'action', '', '', 0, 'select', '_bx_ntfs_form_etemplate_input_sys_action', '_bx_ntfs_form_etemplate_input_action', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ntfs_form_etemplate_input_err_select', 'Xss', '', 0, 0),
('bx_notifications_etemplate', @sName, 'subject', '', '', 0, 'text', '_bx_ntfs_form_etemplate_input_sys_subject', '_bx_ntfs_form_etemplate_input_subject', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ntfs_form_etemplate_input_err_enter', 'Xss', '', 0, 0),
('bx_notifications_etemplate', @sName, 'body', '', '', 0, 'textarea', '_bx_ntfs_form_etemplate_input_sys_body', '_bx_ntfs_form_etemplate_input_body', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ntfs_form_etemplate_input_err_enter', 'XssHtml', '', 0, 0),
('bx_notifications_etemplate', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '_bx_ntfs_form_etemplate_input_controls', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_notifications_etemplate', @sName, 'do_submit', '_bx_ntfs_form_etemplate_input_do_submit', '', 0, 'submit', '_bx_ntfs_form_etemplate_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_notifications_etemplate', @sName, 'do_cancel', '_bx_ntfs_form_etemplate_input_do_cancel', '', 0, 'button', '_bx_ntfs_form_etemplate_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_notifications_etemplate_add', 'bx_notifications_etemplate_edit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_notifications_etemplate_add', 'type', 2147483647, 1, 1),
('bx_notifications_etemplate_add', 'action', 2147483647, 1, 2),
('bx_notifications_etemplate_add', 'subject', 2147483647, 1, 3),
('bx_notifications_etemplate_add', 'body', 2147483647, 1, 4),
('bx_notifications_etemplate_add', 'controls', 2147483647, 1, 5),
('bx_notifications_etemplate_add', 'do_submit', 2147483647, 1, 6),
('bx_notifications_etemplate_add', 'do_cancel', 2147483647, 1, 7),

('bx_notifications_etemplate_edit', 'subject', 2147483647, 1, 1),
('bx_notifications_etemplate_edit', 'body', 2147483647, 1, 2),
('bx_notifications_etemplate_edit', 'controls', 2147483647, 1, 3),
('bx_notifications_etemplate_edit', 'do_submit', 2147483647, 1, 4),
('bx_notifications_etemplate_edit', 'do_cancel', 2147483647, 1, 5);
