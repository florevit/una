-- TABLES
UPDATE `bx_tasks_lists` SET `context_id`=ABS(`context_id`);

CREATE TABLE IF NOT EXISTS `bx_tasks_contexts` (
  `id` int(11) NOT NULL DEFAULT '0',
  `gh_app_id` int(11) NOT NULL DEFAULT '0',
  `gh_username` varchar(64) NOT NULL DEFAULT '',
  `gh_repository` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_tasks_pre_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `use_color` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `use_multiselect` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

DELETE FROM `bx_tasks_pre_lists` WHERE `name` IN ('type', 'sticker');
INSERT INTO `bx_tasks_pre_lists` (`name`, `title`, `use_color`, `use_multiselect`) VALUES
('type', '_bx_tasks_pre_lists_types', 0, 0),
('sticker', '_bx_tasks_pre_lists_stickers', 1, 1);

CREATE TABLE IF NOT EXISTS `bx_tasks_pre_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context_id` int(11) NOT NULL DEFAULT '0',
  `list` varchar(32) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `color` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `list_value` (`list`, `value`)
);

CREATE TABLE IF NOT EXISTS `bx_tasks_filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context_id` int(11) NOT NULL DEFAULT '0',
  `author` int(11) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `conditions` text NOT NULL,
  `permanent` TINYINT NOT NULL DEFAULT '0',
  `active` TINYINT NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

DELETE FROM `bx_tasks_filters` WHERE `title` LIKE '_bx_tasks_filter_title_by_%';
INSERT INTO `bx_tasks_filters` (`author`, `added`, `title`, `conditions`, `permanent`, `active`) VALUES
('0', '0', '_bx_tasks_filter_title_by_author', '{"where":{"cnd":true,"t":"te","f":"author","v":"{logged_pid}","o":"="}}', 1, 1), 
('0', '0', '_bx_tasks_filter_title_by_author_uncompleted', '{"where":{"grp":true,"o":"AND","cnds":[{"cnd":true,"t":"te","f":"author","v":"{logged_pid}","o":"="},{"cnd":true,"t":"te","f":"completed","v":"0","o":"="}]}}', 1, 1),
('0', '0', '_bx_tasks_filter_title_by_author_completed', '{"where":{"grp":true,"o":"AND","cnds":[{"cnd":true,"t":"te","f":"author","v":"{logged_pid}","o":"="},{"cnd":true,"t":"te","f":"completed","v":"1","o":"="}]}}', 1, 1),
('0', '0', '_bx_tasks_filter_title_by_assignee', '{"join":{"cnd":true,"j":"LEFT","tj":"bx_tasks_assignments","taj":"ta","fj":"content","tam":"te","fm":"id"},"where":{"cnd":true,"t":"ta","f":"initiator","v":"{logged_pid}","o":"="}}', 1, 1),
('0', '0', '_bx_tasks_filter_title_by_assignee_uncompleted', '{"join":{"cnd":true,"j":"LEFT","tj":"bx_tasks_assignments","taj":"ta","fj":"content","tam":"te","fm":"id"},"where":{"grp":true,"o":"AND","cnds":[{"cnd":true,"t":"ta","f":"initiator","v":"{logged_pid}","o":"="},{"cnd":true,"t":"te","f":"completed","v":"0","o":"="}]}}', 1, 1),
('0', '0', '_bx_tasks_filter_title_by_assignee_completed', '{"join":{"cnd":true,"j":"LEFT","tj":"bx_tasks_assignments","taj":"ta","fj":"content","tam":"te","fm":"id"},"where":{"grp":true,"o":"AND","cnds":[{"cnd":true,"t":"ta","f":"initiator","v":"{logged_pid}","o":"="},{"cnd":true,"t":"te","f":"completed","v":"1","o":"="}]}}', 1, 1);

CREATE TABLE IF NOT EXISTS `bx_tasks_timers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `started` int(11) NOT NULL default '0',
  `duration` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `timer` (`content_id`, `profile_id`)
);


-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name`='bx_tasks_entry_edit_tasks_list';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_tasks', 'bx_tasks_entry_edit_tasks_list', 'bx_tasks', 0, '_bx_tasks_form_entry_display_edit_tasks_list');

