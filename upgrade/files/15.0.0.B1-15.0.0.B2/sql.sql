
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Update Robot account

UPDATE `sys_accounts` SET `email_confirmed` = 1 WHERE `name` = 'Robot' AND `email` = '';

-- Push objects

DELETE FROM `sys_objects_push` WHERE `object` = 'sys_wonderpush';
INSERT INTO `sys_objects_push` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_wonderpush', 'WonderPush', 'BxTemplPushWonderPush', '');

-- Email templates

DELETE FROM `sys_email_templates` WHERE `Module` = 'system' AND `Name` = 't_Welcome';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_system_welcome', 't_Welcome', '_sys_et_txt_subject_welcome', '_sys_et_txt_body_welcome');

-- Options

DELETE FROM `sys_options` WHERE `name` = 'sys_account_activation_letter';

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_std_pgt_etemplates_editor', '_adm_stg_cpt_option_sys_std_pgt_etemplates_editor', 'on', 'checkbox', '', '', '', '', 290),
(@iCategoryId, 'sys_bg_jobs_process_per_run', '_adm_stg_cpt_option_sys_bg_jobs_process_per_run', '5', 'digit', '', '', '', '', 300),
(@iCategoryId, 'sys_bg_jobs_workers_limit', '_adm_stg_cpt_option_sys_bg_jobs_workers_limit', '3', 'digit', '', '', '', '', 304),
(@iCategoryId, 'sys_bg_jobs_cleanup_timeout', '_adm_stg_cpt_option_sys_bg_jobs_cleanup_timeout', '30', 'digit', '', '', '', '', 306);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `info`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_per_page_recommendations', '_adm_stg_cpt_option_sys_per_page_recommendations', '_adm_stg_inf_option_sys_per_page_recommendations', '12', 'digit', '', '', '', 26),
(@iCategoryId, 'sys_per_page_recommendations_showcase', '_adm_stg_cpt_option_sys_per_page_recommendations_showcase', '_adm_stg_inf_option_sys_per_page_recommendations_showcase', '8', 'digit', '', '', '', 27);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'general');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `info`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_embed_lifetime', '_adm_stg_cpt_option_sys_embed_lifetime', '', '30', 'digit', '', '', '', 73);

UPDATE `sys_options` SET `ORDER` = 70 WHERE `name` = 'sys_embed_default' AND `ORDER` != 70;
UPDATE `sys_options` SET `info` = '' WHERE `name` = 'sys_embed_microlink_key' AND `info` != '';

UPDATE `sys_options` SET `extra`='File,Memcache,Memcached,APC,XCache,Redis' WHERE `name`='sys_content_cache_engine' AND `extra`='File,Memcache,APC,XCache,Redis';
UPDATE `sys_options` SET `extra`='File,Memcache,Memcached,APC,XCache,Redis' WHERE `name`='sys_db_cache_engine' AND `extra`='File,Memcache,APC,XCache,Redis';
UPDATE `sys_options` SET `extra`='File,Memcache,Memcached,APC,XCache,Redis' WHERE `name`='sys_page_cache_engine' AND `extra`='File,Memcache,APC,XCache,Redis';
UPDATE `sys_options` SET `extra`='File,Memcache,Memcached,APC,XCache,Redis' WHERE `name`='sys_pb_cache_engine' AND `extra`='File,Memcache,APC,XCache,Redis';
UPDATE `sys_options` SET `extra`='FileHtml,Memcache,Memcached,APC,XCache,Redis' WHERE `name`='sys_template_cache_engine' AND `extra`='FileHtml,Memcache,APC,XCache,Redis';

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_account_welcome_letter', '_adm_stg_cpt_option_sys_account_welcome_letter', '', 'checkbox', '', '', '', 12);

UPDATE `sys_options` SET `order`='11' WHERE `name`='sys_account_confirmation_type';

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'notifications_push');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_push_wonderpush_app_id', '_adm_stg_cpt_option_sys_push_wonderpush_app_id', '', 'digit', '', '', '', 30),
(@iCategoryId, 'sys_push_wonderpush_access_token', '_adm_stg_cpt_option_sys_push_wonderpush_access_token', '', 'digit', '', '', '', 32),
(@iCategoryId, 'sys_push_wonderpush_web_key', '_adm_stg_cpt_option_sys_push_wonderpush_web_key', '', 'digit', '', '', '', 34);


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'api_layout');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_api_root_page_guest', '_adm_stg_cpt_option_sys_api_root_page_guest', 'home', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:31:"get_options_api_root_page_guest";s:5:"class";s:13:"TemplServices";}', '', '', 1),
(@iCategoryId, 'sys_api_root_page_member', '_adm_stg_cpt_option_sys_api_root_page_member', 'splash', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:32:"get_options_api_root_page_member";s:5:"class";s:13:"TemplServices";}', '', '', 3);

