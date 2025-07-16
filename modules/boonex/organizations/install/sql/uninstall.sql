
-- TABLES
DROP TABLE IF EXISTS `bx_organizations_data`, `bx_organizations_pics`, `bx_organizations_pics_resized`, `bx_organizations_cmts`, `bx_organizations_cmts_notes`, `bx_organizations_views_track`, `bx_organizations_votes`, `bx_organizations_votes_track`, `bx_organizations_reactions`, `bx_organizations_reactions_track`, `bx_organizations_favorites_track`, `bx_organizations_favorites_lists`, `bx_organizations_reports`, `bx_organizations_reports_track`, `bx_organizations_meta_keywords`, `bx_organizations_meta_locations`, `bx_organizations_meta_mentions`, `bx_organizations_fans`, `bx_organizations_admins`, `bx_organizations_scores`, `bx_organizations_scores_track`, `bx_organizations_prices`;

-- PROFILES
DELETE FROM sys_profiles WHERE `type` = 'bx_organizations';

-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_storage` WHERE `object` IN('bx_organizations_pics', 'bx_organizations_pics_resized');
DELETE FROM `sys_storage_tokens` WHERE `object` IN('bx_organizations_pics', 'bx_organizations_pics_resized');

DELETE FROM `sys_objects_transcoder` WHERE `object` IN('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_avatar_big', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb', 'bx_organizations_gallery', 'bx_organizations_badge');
DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_avatar_big', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb', 'bx_organizations_gallery', 'bx_organizations_badge');
DELETE FROM `sys_transcoder_images_files` WHERE `transcoder_object` IN('bx_organizations_icon', 'bx_organizations_thumb', 'bx_organizations_avatar', 'bx_organizations_avatar_big', 'bx_organizations_picture', 'bx_organizations_cover', 'bx_organizations_cover_thumb', 'bx_organizations_gallery', 'bx_organizations_badge');

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_organizations';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_organization_add', 'bx_organization_delete', 'bx_organization_edit', 'bx_organization_edit_cover', 'bx_organization_edit_badge', 'bx_organization_view', 'bx_organization_view_full', 'bx_organization_invite', 'bx_organizations_price_add', 'bx_organizations_price_edit');

-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_organizations';

DELETE FROM `sys_form_pre_values` WHERE `Key` LIKE 'bx_organizations%';

-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name` LIKE 'bx_organizations%';

-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name` = 'bx_organizations';

-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name` IN ('bx_organizations', 'bx_organizations_reactions');

-- SCORES
DELETE FROM `sys_objects_score` WHERE `name` = 'bx_organizations';

-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name` = 'bx_organizations';

-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name` = 'bx_organizations';

-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name` = 'bx_organizations';

-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_organizations', 'bx_organizations_cmts');

DELETE FROM `sys_content_info_grids` WHERE `object` IN ('bx_organizations');

-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module` = 'bx_organizations';

-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_organizations';
