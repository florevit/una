SET @sName = 'bx_developer';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_developer_bp_block' AND `name` IN ('description', 'icon', 'content_empty', 'help', 'cache_lifetime');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_developer_bp_block', @sName, 'description', '', '', 0, 'text', '_bx_dev_bp_txt_sys_block_description', '_bx_dev_bp_txt_block_description', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_bp_block', @sName, 'icon', '', '', 0, 'textarea', '_bx_dev_bp_txt_sys_block_icon', '_bx_dev_bp_txt_block_icon', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_bp_block', @sName, 'content_empty', '', '', 0, 'text', '_bx_dev_bp_txt_sys_block_content_empty', '_bx_dev_bp_txt_block_content_empty', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_bp_block', @sName, 'help', '', '', 0, 'text', '_bx_dev_bp_txt_sys_block_help', '_bx_dev_bp_txt_block_help', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_developer_bp_block', @sName, 'cache_lifetime', '', '', 0, 'text', '_bx_dev_bp_txt_sys_block_cache_lifetime', '_bx_dev_bp_txt_block_cache_lifetime', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_developer_bp_block_edit' AND `input_name` IN ('description', 'icon', 'content_empty', 'help', 'cache_lifetime');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_developer_bp_block_edit', 'description', 2147483647, 1, 5),
('bx_developer_bp_block_edit', 'icon', 2147483647, 1, 5),
('bx_developer_bp_block_edit', 'content_empty', 2147483647, 1, 13),
('bx_developer_bp_block_edit', 'help', 2147483647, 1, 13),
('bx_developer_bp_block_edit', 'cache_lifetime', 2147483647, 1, 13);