UPDATE `sys_options` SET `ORDER`=5 WHERE `name`='sys_api_menu_top' AND `ORDER`<>5;
UPDATE `sys_options` SET `ORDER`=6 WHERE `name`='sys_api_context_switcher' AND `ORDER`<>6;
UPDATE `sys_options` SET `ORDER`=7 WHERE `name`='sys_api_context_connection' AND `ORDER`<>7;


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'agents_general');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `info`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_agents_inspector_key', '_adm_stg_cpt_option_sys_agents_inspector_key', '_adm_stg_cpt_option_sys_agents_inspector_key_info', '', 'digit', '', '', '', 0);

DELETE FROM `sys_options` WHERE `name` IN ('sys_agents_api_key','sys_agents_model','sys_agents_profile');

-- ACL

UPDATE `sys_acl_levels` SET `Icon`='user' WHERE `ID`=1 AND `Icon`='user bx-def-font-color';
UPDATE `sys_acl_levels` SET `Icon`='user' WHERE `ID`=2 AND `Icon`='user col-green1';
UPDATE `sys_acl_levels` SET `Icon`='user' WHERE `ID`=3 AND `Icon`='user col-red1';
UPDATE `sys_acl_levels` SET `Icon`='user' WHERE `ID`=4 AND `Icon`='user bx-def-font-color';
UPDATE `sys_acl_levels` SET `Icon`='user' WHERE `ID`=5 AND `Icon`='user bx-def-font-color';
UPDATE `sys_acl_levels` SET `Icon`='user' WHERE `ID`=6 AND `Icon`='user bx-def-font-color';
UPDATE `sys_acl_levels` SET `Icon`='user-secret' WHERE `ID`=7 AND `Icon`='user-secret col-blue3';
UPDATE `sys_acl_levels` SET `Icon`='user-secret' WHERE `ID`=8 AND `Icon`='user-secret col-blue3';
UPDATE `sys_acl_levels` SET `Icon`='user' WHERE `ID`=9 AND `Icon`='user col-red3';

UPDATE `sys_objects_grid` SET `source`='SELECT `sys_acl_actions`.*, ''0'' AS `Active` FROM `sys_acl_actions` WHERE 1 ' WHERE `object`='sys_studio_acl_actions';

 -- Alerts

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'sys_agents' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`) VALUES
('sys_agents', 'BxDolAiAlertResponse', 'inc/classes/BxDolAiAlertResponse.php');
SET @iIdHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_messenger', 'got_jot', @iIdHandler);

CREATE TABLE IF NOT EXISTS `sys_alerts_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `unit` varchar(128) NOT NULL DEFAULT '',
  `action` varchar(32) NOT NULL DEFAULT 'none',
  `object` varchar(128) NOT NULL,
  `sender` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `extra` text NOT NULL,
  `extra_refs` text NOT NULL,
  `ts` int(10) UNSIGNED NOT NULL,
  `counter_total` int(10) UNSIGNED NOT NULL,
  `counter_24h` int(10) UNSIGNED NOT NULL,
  `counter_per_request` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unit_action` (`unit`,`action`)
);

CREATE TABLE IF NOT EXISTS `sys_alerts_desc` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `unit` varchar(128) NOT NULL DEFAULT '',
  `action` varchar(32) NOT NULL DEFAULT 'none',
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unit_action` (`unit`,`action`)
);

-- Background jobs

DROP TABLE IF EXISTS `sys_background_jobs`;

