-- FORMS
UPDATE `sys_form_inputs` SET `checker_func`='Length', `checker_params`='a:2:{s:3:"min";i:3;s:3:"max";i:160;}' WHERE `object`='bx_videos' AND `name`='title';

UPDATE `sys_form_display_inputs` SET `active`='0' WHERE `display_name` IN ('bx_videos_entry_add', 'bx_videos_entry_edit') AND `input_name`='location';
