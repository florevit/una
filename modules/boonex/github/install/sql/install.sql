SET @sName = 'bx_github';


-- TABLE: settings
CREATE TABLE IF NOT EXISTS `bx_github_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL DEFAULT 0,
  `pat` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile_id` (`profile_id`)
);


-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_github_form_settings', @sName, '_bx_github_form_settings', '', '', 'do_submit', 'bx_github_settings', 'id', '', '', '', 0, 1, 'BxGitHubFormSettings', 'modules/boonex/github/classes/BxGitHubFormSettings.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_github_form_settings_edit', @sName, 'bx_github_form_settings', '_bx_github_form_settings_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_github_form_settings', @sName, 'profile_id', '0', '', 0, 'hidden', '_bx_github_form_settings_input_sys_profile_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_github_form_settings', @sName, 'pat', '', '', 0, 'text', '_bx_github_form_settings_input_sys_pat', '_bx_github_form_settings_input_pat', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_github_form_settings', @sName, 'do_submit', '_bx_github_form_settings_input_do_submit', '', 0, 'submit', '_bx_github_form_settings_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_github_form_settings_edit', 'profile_id', 2147483647, 1, 1),
('bx_github_form_settings_edit', 'username', 2147483647, 1, 2),
('bx_github_form_settings_edit', 'pat', 2147483647, 1, 3),
('bx_github_form_settings_edit', 'do_submit', 2147483647, 1, 4);


-- LOGS
INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_github_api', @sName, 'Auto', '_bx_github_log_api', 1, '', '');


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_github', '_bx_github', 'bx_github@modules/boonex/github/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_github', '', 'bx_github@modules/boonex/github/|std-icon.svg', '_bx_github', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
