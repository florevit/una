SET @sName = 'bx_organizations';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name`='sys_site' AND `name`='organizations-home' AND `icon`='building col-red2';
UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name`='sys_homepage' AND `name`='organizations-home' AND `icon`='building col-red2';
UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name`='sys_add_profile_links' AND `name`='create-organization-profile' AND `icon`='building col-red2';
UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name`='bx_organizations_view_submenu' AND `name`='view-organization-profile' AND `icon`='building col-red2';
UPDATE `sys_menu_items` SET `icon`='info-circle' WHERE `set_name`='bx_organizations_view_submenu' AND `name`='organization-profile-info' AND `icon`='info-circle col-gray';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='bx_organizations_view_submenu' AND `name`='organization-profile-fans' AND `icon`='users col-blue3';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='bx_organizations_view_submenu' AND `name`='organization-profile-friends' AND `icon`='users col-blue3';
UPDATE `sys_menu_items` SET `icon`='sync' WHERE `set_name`='bx_organizations_view_submenu' AND `name`='organization-profile-relations' AND `icon`='sync col-blue3';
UPDATE `sys_menu_items` SET `icon`='check' WHERE `set_name`='bx_organizations_view_submenu' AND `name`='organization-profile-subscriptions' AND `icon`='check col-blue3';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-friend-requests' AND `icon`='users col-red2';
UPDATE `sys_menu_items` SET `icon`='sync' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-relation-requests' AND `icon`='sync col-red2';
UPDATE `sys_menu_items` SET `icon`='star' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-favorite-organizations' AND `icon`='star col-red2';
UPDATE `sys_menu_items` SET `icon`='rss' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-subscriptions' AND `icon`='rss col-red2';
UPDATE `sys_menu_items` SET `icon`='rss' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-subscribed-me' AND `icon`='rss col-red2';
UPDATE `sys_menu_items` SET `icon`='sync' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-relations' AND `icon`='sync col-blue3';
UPDATE `sys_menu_items` SET `icon`='sync' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-related-me' AND `icon`='sync col-blue3';
UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-friend-requests' AND `icon`='building col-red2';
UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-organizations' AND `icon`='building col-red2';
UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name`='sys_profile_followings' AND `name`='organizations' AND `icon`='building col-red2';
UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='joined-organizations' AND `icon`='building col-red2';
UPDATE `sys_menu_items` SET `icon`='building' WHERE `set_name` LIKE '%_view_submenu' AND `name`='joined-organizations' AND `icon`='building col-red2';


-- STATS
UPDATE `sys_statistics` SET `icon`='building' WHERE `name`='bx_organizations' AND `icon`='building col-red2';
