
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Embeds

UPDATE `sys_objects_embeds` SET `title` = '_adm_stg_cpt_option_sys_embed_system' WHERE `object` = 'sys_system';

DELETE FROM `sys_objects_embeds` WHERE `object` IN ('sys_microlink', 'sys_peekalink');
INSERT INTO `sys_objects_embeds` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_microlink', '_adm_stg_cpt_option_sys_embed_microlink', 'BxTemplEmbedMicrolink', ''),
('sys_peekalink', '_adm_stg_cpt_option_sys_embed_peekalink', 'BxTemplEmbedPeekalink', '');

-- Options

UPDATE sys_options SET value = CONCAT(value, ',svg') WHERE name = 'sys_files_ext_images' AND value NOT LIKE '%svg%';

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `info`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_per_page_search_extended', '_adm_stg_cpt_option_sys_per_page_search_extended', '_adm_stg_inf_option_sys_per_page_search_extended', '12', 'digit', '', '', '', 25);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'general');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `info`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_embed_peekalink_api_key', '_adm_stg_cpt_option_sys_embed_peekalink_api_key', '', '', 'digit', '', '', '', 110);

UPDATE `sys_options` SET `category_id` = @iCategoryId, `info` = '_adm_stg_inf_option_sys_embed_microlink_key', `order` = 100 WHERE `name` = 'sys_embed_microlink_key';

UPDATE `sys_options` SET `extra` = 'File,Memcache,APC,XCache,Redis' WHERE `name` IN('sys_content_cache_engine', 'sys_db_cache_engine', 'sys_page_cache_engine', 'sys_pb_cache_engine');
UPDATE `sys_options` SET `extra` = 'FileHtml,Memcache,APC,XCache,Redis' WHERE `name` IN('sys_template_cache_engine');

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'cache');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `info`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_cache_redis_connection_string', '_adm_stg_cpt_option_sys_cache_redis_connection_string', '_adm_stg_cpt_option_sys_cache_redis_connection_string_info', '', 'digit', '', '', '', 25);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'storage');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_storage_ghost_lifetime', '_adm_stg_cpt_option_sys_storage_ghost_lifetime', '60', 'digit', '', '', '', 20);

-- Storage

UPDATE `sys_objects_storage` SET `ext_allow` = '{image}' WHERE `object` IN ('sys_images', 'sys_images_custom', 'sys_images_resized');

-- Menu

UPDATE `sys_objects_menu` SET `override_class_name`='BxTemplMenuTagsCloud' WHERE `object`='sys_tags_cloud';

UPDATE `sys_objects_menu` SET `title_public`='_sys_menu_title_public_ntfs_submenu' WHERE `object`='sys_ntfs_submenu';


UPDATE `sys_menu_items` SET `icon` = 'home' WHERE `set_name` = 'sys_site' AND `name` = 'home' AND `icon` = 'home col-gray';

UPDATE `sys_menu_items` SET `icon` = 'info-circle' WHERE `set_name` = 'sys_site' AND `name` = 'about' AND `icon` = 'info-circle col-blue3-dark';


UPDATE `sys_menu_items` SET `icon` = 'cart-plus' WHERE `set_name` = 'sys_account_notifications' AND `name` = 'cart' AND `icon` = 'cart-plus col-red3';

UPDATE `sys_menu_items` SET `icon` = 'cart-arrow-down' WHERE `set_name` = 'sys_account_notifications' AND `name` = 'orders' AND `icon` = 'cart-arrow-down col-green3';

UPDATE `sys_menu_items` SET `icon` = 'file-invoice' WHERE `set_name` = 'sys_account_notifications' AND `name` = 'invoices' AND `icon` = 'file-invoice col-green3';


UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_con_submenu' AND `module`='system' AND `name`='friends' AND `icon`='';
UPDATE `sys_menu_items` SET `icon`='user-shield' WHERE `set_name`='sys_con_submenu' AND `module`='system' AND `name`='friend-suggestions' AND `icon`='';
UPDATE `sys_menu_items` SET `icon`='user-plus' WHERE `set_name`='sys_con_submenu' AND `module`='system' AND `name`='friend-requests' AND `icon`='';
UPDATE `sys_menu_items` SET `icon`='user-check' WHERE `set_name`='sys_con_submenu' AND `module`='system' AND `name`='sent-friend-requests' AND `icon`='';
UPDATE `sys_menu_items` SET `icon`='address-book' WHERE `set_name`='sys_con_submenu' AND `module`='system' AND `name`='follow-suggestions' AND `icon`='';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_con_submenu' AND `module`='system' AND `name`='followers' AND `icon`='';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_con_submenu' AND `module`='system' AND `name`='following' AND `icon`='';

-- Pages

CREATE TABLE IF NOT EXISTS `sys_pages_layout_columns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_id` int(11) NOT NULL DEFAULT '0',
  `index` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `width` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `column` (`layout_id`, `index`)
);

TRUNCATE TABLE `sys_pages_layout_columns`;
INSERT INTO `sys_pages_layout_columns` (`layout_id`, `index`, `name`, `width`) VALUES
(1, 1, 'left', '1/3'), (1, 2, 'center', '2/3'),
(2, 1, 'center', '2/3'), (2, 2, 'right', '1/3'),
(3, 1, 'left', '1/3'), (3, 2, 'center', '1/3'), (3, 3, 'right', '1/3'),
(4, 1, 'left', '1/2'), (4, 2, 'right', '1/3'),
(5, 1, 'center', '1'),
(6, 1, 'top', '1'), (6, 2, 'left', '1/3'), (6, 3, 'center', '2/3'),
(7, 1, 'top', '1'), (7, 2, 'center', '2/3'), (7, 3, 'right', '1/3'),
(8, 1, 'top', '1'), (8, 2, 'left', '1/3'), (8, 3, 'center', '1/3'), (8, 4, 'right', '1/3'),
(9, 1, 'top', '1'), (9, 2, 'left', '1/2'), (9, 3, 'right', '1/2'),
(10, 1, 'top', '1'), (10, 2, 'left', '1/2'), (10, 3, 'right', '1/2'), (10, 4, 'bottom', '1'),
(11, 1, 'left', '1/2'), (11, 2, 'right', '1/2'), (11, 3, 'bottom', '1'),
(12, 1, 'top', '1'), (12, 2, 'center', '2/3'), (12, 3, 'right', '1/3'), (12, 4, 'bottom', '1'),
(13, 1, 'top', '1'), (13, 2, 'left', '1/3'), (13, 3, 'center', '2/3'), (13, 4, 'bottom', '1'),
(14, 1, 'left', '1/4'), (14, 2, 'center', '1/2'), (14, 3, 'right', '1/4'),
(15, 1, 'top', '1'), (15, 2, 'left', '1/4'), (15, 3, 'center', '1/2'), (15, 4, 'right', '1/4'),
(16, 1, 'top', '1'), (16, 2, 'left', '1/6'), (16, 3, 'center', '1/2'), (16, 4, 'right', '1/3'), (16, 5, 'bottom', '1'),
(17, 1, 'top', '1'), (17, 2, 'left', '1/6'), (17, 3, 'center', '5/6'), (17, 4, 'bottom', '1'),
(18, 1, 'center', '1/3'),
(19, 1, 'center', '1/2'),
(20, 1, 'center', '1/2'),
(21, 1, 'top', '1'), (21, 2, 'left', '1/5'), (21, 3, 'center', '1/2'), (21, 4, 'right', '3/10'), (21, 5, 'bottom', '1');

-- SEO links

ALTER TABLE `sys_seo_links` CHANGE `added` `added` INT(11) NOT NULL;

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '15.0.0-B1' WHERE (`version` = '15.0.0.A2' OR `version` = '15.0.0-A2') AND `name` = 'system';

