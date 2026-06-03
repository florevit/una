-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_ads' AND `name`='do_select';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_ads', 'bx_ads', 'do_select', '_bx_ads_form_entry_input_do_select', '', 0, 'button', '_bx_ads_form_entry_input_sys_do_select', '', '', 0, 0, 0, 'a:1:{s:7:"onclick";s:32:"{js_object}.selectCategory(this)";}', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_form_inputs` SET `checker_func`='Length', `checker_params`='a:2:{s:3:"min";i:3;s:3:"max";i:160;}' WHERE `object`='bx_ads' AND `name`='title';

UPDATE `sys_form_display_inputs` SET `input_name`='do_select' WHERE `display_name`='bx_ads_entry_add' AND `input_name`='do_submit';
