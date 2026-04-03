SET @sName = 'bx_github';


-- SETTINGS

-- SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
-- INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
-- ('modules', @sName, '_bx_github', 'bx_github@modules/boonex/github/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
-- SET @iTypeId = LAST_INSERT_ID();

-- INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
-- VALUES (@iTypeId, @sName, '_bx_github', 10);
-- SET @iCategId = LAST_INSERT_ID();

-- INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
-- ('bx_github_badge_persons', '', @iCategId, '_bx_github_option_badge_persons', 'select', 'a:3:{s:6:"module";s:9:"bx_github";s:6:"method";s:17:"get_options_badge";s:6:"params";a:1:{i:0;s:10:"bx_persons";}}', '', '', 10);


-- PAGES

-- PAGE: settings
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_github_settings', '_bx_github_page_title_sys_settings', '_bx_github_page_title_settings', @sName, 5, 2147483647, 1, 'github-settings', 'page.php?i=github-settings', '', '', '', 0, 1, 0, 'BxGitHubPageSettings', 'modules/boonex/github/classes/BxGitHubPageSettings.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_github_settings', 1, @sName, '_bx_github_page_block_title_settings', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_github";s:6:"method";s:18:"get_block_settings";}', 0, 0, 1);


-- MENUS

-- MENU: account settings menu
SET @iMoAccountSettings = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_settings' AND `order` < 9999 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_settings', @sName, 'github-settings', '_bx_github_menu_item_title_system_settings', '_bx_github_menu_item_title_settings', 'page.php?i=github-settings', '', '_self', 'github', '', '', 2147483646, 1, 0, 1, @iMoAccountSettings + 1);
