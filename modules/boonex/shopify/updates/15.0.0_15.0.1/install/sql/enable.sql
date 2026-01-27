-- MENUS
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_site' AND `name`='shopify-home' AND `icon`='shopping-cart col-green1';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_homepage' AND `name`='shopify-home' AND `icon`='shopping-cart col-green1';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_add_content_links' AND `name`='create-shopify-entry' AND `icon`='shopping-cart col-green1';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-shopify' AND `icon`='shopping-cart col-green1';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='shopify-author' AND `icon`='shopping-cart col-green1';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='trigger_group_view_submenu' AND `name`='shopify-context' AND `icon`='shopping-cart col-green1';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name` LIKE '%_view_submenu' AND `name`='shopify-author' AND `icon`='shopping-cart col-green1';
UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name` LIKE '%_view_submenu' AND `name`='shopify-context' AND `icon`='shopping-cart col-green1';


-- STATS
UPDATE `sys_statistics` SET `icon`='shopping-cart' WHERE `name`='bx_shopify' AND `icon`='shopping-cart col-green1';