UPDATE `sys_form_inputs` SET `values`='#!bx_tasks_estimates', `type`='select' WHERE `object`='bx_tasks' AND `name`='estimate';
UPDATE `sys_form_inputs` SET `checker_func`='Length', `checker_params`='a:2:{s:3:"min";i:3;s:3:"max";i:160;}' WHERE `object`='bx_tasks' AND `name`='title';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_tasks' AND `name` IN ('tasks_list', 'stickers', 'gh_issue_url');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_tasks', 'bx_tasks', 'tasks_list', '', '', 0, 'select', '_bx_tasks_form_entry_input_sys_tasks_list', '_bx_tasks_form_entry_input_tasks_list', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_tasks', 'bx_tasks', 'stickers', '', '#!bx_tasks_stickers', 0, 'checkbox_set', '_bx_tasks_form_entry_input_sys_stickers', '_bx_tasks_form_entry_input_stickers', '', 0, 0, 0, '', '', '', '', '', '', 'Set', '', 1, 0),
('bx_tasks', 'bx_tasks', 'gh_issue_url', '', '', 0, 'value', '_bx_tasks_form_entry_input_sys_gh_issue_url', '_bx_tasks_form_entry_input_gh_issue_url', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_tasks_entry_add', 'bx_tasks_entry_edit', 'bx_tasks_entry_view') AND `input_name`='stickers';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_tasks_entry_add', 'stickers', 2147483647, 1, 4),
('bx_tasks_entry_edit', 'stickers', 2147483647, 1, 10),
('bx_tasks_entry_view', 'stickers', 2147483647, 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_tasks_entry_edit_tasks_list';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_tasks_entry_edit_tasks_list', 'tasks_list', 2147483647, 1, 1),
('bx_tasks_entry_edit_tasks_list', 'controls_edit_popup', 2147483647, 1, 2),
('bx_tasks_entry_edit_tasks_list', 'do_submit', 2147483647, 1, 3),
('bx_tasks_entry_edit_tasks_list', 'do_cancel', 2147483647, 1, 4);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_tasks_entry_view' AND `input_name` IN ('tasks_list', 'gh_issue_url');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_tasks_entry_view', 'tasks_list', 2147483647, 1, 0),
('bx_tasks_entry_view', 'gh_issue_url', 2147483647, 1, 9);

UPDATE `sys_form_inputs` SET `checker_func`='Length', `checker_params`='a:2:{s:3:"min";i:3;s:3:"max";i:160;}' WHERE `object`='bx_tasks_list' AND `name`='title';

DELETE FROM `sys_objects_form` WHERE `object`='bx_tasks_context';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_context', 'bx_tasks', '_bx_tasks_form_context', '', '', 'do_submit', 'bx_tasks_contexts', 'id', '', '', '', 0, 1, 'BxTasksFormContext', 'modules/boonex/tasks/classes/BxTasksFormContext.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_tasks_context';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_tasks_context_edit', 'bx_tasks', 'bx_tasks_context', '_bx_tasks_form_display_context_edit', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_tasks_context';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_tasks_context', 'bx_tasks', 'gh_app_id', '', '', 0, 'select', '_bx_tasks_form_context_input_sys_gh_app_id', '_bx_tasks_form_context_input_gh_app_id', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_tasks_context', 'bx_tasks', 'gh_username', '', '', 0, 'text', '_bx_tasks_form_context_input_sys_gh_username', '_bx_tasks_form_context_input_gh_username', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_tasks_context', 'bx_tasks', 'gh_repository', '', '', 0, 'text', '_bx_tasks_form_context_input_sys_gh_repository', '_bx_tasks_form_context_input_gh_repository', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_tasks_context', 'bx_tasks', 'do_submit', '_bx_tasks_form_context_input_do_submit', '', 0, 'submit', '_bx_tasks_form_context_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks_context', 'bx_tasks', 'do_cancel', '_bx_tasks_form_context_input_do_cancel', '', 0, 'button', '_bx_tasks_form_context_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0),
('bx_tasks_context', 'bx_tasks', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '_bx_tasks_form_context_input_sys_controls', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_tasks_context_edit';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_tasks_context_edit', 'gh_username', 2147483647, 1, 1),
('bx_tasks_context_edit', 'gh_repository', 2147483647, 1, 2),
('bx_tasks_context_edit', 'gh_app_id', 2147483647, 1, 3),
('bx_tasks_context_edit', 'do_submit', 2147483647, 1, 4);

UPDATE `sys_form_inputs` SET `info`='' WHERE `object`='bx_tasks_time' AND `name`='value_date';


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_tasks_stickers';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_tasks_stickers', '_bx_tasks_pre_lists_stickers', 'bx_tasks', '1');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_tasks_stickers';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_tasks_stickers', '', 0, '_sys_please_select', ''),
('bx_tasks_stickers', '1', 1, '_bx_tasks_sticker_1', ''),
('bx_tasks_stickers', '2', 2, '_bx_tasks_sticker_2', ''),
('bx_tasks_stickers', '3', 3, '_bx_tasks_sticker_3', ''),
('bx_tasks_stickers', '4', 4, '_bx_tasks_sticker_4', ''),
('bx_tasks_stickers', '5', 5, '_bx_tasks_sticker_5', '');

DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_tasks_estimates';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_tasks_estimates', '_bx_tasks_pre_lists_estimates', 'bx_tasks', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_tasks_estimates';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_tasks_estimates', '', 0, '_sys_please_select', ''),
('bx_tasks_estimates', '2', 1, '_bx_tasks_estimate_2', ''),
('bx_tasks_estimates', '4', 1, '_bx_tasks_estimate_4', ''),
('bx_tasks_estimates', '8', 1, '_bx_tasks_estimate_8', ''),
('bx_tasks_estimates', '16', 1, '_bx_tasks_estimate_16', ''),
('bx_tasks_estimates', '32', 1, '_bx_tasks_estimate_32', ''),
('bx_tasks_estimates', '64', 1, '_bx_tasks_estimate_64', '');
