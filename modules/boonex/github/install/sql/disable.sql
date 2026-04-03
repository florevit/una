SET @sName = 'bx_github';


-- SETTINGS
DELETE FROM `tot`, `toc`, `to` USING `sys_options_types` AS `tot` LEFT JOIN `sys_options_categories` AS `toc` ON `tot`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `tot`.`name`=@sName;


-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName OR `object` IN ('bx_github_settings');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = @sName;
DELETE FROM `sys_menu_sets` WHERE `module` = @sName;
DELETE FROM `sys_menu_items` WHERE `module` = @sName; -- OR `set_name` IN('');
