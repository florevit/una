-- MENUS
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_site' AND `name`='snipcart-home' AND `icon`='shopping-cart col-green2';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_homepage' AND `name`='snipcart-home' AND `icon`='shopping-cart col-green2';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_add_content_links' AND `name`='create-snipcart-entry' AND `icon`='shopping-cart col-green2';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-snipcart' AND `icon`='shopping-cart col-green2';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='snipcart-author' AND `icon`='shopping-cart col-green2';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='trigger_group_view_submenu' AND `name`='snipcart-context' AND `icon`='shopping-cart col-green2';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name` LIKE '%_view_submenu' AND `name`='snipcart-author' AND `icon`='shopping-cart col-green2';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name` LIKE '%_view_submenu' AND `name`='snipcart-context' AND `icon`='shopping-cart col-green2';


-- STATS
UPDATE `sys_statistics` SET `icon`='shopping-cart' WHERE `name`='bx_snipcart' AND `icon`='shopping-cart col-green2';
