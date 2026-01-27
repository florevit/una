-- MENUS
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name`='sys_site' AND `name`='ads-home' AND `icon`='ad col-green2';
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name`='sys_homepage' AND `name`='ads-home' AND `icon`='ad col-green2';
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name`='sys_add_content_links' AND `name`='create-ad' AND `icon`='ad col-green2';
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-ads-offers' AND `icon`='ad col-green2';
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-ads' AND `icon`='ad col-green2';
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name`='sys_account_dashboard' AND `name`='dashboard-ads-licenses' AND `icon`='ad col-green2';
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='ads-author' AND `icon`='ad col-green2';
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name`='trigger_group_view_submenu' AND `name`='ads-context' AND `icon`='ad col-green2';
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name` LIKE '%_view_submenu' AND `name`='ads-author' AND `icon`='ad col-green2';
UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name` LIKE '%_view_submenu' AND `name`='ads-context' AND `icon`='ad col-green2';


-- STATS
UPDATE `sys_statistics` SET `icon`='ad' WHERE `name`='bx_ads' AND `icon`='ad col-green2';
