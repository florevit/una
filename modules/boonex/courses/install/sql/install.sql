SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- TABLE: PROFILES
CREATE TABLE IF NOT EXISTS `bx_courses_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `picture` int(11) NOT NULL,
  `cover` int(11) NOT NULL,
  `cover_data` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `desc` text NOT NULL,
  `labels` text NOT NULL,
  `location` text NOT NULL,
  `members` int(11) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `rrate` float NOT NULL default '0',
  `rvotes` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `sc_up` int(11) NOT NULL default '0',
  `sc_down` int(11) NOT NULL default '0',
  `favorites` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `cf` int(11) NOT NULL default '1',
  `join_confirmation` tinyint(4) NOT NULL DEFAULT '0',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `allow_post_to` varchar(16) NOT NULL DEFAULT '3',
  `status` enum('active','awaiting','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search_fields` (`name`, `desc`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_content_structure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `node_id` int(11) NOT NULL DEFAULT '0',
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `cn_l2` int(11) NOT NULL DEFAULT '0',
  `cn_l3` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `node_id` (`node_id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_content_nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `passing` tinyint(4) NOT NULL DEFAULT '0',
  `counters` text NOT NULL,
  `added` int(11) NOT NULL,
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_content_nodes2users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pass` (`node_id`, `profile_id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_content_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL default '0',
  `node_id` int(11) NOT NULL DEFAULT '0',
  `content_type` varchar(32) NOT NULL DEFAULT '',
  `content_id` int(11) NOT NULL DEFAULT '0',
  `usage` tinyint(4) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_content_data2users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pass` (`data_id`, `profile_id`)
);

-- TABLE: STORAGES & TRANSCODERS
CREATE TABLE IF NOT EXISTS `bx_courses_pics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_pics_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

-- TABLE: comments
CREATE TABLE IF NOT EXISTS `bx_courses_cmts` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  `cmt_cf` int(11) NOT NULL default '1',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_cmts_notes` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

-- TABLE: VIEWS
CREATE TABLE IF NOT EXISTS `bx_courses_views_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: VOTES
CREATE TABLE IF NOT EXISTS `bx_courses_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_reactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_reactions_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

-- TABLE: REPORTS
CREATE TABLE IF NOT EXISTS `bx_courses_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_reports_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `text` text NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `checked_by` int(11) NOT NULL default '0',
  `status` tinyint(11) NOT NULL default '0',	
  PRIMARY KEY (`id`),
  KEY `report` (`object_id`, `author_nip`)
);

-- TABLE: metas
CREATE TABLE IF NOT EXISTS `bx_courses_meta_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_meta_locations` (
  `object_id` int(10) unsigned NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `street_number` varchar(255) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `country_state_city` (`country`,`state`(8),`city`(8))
);

CREATE TABLE IF NOT EXISTS `bx_courses_meta_mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: fans
CREATE TABLE IF NOT EXISTS `bx_courses_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `mutual` tinyint(4) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
);

-- TABLE: admins
CREATE TABLE IF NOT EXISTS `bx_courses_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_profile_id` int(10) unsigned NOT NULL,
  `fan_id` int(10) unsigned NOT NULL,
  `role` int(10) unsigned NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `expired` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin` (`group_profile_id`,`fan_id`)
);

-- TABLE: favorites
CREATE TABLE IF NOT EXISTS `bx_courses_favorites_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `list_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`author_id`)
);

CREATE TABLE `bx_courses_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);


-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_courses_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(128) NOT NULL default '0',
  `group_profile_id` int(11) NOT NULL default '0',
  `author_profile_id` int(11) NOT NULL default '0',
  `invited_profile_id` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`)
);

