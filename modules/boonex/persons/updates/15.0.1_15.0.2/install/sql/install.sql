SET @sName = 'bx_persons';


-- FORMS
UPDATE `sys_form_inputs` SET `name`='badge_link_custom', `caption_system`='_bx_persons_form_profile_input_sys_badge_link_custom', `caption`='_bx_persons_form_profile_input_badge_link_custom', `attrs`='a:1:{s:11:"placeholder";s:60:"_bx_persons_form_profile_input_badge_link_custom_placeholder";}', `db_pass`='' WHERE `object`='bx_person' AND `name`='badge_link';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name`='badge_link_select';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`, `rateable`) VALUES 
('bx_person', 'bx_persons', 'badge_link_select', '', '', 0, 'select', '_bx_persons_form_profile_input_sys_badge_link_select', '_bx_persons_form_profile_input_badge_link_select', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0, '');

UPDATE `sys_form_display_inputs` SET `input_name`='badge_link_custom', `active`='0' WHERE `display_name`='bx_person_edit_badge' AND `input_name`='badge_link';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_edit_badge' AND `input_name`='badge_link_select';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_edit_badge', 'badge_link_select', 2147483647, 1, 1);