CREATE TABLE IF NOT EXISTS `sys_background_jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL default '',
  `claim_token` varchar(64) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `reserved_at` int(11) unsigned NOT NULL default '0',
  `available_at` int(11) unsigned NOT NULL default '0',
  `priority` tinyint(4) unsigned NOT NULL default '0',
  `service_call` text NOT NULL default '',
  `attempts` tinyint(4) NOT NULL DEFAULT '3',
  `error` varchar(255) NOT NULL default '',
  `status` varchar(16) NOT NULL default 'pending',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `claim_token` (`claim_token`)
);

-- Cron

DELETE FROM `sys_cron_jobs` WHERE `name` = 'sys_agents_vector_store';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('sys_agents_vector_store', '* * * * *', 'BxDolCronAgentsVectorStore', 'inc/classes/BxDolCronAgentsVectorStore.php', '');

-- AGENTS GRIDS

-- GRID: Agents Models
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_agents_models';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_models', 'Sql', 'SELECT * FROM `sys_agents_models` WHERE 1 ', 'sys_agents_models', 'id', 'changed', 'active', '', 20, NULL, 'start', '', 'type,model,title,capabilities', '', 'like', 'type,model,title,capabilities', '', 128, 1, 1, 'BxTemplStudioAgentsModels', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_agents_models';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_models', 'checkbox', '', '2%', 0, 0, '', '', 10),
('sys_studio_agents_models', 'switcher', '_sys_agents_models_txt_active', '8%', 0, 0, '', '', 20),
('sys_studio_agents_models', 'type', '_sys_agents_automators_txt_type', '10%', 0, 0, '', '', 30),
('sys_studio_agents_models', 'title', '_sys_agents_models_txt_title', '10%', 0, 0, '', '', 40),
('sys_studio_agents_models', 'model', '_sys_agents_models_txt_model', '10%', 0, 0, '', '', 50),
('sys_studio_agents_models', 'capabilities', '_sys_agents_models_txt_capabilities', '10%', 0, 0, '', '', 60),
('sys_studio_agents_models', 'actions', '', '50%', 0, 0, '', '', 70);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_agents_models';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `active`, `order`) VALUES
('sys_studio_agents_models', 'single', 'duplicate', '_Duplicate', 'copy', 1, 0, 1, 1),
('sys_studio_agents_models', 'single', 'edit', '_Edit', 'pencil-alt', 1, 0, 1, 2),
('sys_studio_agents_models', 'single', 'delete', '_Delete', 'remove', 1, 1, 1, 3),
('sys_studio_agents_models', 'bulk', 'delete', '_Delete', '', 0, 1, 1, 1);

UPDATE `sys_grid_fields` SET `translatable` = 1 WHERE `object` = 'sys_studio_agents_assistants_chats' AND `name` = 'description';

-- GRID: Agents Vector Store
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_agents_vector_store';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_vector_store', 'Sql', 'SELECT * FROM `sys_agents_vector_store` WHERE 1 ', 'sys_agents_vector_store', 'id', 'id', 'active', '', 20, NULL, 'start', '', 'title,type', '', 'like', '', '', 128, 1, 1, 'BxTemplStudioAgentsVectorStore', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_agents_vector_store';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_vector_store', 'switcher', '_sys_active', '10%', 0, 0, '', '', 10),
('sys_studio_agents_vector_store', 'title', '_Title', '20%', 0, 0, '', '', 20),
('sys_studio_agents_vector_store', 'type', '_adm_form_txt_fields_type', '10%', 0, 0, '', '', 30),
('sys_studio_agents_vector_store', 'embedding_provider_id', '_sys_agents_vector_store_txt_embedding_provider', '5%', 0, 0, '', '', 40),
('sys_studio_agents_vector_store', 'files_num', '_sys_agents_vector_store_txt_files_num', '5%', 0, 0, '', '', 50),
('sys_studio_agents_vector_store', 'actions', '', '25%', 0, 0, '', '', 60);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_agents_vector_store';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `active`, `order`) VALUES
('sys_studio_agents_vector_store', 'single', 'add_data', '_sys_uploader_simple_attach_one_more_file', 'plus', 1, 0, 1, 10),
('sys_studio_agents_vector_store', 'single', 'edit', '_Edit', 'pencil-alt', 1, 0, 1, 20),
('sys_studio_agents_vector_store', 'single', 'delete', '_Delete', 'remove', 1, 1, 1, 30),
('sys_studio_agents_vector_store', 'single', 'duplicate', '_Duplicate', 'copy', 1, 0, 1, 40);

-- GRID: Agents Vector Store Data
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_agents_vector_store_data';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_vector_store_data', 'Sql', 'SELECT * FROM `sys_agents_vector_store_data` WHERE `vector_store_id` = ''{vector_store_id}'' ', 'sys_agents_vector_store_data', 'id', 'id', '', '', 10, NULL, 'start', '', 'name', '', 'like', '', '', 128, 1, 1, 'BxTemplStudioAgentsVectorStoreData', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_agents_vector_store_data';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_vector_store_data', 'checkbox', '', '2%', 0, 0, '', '', 10),
('sys_studio_agents_vector_store_data', 'name', '_Name', '70%', 0, 0, '', '', 20),
('sys_studio_agents_vector_store_data', 'size', '_sys_agents_assistants_files_txt_size', '18%', 0, 0, '', '', 30),
('sys_studio_agents_vector_store_data', 'status', '_sys_status', '10%', 0, 0, '', '', 40);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_agents_vector_store_data';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `active`, `order`) VALUES
('sys_studio_agents_vector_store_data', 'bulk', 'delete', '_Delete', '', 0, 1, 1, 10);

-- GRID: Agents
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_agents_agents';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_agents', 'Sql', 'SELECT * FROM `sys_agents_agents` WHERE 1 ', 'sys_agents_agents', 'id', 'added', 'active', '', 20, NULL, 'start', '', 'name', '', 'like', '', '', 'BxTemplStudioAgentsAgents', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_agents_agents';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_agents', 'checkbox', '', '2%', 0, 0, '', '', 1),
('sys_studio_agents_agents', 'switcher', '_sys_active', '8%', 0, 0, '', '', 2),
('sys_studio_agents_agents', 'name', '_sys_agents_agents_txt_name', '10%', 0, 0, '', '', 3),
('sys_studio_agents_agents', 'trigger', '_sys_agents_agents_txt_trigger', '8%', 0, 0, '', '', 4),
('sys_studio_agents_agents', 'profile_id', '_sys_agents_agents_txt_profile_id', '10%', 0, 0, '', '', 6),
('sys_studio_agents_agents', 'actions', '', '20%', 0, 0, '', '', 11);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_agents_agents';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_studio_agents_agents', 'bulk', 'delete', '_Delete', '', 0, 1, 1),
('sys_studio_agents_agents', 'single', 'manual', '_Run', 'play', 1, 0, 1),
('sys_studio_agents_agents', 'single', 'logs', '_Logs', 'file-alt', 1, 0, 2),
('sys_studio_agents_agents', 'single', 'edit', '_Edit', 'pencil-alt', 1, 0, 3),
('sys_studio_agents_agents', 'single', 'wipe_chat_history', '_sys_agents_agents_act_wipe_chat_history', 'eraser', 1, 1, 4),
('sys_studio_agents_agents', 'single', 'delete', '_Delete', 'remove', 1, 1, 5),
('sys_studio_agents_agents', 'independent', 'add', '_adm_form_btn_field_add', '', 0, 0, 1);

-- GRID: Agents Logs
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_agents_logs';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_logs', 'Sql', 'SELECT * FROM `sys_logger` WHERE `channel` = ''sys_agents_{agent_id}'' ', 'sys_logger', 'id', 'id', '', '', 10, NULL, 'start', '', 'message,context', '', 'like', '', '', 128, 1, 1, 'BxTemplStudioAgentsLogs', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_agents_logs';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_logs', 'level', '_Level', '5%', 0, 0, '', '', 10),
('sys_studio_agents_logs', 'message', '_sys_agents_helpers_field_message', '15%', 0, 0, '', '', 20),
('sys_studio_agents_logs', 'context', '_Context', '70%', 0, 0, '', '', 30),
('sys_studio_agents_logs', 'created_at', '_Date', '10%', 0, 0, '', '', 40);

-- GRID: Agents Tools
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_agents_tools';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_tools', 'Sql', 'SELECT * FROM `sys_agents_tools` WHERE 1 ', 'sys_agents_tools', 'id', 'id', 'active', '', 20, NULL, 'start', '', 'title,type', '', 'like', '', '', 128, 1, 1, 'BxTemplStudioAgentsTools', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_agents_tools';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_tools', 'switcher', '_sys_active', '10%', 0, 0, '', '', 10),
('sys_studio_agents_tools', 'type', '_adm_form_txt_fields_type', '10%', 0, 0, '', '', 20),
('sys_studio_agents_tools', 'title', '_Title', '40%', 0, 0, '', '', 30),
('sys_studio_agents_tools', 'actions', '', '40%', 0, 0, '', '', 40);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_agents_tools';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `active`, `order`) VALUES
('sys_studio_agents_tools', 'single', 'edit', '_Edit', 'pencil-alt', 1, 0, 1, 20),
('sys_studio_agents_tools', 'single', 'delete', '_Delete', 'remove', 1, 1, 1, 30),
('sys_studio_agents_tools', 'single', 'duplicate', '_Duplicate', 'copy', 1, 0, 1, 40);

-- Pages

UPDATE `sys_objects_page` SET `layout_id`=5 WHERE `layout_id`=12 AND `object` IN ('sys_con_friends','sys_con_friend_requests','sys_con_friend_requested','sys_con_following','sys_con_followers','sys_recom_friends','sys_recom_subscriptions');

-- Agents

DROP TABLE IF EXISTS `sys_agents_models`;

CREATE TABLE IF NOT EXISTS `sys_agents_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(64) NOT NULL,
  `model` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL DEFAULT '',
  `docs` text NOT NULL,
  `key` varchar(255) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `params_user` text DEFAULT NULL,
  `capabilities` enum('chatllm','chatvlm','embeddings') NOT NULL DEFAULT 'chatllm',
  `duplicate` tinyint(4) NOT NULL DEFAULT 1,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `changed` int(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

SET @j = JSON_OBJECT(
    'parameters', JSON_OBJECT()
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('anthropic', 'claude-sonnet-4-6', 'Anthropic', 'https://platform.claude.com/docs/en/about-claude/models/overview', '', CAST(@j AS CHAR), NULL, 'chatvlm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'parameters', JSON_OBJECT(),
    'strict_response', FALSE
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('openai-responses', 'gpt-5-mini', 'OpenAI (Responses)', 'https://developers.openai.com/api/docs/models', '', CAST(@j AS CHAR), NULL, 'chatvlm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'endpoint', 'AZURE_ENDPOINT',
    'version', 'AZURE_API_VERSION'
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('azure-openai', 'gpt-5-mini', 'Azure OpenAI', 'https://ai.azure.com/catalog/publishers/openai', '', CAST(@j AS CHAR), NULL, 'chatvlm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'baseUri', 'https://api.together.xyz/v1',
    'parameters', JSON_OBJECT()
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('openai-like', 'MODEL_NAME_HERE', 'OpenAI Like', 'Use any provider with the same data format like official OpenAI API', '', CAST(@j AS CHAR), NULL, 'chatllm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'url', 'OLLAMA_URL',
    'parameters', JSON_OBJECT()
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('ollama', '', 'Ollama', 'Run AI models locally - https://ollama.com/library', '', CAST(@j AS CHAR), NULL, 'chatllm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'parameters', JSON_OBJECT()
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('gemini', 'gemini-3-flash-preview', 'Gemini', 'https://ai.google.dev/gemini-api/docs/models', '', CAST(@j AS CHAR), NULL, 'chatvlm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'parameters', JSON_OBJECT(),
    'strict_response', FALSE
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('mistral', 'mistral-medium-2508', 'Mistral', 'https://docs.mistral.ai/getting-started/models', '', CAST(@j AS CHAR), NULL, 'chatvlm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'inferenceProvider', 'hf-inference/models',
    'parameters', JSON_OBJECT(
        'max_tokens', 500,
        'temperature', 0.5
    )
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('huggingface', 'mistralai/Mistral-7B-Instruct-v0.3', 'HuggingFace', 'https://huggingface.co/models?pipeline_tag=text-generation', '', CAST(@j AS CHAR), NULL, 'chatllm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'parameters', JSON_OBJECT(),
    'strict_response', FALSE
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('deepseek', 'deepseek-chat', 'Deepseek', 'https://api-docs.deepseek.com/quick_start/pricing', '', CAST(@j AS CHAR), NULL, 'chatllm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'parameters', JSON_OBJECT(),
    'strict_response', FALSE
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('grok', 'grok-4-1-fast-reasoning', 'Grok (X-AI)', 'https://docs.x.ai/developers/models', '', CAST(@j AS CHAR), NULL, 'chatvlm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'client_params', JSON_OBJECT(
        'version', 'latest',
        'region', 'us-east-1',
        'credentials', JSON_OBJECT(
            'key', '{key}',
            'secret', 'AWS_BEDROCK_SECRET'
        )
    ),
    'inferenceConfig', JSON_OBJECT()
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('aws-bedrock', 'google.gemma-3-12b-it', 'AWS Bedrock', 'https://docs.aws.amazon.com/bedrock/latest/userguide/models.html', '', CAST(@j AS CHAR), NULL, 'chatvlm', 0, 0, 0);

INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('cohere', 'command-a-03-2025', 'Cohere', 'https://docs.cohere.com/docs/models', '', '', NULL, 'chatllm', 0, 0, 0);

SET @j = JSON_OBJECT(
    'url', 'http://localhost:11434/api',
    'parameters', JSON_OBJECT()
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('ollama-embeddings', 'all-minilm', 'Ollama', 'With Ollama you can run embedding models locally. Documentation - https://ollama.com/blog/embedding-models', '', CAST(@j AS CHAR), NULL, 'embeddings', 0, 0, 0);

SET @j = JSON_OBJECT(
    'dimensions', 1024
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('voyageai-embeddings', 'voyage-4-large', 'Voyage AI', 'Models - https://docs.voyageai.com/docs/embeddings, pricing - https://docs.voyageai.com/docs/embeddings', '', CAST(@j AS CHAR), NULL, 'embeddings', 0, 0, 0);

SET @j = JSON_OBJECT(
    'dimensions', 1024
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('openai-embeddings', 'text-embedding-3-small', 'OpenAI', 'Models - https://developers.openai.com/api/docs/guides/embeddings#embedding-models', '', CAST(@j AS CHAR), NULL, 'embeddings', 0, 0, 0);

SET @j = JSON_OBJECT(
    'baseUri', 'PRODIDER_URL'
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('openai-like-embeddings', 'MODEL_NAME_HER', 'OpenAI Like Embeddings', 'Use any providers comaptible with OpenAI API format.', '', CAST(@j AS CHAR), NULL, 'embeddings', 0, 0, 0);

SET @j = JSON_OBJECT(
    'client_params', JSON_OBJECT(
        'version', 'latest',
        'region', 'us-east-1',
        'credentials', JSON_OBJECT(
            'key', '{key}',
            'secret', 'AWS_BEDROCK_SECRET'
        )
    )
);
INSERT INTO `sys_agents_models` (`type`, `model`, `title`, `docs`, `key`, `params`, `params_user`, `capabilities`, `duplicate`, `active`, `changed`) VALUES
('aws-bedrock-embeddings', 'amazon.titan-embed-text-v2:0', 'Aws Bedrock', 'https://docs.aws.amazon.com/bedrock/latest/userguide/titan-embedding-models.html', '', CAST(@j AS CHAR), NULL, 'embeddings', 0, 0, 0);

CREATE TABLE IF NOT EXISTS`sys_agents_agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `model_id` int(11) NOT NULL DEFAULT 0,
  `profile_id` int(11) NOT NULL DEFAULT 0,
  `prompt_system` text NOT NULL,
  `prompt_steps` text NOT NULL,
  `prompt_output` text NOT NULL,
  `prompt_tools` text NOT NULL,
  `tools` varchar(255) NOT NULL DEFAULT '',
  `tools_max_run` int(11) NOT NULL DEFAULT 10,
  `chat_history_context` int(11) NOT NULL DEFAULT 50000,
  `vector_store_id` int(11) NOT NULL,
  `trigger` enum('alert','scheduler','webhook','manual','agent','message') NOT NULL DEFAULT 'message',
  `async` tinyint(4) NOT NULL DEFAULT 0,
  `alert` varchar(192) NOT NULL DEFAULT '',
  `scheduler_cron` varchar(64) NOT NULL,
  `webhook_key` varchar(255) NOT NULL,
  `webhook_sample` text NOT NULL,
  `agent_sample` text NOT NULL,
  `message_profile_id` int(11) NOT NULL,
  `added` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `alert` (`alert`),
  KEY `profile_id` (`profile_id`,`message_profile_id`),
  KEY `webhook_key` (`webhook_key`(192))
);

CREATE TABLE IF NOT EXISTS `sys_agents_vector_store_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  
  `vector_store_id` int(11) NOT NULL,
  `type` varchar(128) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `size` int(11) NOT NULL DEFAULT 0,
  `metadata` text NOT NULL DEFAULT '',
  `settings` varchar(255) NOT NULL DEFAULT '',
  `content` longtext NOT NULL DEFAULT '',
  `status` enum('pending', 'processing','ready','error') NOT NULL DEFAULT 'pending',  
  `added` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_vector_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  
  `embedding_provider_id` int(11) NOT NULL,
  `type` varchar(128) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `docs` text DEFAULT NULL,
  `topk` tinyint(4) DEFAULT 4,
  `params` text DEFAULT NULL,
  `params_user` text DEFAULT NULL,
  `duplicate` tinyint(4) NOT NULL DEFAULT 1,
  `changed` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

TRUNCATE TABLE `sys_agents_vector_store`;

-- 1. File
SET @j = JSON_OBJECT(
    'folder', 'vectorstore'
);
INSERT INTO sys_agents_vector_store (type, title, docs, topk, params, duplicate, changed, active)
VALUES ('file', 'File', 'Local low volume embeddings storage', 4, CAST(@j AS CHAR), 0, 0, 0);

-- 2. Pinecone
SET @j = JSON_OBJECT(
    'key', 'PINECONE_API_KEY',
    'indexUrl', 'PINECONE_INDEX_URL'
);
INSERT INTO sys_agents_vector_store (type, title, docs, topk, params, duplicate, changed, active)
VALUES ('pinecone', 'Pinecone', 'https://www.pinecone.io/', 4, CAST(@j AS CHAR), 0, 0, 0);

-- 3. Elasticsearch
SET @j = JSON_OBJECT(
    'client_params', JSON_OBJECT(
        'endpoint', 'ELASTICSEARCH-ENDPOINT',
        'key', 'API-KEY'
    ),
    'index', 'vectorstore'
);
INSERT INTO sys_agents_vector_store (type, title, docs, topk, params, duplicate, changed, active)
VALUES ('elasticsearch', 'Elasticsearch', 'https://www.elastic.co/elasticsearch/vector-database', 4, CAST(@j AS CHAR), 0, 0, 0);

-- 4. Opensearch
SET @j = JSON_OBJECT(
    'client_params', JSON_OBJECT(
        'base_uri', 'http://localhost:9200'
    ),
    'index', 'vectorstore'
);
INSERT INTO sys_agents_vector_store (type, title, docs, topk, params, duplicate, changed, active)
VALUES ('opensearch', 'Opensearch', 'Free open source fork of Elasticsearch - https://opensearch.org/', 4, CAST(@j AS CHAR), 0, 0, 0);

-- 5. Typesense
SET @j = JSON_OBJECT(
    'client_params', JSON_OBJECT(
        'api_key', 'TYPESENSE_API_KEY',
        'nodes', JSON_ARRAY(
            JSON_OBJECT(
                'host', 'TYPESENSE_NODE_HOST',
                'port', 'TYPESENSE_NODE_PORT',
                'protocol', 'TYPESENSE_NODE_PROTOCOL'
            )
        )
    ),
    'collection', 'vectorstore',
    'vectorDimension', 1024
);
INSERT INTO sys_agents_vector_store (type, title, docs, topk, params, duplicate, changed, active)
VALUES ('typesense', 'Typesense', 'https://typesense.org/', 4, CAST(@j AS CHAR), 0, 0, 0);

-- 6. Qdrant
SET @j = JSON_OBJECT(
    'collectionUrl', 'http://localhost:6333/collections/neuron-ai/',
    'key', 'QDRANT_API_KEY'
);
INSERT INTO sys_agents_vector_store (type, title, docs, topk, params, duplicate, changed, active)
VALUES ('qdrant', 'Qdrant', 'https://qdrant.tech/', 4, CAST(@j AS CHAR), 0, 0, 0);

-- 7. ChromaDB
SET @j = JSON_OBJECT(
    'collection', 'vectorstore',
    'host', 'http://localhost:8000'
);
INSERT INTO sys_agents_vector_store (type, title, docs, topk, params, duplicate, changed, active)
VALUES ('chromadb', 'ChromaDB', 'https://www.trychroma.com/products/chromadb', 5, CAST(@j AS CHAR), 0, 0, 0);

-- 8. Meilisearch
SET @j = JSON_OBJECT(
    'indexUid', 'MEILISEARCH_INDEXUID',
    'host', 'http://localhost:8000',
    'key', 'MEILISEARCH_API_KEY',
    'embedder', 'default'
);
INSERT INTO sys_agents_vector_store (type, title, docs, topk, params, duplicate, changed, active)
VALUES ('meilisearch', 'Meilisearch', 'https://www.meilisearch.com/', 5, CAST(@j AS CHAR), 0, 0, 0);

CREATE TABLE IF NOT EXISTS `sys_agents_tools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(128) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `docs` text DEFAULT NULL,
  `params` text DEFAULT NULL,
  `params_user` text DEFAULT NULL,
  `duplicate` tinyint(4) NOT NULL DEFAULT 1,
  `changed` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `class_name` varchar(255) NOT NULL DEFAULT '',
  `class_file` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
);

TRUNCATE TABLE `sys_agents_tools`;

INSERT INTO `sys_agents_tools` (`type`, `title`, `docs`, `params`, `params_user`, `duplicate`, `changed`, `active`, `class_name`, `class_file`) VALUES
('mysql_schema', 'MySQL Schema', 'This tool allows agents to understand the structure of the database, enabling them to construct intelligent queries without requiring you to hardcode table structures or relationships into the prompts. This tool essentially gives your agent the equivalent of a database administrator''s understanding of your schema, allowing it to craft queries that respect your data model and take advantage of existing indexes.  \r\nIt reads all tables by default, but it''s possible to limit it to a certain number of tables by providing the list in the `tables` parameter. By limiting the schema scope, you can create specialized agents that focus on specific areas of the site.', '{\"tables\":[]}', NULL, 0, 0, 1, '', ''),
('mysql_select', 'MySQL Select', 'Use this tool to make your agent able to run SELECT query against the database. It''s like read-only access without ability to change anything in the DB. It works the best in par of MySQL Schema tool.  \r\nNo parameters are needed for this tool.', '{}', NULL, 0, 0, 1, '', ''),
('mysql_write', 'MySQL Write', 'Use this tool to make your agent able to performs write operations against the database (INSERT, UPDATE, DELETE). It works the best in par of MySQL Schema tool.   \r\nNo parameters are needed for this tool.  \r\n**USE WITH CAUTION**', '{}', NULL, 0, 0, 0, '', ''),

('content_structure', 'Content modules structure', 'This tool allows agents to understand the structure of content modules fields,  allowing them to call content adding and editing with correct data. It provide knowledge of what content modules are available and what the fields in them, event if fields are changed in fields builder.', '{}', NULL, 0, 0, 1, 'BxDolAIToolContentStructure', ''),
('content_get', 'Content modules get', 'This tool allows agents to get info about specific content by module name and content id.', '{}', NULL, 0, 0, 1, 'BxDolAIToolContentGet', ''),
('content_search', 'Content modules search', 'This tool allows agents to search for content items based on a keyword and optional sections.', '{}', NULL, 0, 0, 1, 'BxDolAIToolContentSearch', ''),
('content_delete', 'Content modules delete', 'This tool allows agents to delete content. ', '{}', NULL, 0, 0, 1, 'BxDolAIToolContentDelete', ''),
('content_add', 'Content modules add', 'This tool allows agents to add new content. It works with the "content_structure" tool to get knowledge about content modules fields for add actions.', '{}', NULL, 0, 0, 1, 'BxDolAIToolContentAdd', ''),
('content_update', 'Content modules update', 'This tool allows agents to update existing content. It works with the "content_structure" tool to get knowledge about content modules fields for update actions.', '{}', NULL, 0, 0, 1, 'BxDolAIToolContentUpdate', ''),

('comments_get', 'Comments get', 'This tool allows agents to get comments for specific content by module name and content id.', '{}', NULL, 0, 0, 1, 'BxDolAIToolCmtsGet', ''),
('comments_add', 'Comments add', 'This tool allows agents to post comments for specific content.', '{}', NULL, 0, 0, 1, 'BxDolAIToolCmtsAdd', ''),
('comments_update', 'Comments update', 'This tool allows agents to edit comments.', '{}', NULL, 0, 0, 1, 'BxDolAIToolCmtsUpdate', ''),
('comments_delete', 'Comments delete', 'This tool allows agents to delete comments.', '{}', NULL, 0, 0, 1, 'BxDolAIToolCmtsDelete', '');

CREATE TABLE IF NOT EXISTS `sys_agents_chat_history` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `thread_id` VARCHAR(255) NOT NULL,
  `messages` LONGTEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_thread_id` (`thread_id`(192))
);

-- Logger 

CREATE TABLE IF NOT EXISTS `sys_logger` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `level`      VARCHAR(16)     NOT NULL,
    `message`    TEXT            NOT NULL,
    `context`    TEXT            NULL,
    `channel`    VARCHAR(64)     NOT NULL DEFAULT 'system',
    `created_at` DATETIME(3)     NOT NULL DEFAULT CURRENT_TIMESTAMP(3),

    PRIMARY KEY (`id`),
    INDEX `idx_level`      (`level`),
    INDEX `idx_channel`    (`channel`),
    INDEX `idx_created_at` (`created_at`)
);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '15.0.0-B2' WHERE (`version` = '15.0.0.B1' OR `version` = '15.0.0-B1') AND `name` = 'system';