-- TABLE: Pricing
CREATE TABLE IF NOT EXISTS `bx_courses_prices` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `role_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `period` int(11) unsigned NOT NULL default '1',
  `period_unit` varchar(32) NOT NULL default '',
  `price` float unsigned NOT NULL default '1',
  `added` int(11) NOT NULL default '0',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `type` (`profile_id`, `role_id`,`period`, `period_unit`)
);

-- STORAGES & TRANSCODERS
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_courses_pics', @sStorageEngine, '', 360, 2592000, 3, 'bx_courses_pics', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),
('bx_courses_pics_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_courses_pics_resized', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_courses_icon', 'bx_courses_pics_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_courses_pics";}', 'no', '1', '2592000', '0'),
('bx_courses_thumb', 'bx_courses_pics_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_courses_pics";}', 'no', '1', '2592000', '0'),
('bx_courses_avatar', 'bx_courses_pics_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_courses_pics";}', 'no', '1', '2592000', '0'),
('bx_courses_avatar_big', 'bx_courses_pics_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_courses_pics";}', 'no', '1', '2592000', '0'),
('bx_courses_picture', 'bx_courses_pics_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_courses_pics";}', 'no', '1', '2592000', '0'),
('bx_courses_cover', 'bx_courses_pics_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_courses_pics";}', 'no', '1', '2592000', '0'),
('bx_courses_cover_thumb', 'bx_courses_pics_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_courses_pics";}', 'no', '1', '2592000', '0'),
('bx_courses_gallery', 'bx_courses_pics_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_courses_pics";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_courses_icon', 'Resize', 'a:3:{s:1:"w";s:2:"30";s:1:"h";s:2:"30";s:13:"square_resize";s:1:"1";}', '0'),
('bx_courses_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}', '0'),
('bx_courses_avatar', 'Resize', 'a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}', '0'),
('bx_courses_avatar_big', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', '0'),
('bx_courses_picture', 'Resize', 'a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"0";}', '0'),
('bx_courses_cover', 'Resize', 'a:1:{s:1:"w";s:4:"1200";}', '0'),
('bx_courses_cover_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0'),
('bx_courses_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');

-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_course', 'bx_courses', '_bx_courses_form_profile', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_courses_data', 'id', '', '', 'do_submit', '', 0, 1, 'BxCoursesFormEntry', 'modules/boonex/courses/classes/BxCoursesFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_course', 'bx_course_add', 'bx_courses', 0, '_bx_courses_form_profile_display_add'),
('bx_course', 'bx_course_delete', 'bx_courses', 0, '_bx_courses_form_profile_display_delete'),
('bx_course', 'bx_course_edit', 'bx_courses', 0, '_bx_courses_form_profile_display_edit'),
('bx_course', 'bx_course_edit_cover', 'bx_courses', 0, '_bx_courses_form_profile_display_edit_cover'),
('bx_course', 'bx_course_view', 'bx_courses', 1, '_bx_courses_form_profile_display_view'),
('bx_course', 'bx_course_view_full', 'bx_courses', 1, '_bx_courses_form_profile_display_view_full'),
('bx_course', 'bx_course_invite', 'bx_courses', 0, '_bx_courses_form_profile_display_invite');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_course', 'bx_courses', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_course', 'bx_courses', 'allow_view_to', 3, '', 0, 'custom', '_bx_courses_form_profile_input_sys_allow_view_to', '_bx_courses_form_profile_input_allow_view_to', '_bx_courses_form_profile_input_allow_view_to_desc', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_course', 'bx_courses', 'allow_post_to', 3, '', 0, 'custom', '_bx_courses_form_profile_input_sys_allow_post_to', '_bx_courses_form_profile_input_allow_post_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_course', 'bx_courses', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_courses_form_profile_input_sys_delete_confirm', '_bx_courses_form_profile_input_delete_confirm', '_bx_courses_form_profile_input_delete_confirm_info', 1, 0, 0, '', '', '', 'avail', '', '_bx_courses_form_profile_input_delete_confirm_error', '', '', 1, 0),
('bx_course', 'bx_courses', 'do_submit', '_sys_form_account_input_submit', '', 0, 'submit', '_bx_courses_form_profile_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_course', 'bx_courses', 'do_cancel', '_sys_form_input_cancel', '', 0, 'button', '_sys_form_input_sys_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:41:"window.open(''{edit_cancel_url}'', ''_self'')";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0),
('bx_course', 'bx_courses', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '_sys_form_input_sys_controls', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_course', 'bx_courses', 'desc', '', '', 0, 'textarea', '_bx_courses_form_profile_input_sys_desc', '_bx_courses_form_profile_input_desc', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 1),
('bx_course', 'bx_courses', 'cat', '', '#!bx_courses_cats', 0, 'select', '_bx_courses_form_profile_input_sys_cat', '_bx_courses_form_profile_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_courses_form_profile_input_cat_err', 'Xss', '', 1, 1),
('bx_course', 'bx_courses', 'name', '', '', 0, 'text', '_bx_courses_form_profile_input_sys_name', '_bx_courses_form_profile_input_name', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_courses_form_profile_input_name_err', 'Xss', '', 1, 0),
('bx_course', 'bx_courses', 'initial_members', '', '', 0, 'custom', '_bx_courses_form_profile_input_sys_initial_members', '_bx_courses_form_profile_input_initial_members', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('bx_course', 'bx_courses', 'join_confirmation', 1, '', 0, 'switcher', '_bx_courses_form_profile_input_sys_join_confirm', '_bx_courses_form_profile_input_join_confirm', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_course', 'bx_courses', 'cover', 'a:1:{i:0;s:21:"bx_courses_cover_crop";}', 'a:1:{s:21:"bx_courses_cover_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_courses_form_profile_input_sys_cover', '_bx_courses_form_profile_input_cover', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_course', 'bx_courses', 'picture', 'a:1:{i:0;s:23:"bx_courses_picture_crop";}', 'a:1:{s:23:"bx_courses_picture_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_courses_form_profile_input_sys_picture', '_bx_courses_form_profile_input_picture', '', 0, 0, 0, '', '', '', '', '', '_bx_courses_form_profile_input_picture_err', '', '', 1, 0),
('bx_course', 'bx_courses', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_course', 'bx_courses', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_course_add', 'initial_members', 2147483647, 1, 1),
('bx_course_add', 'name', 2147483647, 1, 2),
('bx_course_add', 'cat', 2147483647, 1, 3),
('bx_course_add', 'desc', 2147483647, 1, 4),
('bx_course_add', 'location', 2147483647, 1, 5),
('bx_course_add', 'join_confirmation', 2147483647, 1, 6),
('bx_course_add', 'allow_view_to', 2147483647, 1, 7),
('bx_course_add', 'allow_post_to', 2147483647, 1, 8),
('bx_course_add', 'cf', 2147483647, 1, 9),
('bx_course_add', 'do_submit', 2147483647, 1, 10),

('bx_course_invite', 'initial_members', 2147483647, 1, 1),
('bx_course_invite', 'do_submit', 2147483647, 1, 2),

('bx_course_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_course_delete', 'do_submit', 2147483647, 1, 2),

('bx_course_edit', 'name', 2147483647, 1, 1),
('bx_course_edit', 'cat', 2147483647, 1, 2),
('bx_course_edit', 'desc', 2147483647, 1, 3),
('bx_course_edit', 'location', 2147483647, 1, 4),
('bx_course_edit', 'join_confirmation', 2147483647, 1, 5),
('bx_course_edit', 'allow_view_to', 2147483647, 1, 6),
('bx_course_edit', 'allow_post_to', 2147483647, 1, 7),
('bx_course_edit', 'cf', 2147483647, 1, 8),
('bx_course_edit', 'controls', 2147483647, 1, 9),
('bx_course_edit', 'do_submit', 2147483647, 1, 10),
('bx_course_edit', 'do_cancel', 2147483647, 1, 11),

('bx_course_edit_cover', 'cover', 2147483647, 1, 1),
('bx_course_edit_cover', 'do_submit', 2147483647, 1, 2),

('bx_course_view', 'name', 2147483647, 1, 1),
('bx_course_view', 'cat', 2147483647, 1, 2),

('bx_course_view_full', 'name', 2147483647, 1, 1),
('bx_course_view_full', 'cat', 2147483647, 1, 2),
('bx_course_view_full', 'desc', 2147483647, 1, 3);

-- FORMS: Price
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_courses_price', 'bx_courses', '_bx_courses_form_price', '', '', 'do_submit', 'bx_courses_prices', 'id', '', '', '', 0, 1, 'BxCoursesFormPrice', 'modules/boonex/courses/classes/BxCoursesFormPrice.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_courses_price_add', 'bx_courses', 'bx_courses_price', '_bx_courses_form_price_display_add', 0),
('bx_courses_price_edit', 'bx_courses', 'bx_courses_price', '_bx_courses_form_price_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_courses_price', 'bx_courses', 'id', '', '', 0, 'hidden', '_bx_courses_form_price_input_sys_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_courses_price', 'bx_courses', 'role_id', '', '', 0, 'hidden', '_bx_courses_form_price_input_sys_role_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_courses_price', 'bx_courses', 'name', '', '', 0, 'text', '_bx_courses_form_price_input_sys_name', '_bx_courses_form_price_input_name', '_bx_courses_form_price_input_inf_name', 1, 0, 0, '', '', '', 'Avail', '', '_bx_courses_form_price_input_err_name', 'Xss', '', 1, 0),
('bx_courses_price', 'bx_courses', 'period', '', '', 0, 'text', '_bx_courses_form_price_input_sys_period', '_bx_courses_form_price_input_period', '_bx_courses_form_price_input_inf_period', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_courses_price', 'bx_courses', 'period_unit', '', '#!bx_courses_period_units', 0, 'select', '_bx_courses_form_price_input_sys_period_unit', '_bx_courses_form_price_input_period_unit', '_bx_courses_form_price_input_inf_period_unit', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_courses_price', 'bx_courses', 'price', '', '', 0, 'price', '_bx_courses_form_price_input_sys_price', '_bx_courses_form_price_input_price', '_bx_courses_form_price_input_inf_price', 1, 0, 0, '', '', '', '', '', '', 'Float', '', 1, 0),
('bx_courses_price', 'bx_courses', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_courses_price', 'bx_courses', 'do_submit', '_bx_courses_form_price_input_do_submit', '', 0, 'submit', '_bx_courses_form_price_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_courses_price', 'bx_courses', 'do_cancel', '_bx_courses_form_price_input_do_cancel', '', 0, 'button', '_bx_courses_form_price_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_courses_price_add', 'id', 2147483647, 0, 1),
('bx_courses_price_add', 'role_id', 2147483647, 1, 2),
('bx_courses_price_add', 'name', 2147483647, 1, 3),
('bx_courses_price_add', 'price', 2147483647, 1, 4),
('bx_courses_price_add', 'period', 2147483647, 1, 5),
('bx_courses_price_add', 'period_unit', 2147483647, 1, 6),
('bx_courses_price_add', 'controls', 2147483647, 1, 7),
('bx_courses_price_add', 'do_submit', 2147483647, 1, 8),
('bx_courses_price_add', 'do_cancel', 2147483647, 1, 9),

('bx_courses_price_edit', 'id', 2147483647, 1, 1),
('bx_courses_price_edit', 'role_id', 2147483647, 1, 2),
('bx_courses_price_edit', 'name', 2147483647, 1, 3),
('bx_courses_price_edit', 'price', 2147483647, 1, 4),
('bx_courses_price_edit', 'period', 2147483647, 1, 5),
('bx_courses_price_edit', 'period_unit', 2147483647, 1, 6),
('bx_courses_price_edit', 'controls', 2147483647, 1, 7),
('bx_courses_price_edit', 'do_submit', 2147483647, 1, 8),
('bx_courses_price_edit', 'do_cancel', 2147483647, 1, 9);

-- FORMS: Content Nodes
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_courses_content_node', 'bx_courses', '_bx_courses_form_content_node', '', '', 'do_submit', 'bx_courses_content_nodes', 'id', '', '', '', 0, 1, 'BxCoursesFormContentNode', 'modules/boonex/courses/classes/BxCoursesFormContentNode.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_courses_content_node_add', 'bx_courses', 'bx_courses_content_node', '_bx_courses_form_content_node_display_add', 0),
('bx_courses_content_node_edit', 'bx_courses', 'bx_courses_content_node', '_bx_courses_form_content_node_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_courses_content_node', 'bx_courses', 'title', '', '', 0, 'text', '_bx_courses_form_content_node_input_sys_title', '_bx_courses_form_content_node_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_courses_form_content_node_input_title_err', 'Xss', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'text', '', '', 0, 'textarea', '_bx_courses_form_content_node_input_sys_text', '_bx_courses_form_content_node_input_text', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'passing', '', '', 0, 'select', '_bx_courses_form_content_node_input_sys_passing', '_bx_courses_form_content_node_input_passing', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'do_submit', '_bx_courses_form_content_node_input_do_submit', '', 0, 'submit', '_bx_courses_form_content_node_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'do_cancel', '_bx_courses_form_content_node_input_do_cancel', '', 0, 'button', '_bx_courses_form_content_node_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_courses_content_node_add', 'title', 2147483647, 1, 1),
('bx_courses_content_node_add', 'text', 2147483647, 1, 2),
('bx_courses_content_node_add', 'passing', 2147483647, 1, 3),
('bx_courses_content_node_add', 'controls', 2147483647, 1, 4),
('bx_courses_content_node_add', 'do_submit', 2147483647, 1, 5),
('bx_courses_content_node_add', 'do_cancel', 2147483647, 1, 6),

('bx_courses_content_node_edit', 'title', 2147483647, 1, 1),
('bx_courses_content_node_edit', 'text', 2147483647, 1, 2),
('bx_courses_content_node_edit', 'passing', 2147483647, 1, 3),
('bx_courses_content_node_edit', 'controls', 2147483647, 1, 4),
('bx_courses_content_node_edit', 'do_submit', 2147483647, 1, 5),
('bx_courses_content_node_edit', 'do_cancel', 2147483647, 1, 6);

-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_courses_cats', '_bx_courses_pre_lists_cats', 'bx_courses', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_courses_cats', '', 0, '_sys_please_select', ''),
('bx_courses_cats', '1', 1, '_bx_courses_cat_General', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_courses_roles', '_bx_courses_pre_lists_roles', 'bx_courses', '1');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_courses_roles', '0', 1, '_bx_courses_role_regular', ''),
('bx_courses_roles', '1', 2, '_bx_courses_role_administrator', ''),
('bx_courses_roles', '2', 3, '_bx_courses_role_moderator', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_courses_period_units', '_bx_courses_pre_lists_period_units', 'bx_courses', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_courses_period_units', '', 0, '_sys_please_select', ''),
('bx_courses_period_units', 'day', 1, '_bx_courses_period_unit_day', ''),
('bx_courses_period_units', 'week', 2, '_bx_courses_period_unit_week', ''),
('bx_courses_period_units', 'month', 3, '_bx_courses_period_unit_month', ''),
('bx_courses_period_units', 'year', 4, '_bx_courses_period_unit_year', '');

-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_courses', 'bx_courses', 'bx_courses_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-course-profile&id={object_id}', '', 'bx_courses_data', 'id', 'author', 'name', 'comments', 'BxCoursesCmts', 'modules/boonex/courses/classes/BxCoursesCmts.php'),
('bx_courses_notes', 'bx_courses', 'bx_courses_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-course-profile&id={object_id}', '', 'bx_courses_data', 'id', 'author', 'name', '', 'BxTemplCmtsNotes', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `module`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_courses', 'bx_courses', 'bx_courses_views_track', '86400', '1', 'bx_courses_data', 'id', 'author', 'views', '', '');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `Module`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_courses', 'bx_courses', 'bx_courses_votes', 'bx_courses_votes_track', '604800', '1', '1', '0', '1', 'bx_courses_data', 'id', 'author', 'rate', 'votes', '', ''),
('bx_courses_reactions', 'bx_courses', 'bx_courses_reactions', 'bx_courses_reactions_track', '604800', '1', '1', '1', '1', 'bx_courses_data', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');

-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_courses', 'bx_courses', 'bx_courses_scores', 'bx_courses_scores_track', '604800', '1', 'bx_courses_data', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_courses', 'bx_courses', 'bx_courses_reports', 'bx_courses_reports_track', '1', 'page.php?i=view-course-profile&id={object_id}', 'bx_courses_notes', 'bx_courses_data', 'id', 'author', 'reports', '', '');

-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `table_lists`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_courses', 'bx_courses_favorites_track', 'bx_courses_favorites_lists', '1', '1', '1', 'page.php?i=view-course-profile&id={object_id}', 'bx_courses_data', 'id', 'author', 'favorites', '', '');

-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_courses', 'bx_courses', '1', '1', 'page.php?i=view-course-profile&id={object_id}', 'bx_courses_data', 'id', 'author', 'featured', '', '');

-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_courses', '_bx_courses', 'bx_courses', 'added', 'edited', 'deleted', '', ''),
('bx_courses_cmts', '_bx_courses_cmts', 'bx_courses', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_courses', 'bx_courses_administration', 'td`.`id', '', ''),
('bx_courses', 'bx_courses_common', 'td`.`id', '', '');

-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_courses', 'bx_courses', 'bx_courses', '_bx_courses_search_extended', 1, '', ''),
('bx_courses_cmts', 'bx_courses_cmts', 'bx_courses', '_bx_courses_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');

-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_courses', '_bx_courses', '_bx_courses', 'bx_courses@modules/boonex/courses/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_courses', 'content', '{url_studio}module.php?name=bx_courses', '', 'bx_courses@modules/boonex/courses/|std-icon.svg', '_bx_courses', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

