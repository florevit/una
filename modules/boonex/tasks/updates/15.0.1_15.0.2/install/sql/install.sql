-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name` IN ('bx_tasks_entry_edit_body', 'bx_tasks_entry_edit_type', 'bx_tasks_entry_edit_priority', 'bx_tasks_entry_edit_estimate', 'bx_tasks_entry_edit_due_date');
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_tasks', 'bx_tasks_entry_edit_body', 'bx_tasks', 0, '_bx_tasks_form_entry_display_edit_body'),
('bx_tasks', 'bx_tasks_entry_edit_type', 'bx_tasks', 0, '_bx_tasks_form_entry_display_edit_type'),
('bx_tasks', 'bx_tasks_entry_edit_priority', 'bx_tasks', 0, '_bx_tasks_form_entry_display_edit_priority'),
('bx_tasks', 'bx_tasks_entry_edit_estimate', 'bx_tasks', 0, '_bx_tasks_form_entry_display_edit_estimate'),
('bx_tasks', 'bx_tasks_entry_edit_due_date', 'bx_tasks', 0, '_bx_tasks_form_entry_display_edit_due_date');

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_tasks_entry_edit_body', 'bx_tasks_entry_edit_type', 'bx_tasks_entry_edit_priority', 'bx_tasks_entry_edit_estimate', 'bx_tasks_entry_edit_due_date');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_tasks_entry_edit_body', 'title', 2147483647, 1, 1),
('bx_tasks_entry_edit_body', 'text', 2147483647, 1, 2),
('bx_tasks_entry_edit_body', 'do_submit', 2147483647, 1, 3),

('bx_tasks_entry_edit_type', 'type', 2147483647, 1, 1),
('bx_tasks_entry_edit_type', 'controls_edit_popup', 2147483647, 1, 2),
('bx_tasks_entry_edit_type', 'do_submit', 2147483647, 1, 3),
('bx_tasks_entry_edit_type', 'do_cancel', 2147483647, 1, 4),

('bx_tasks_entry_edit_priority', 'priority', 2147483647, 1, 1),
('bx_tasks_entry_edit_priority', 'controls_edit_popup', 2147483647, 1, 2),
('bx_tasks_entry_edit_priority', 'do_submit', 2147483647, 1, 3),
('bx_tasks_entry_edit_priority', 'do_cancel', 2147483647, 1, 4),

('bx_tasks_entry_edit_estimate', 'estimate', 2147483647, 1, 1),
('bx_tasks_entry_edit_estimate', 'controls_edit_popup', 2147483647, 1, 2),
('bx_tasks_entry_edit_estimate', 'do_submit', 2147483647, 1, 3),
('bx_tasks_entry_edit_estimate', 'do_cancel', 2147483647, 1, 4),

('bx_tasks_entry_edit_due_date', 'due_date', 192, 1, 1),
('bx_tasks_entry_edit_due_date', 'controls_edit_popup', 2147483647, 1, 2),
('bx_tasks_entry_edit_due_date', 'do_submit', 2147483647, 1, 3),
('bx_tasks_entry_edit_due_date', 'do_cancel', 2147483647, 1, 4);

UPDATE `sys_objects_form` SET `override_class_name`='BxTasksFormTime', `override_class_file`='modules/boonex/tasks/classes/BxTasksFormTime.php' WHERE `object`='bx_tasks_time';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_tasks_time' AND `name` IN ('value', 'value_h', 'value_div', 'value_m');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_tasks_time', 'bx_tasks', 'value', '', 'value_h,value_div,value_m', 0, 'input_set', '_bx_tasks_form_time_input_sys_value', '_bx_tasks_form_time_input_value', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks_time', 'bx_tasks', 'value_h', '', '', 0, 'text', '_bx_tasks_form_time_input_sys_value_h', '', '', 0, 0, 0, 'a:1:{s:11:"placeholder";s:45:"_bx_tasks_form_time_input_value_h_placeholder";}', '', '', '', '', '', '', '', 1, 0),
('bx_tasks_time', 'bx_tasks', 'value_div', '_bx_tasks_form_time_input_value_div', '', 0, 'value', '_bx_tasks_form_time_input_sys_value_div', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks_time', 'bx_tasks', 'value_m', '', '', 0, 'text', '_bx_tasks_form_time_input_sys_value_m', '', '', 0, 0, 0, 'a:1:{s:11:"placeholder";s:45:"_bx_tasks_form_time_input_value_m_placeholder";}', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_tasks_time_add' AND `input_name` IN ('value', 'value_h', 'value_div', 'value_m');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_tasks_time_add', 'value', 2147483647, 1, 4),
('bx_tasks_time_add', 'value_h', 2147483647, 1, 4),
('bx_tasks_time_add', 'value_div', 2147483647, 1, 4),
('bx_tasks_time_add', 'value_m', 2147483647, 1, 4);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_tasks_time_edit' AND `input_name` IN ('value', 'value_h', 'value_div', 'value_m');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_tasks_time_edit', 'value', 2147483647, 1, 1),
('bx_tasks_time_edit', 'value_h', 2147483647, 1, 1),
('bx_tasks_time_edit', 'value_div', 2147483647, 1, 1),
('bx_tasks_time_edit', 'value_m', 2147483647, 1, 1);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `PerView`=50, `PerViewReplies`=10, `ClassName`='BxTasksCmts', `ClassFile`='modules/boonex/tasks/classes/BxTasksCmts.php' WHERE `Name`='bx_tasks';
