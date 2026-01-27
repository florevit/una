SET @sName = 'bx_market';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_site' AND `name`='products-home' AND `icon`='shopping-cart col-green3';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_homepage' AND `name`='products-home' AND `icon`='shopping-cart col-green3';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_add_content_links' AND `name`='create-product' AND `icon`='shopping-cart col-green3';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-market' AND `icon`='shopping-cart col-green3';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='products-author' AND `icon`='shopping-cart col-green3';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='trigger_group_view_submenu' AND `name`='products-context' AND `icon`='shopping-cart col-green3';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name` LIKE '%_view_submenu' AND `name`='products-author' AND `icon`='shopping-cart col-green3';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name` LIKE '%_view_submenu' AND `name`='products-context' AND `icon`='shopping-cart col-green3';


-- STATS
UPDATE `sys_statistics` SET `icon`='shopping-cart' WHERE `name`='bx_market' AND `icon`='shopping-cart col-green3';
