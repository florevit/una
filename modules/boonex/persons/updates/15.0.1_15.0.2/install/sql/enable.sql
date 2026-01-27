SET @sName = 'bx_persons';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='user' WHERE `set_name`='sys_site' AND `name`='persons-home' AND `icon`='user col-blue3';
UPDATE `sys_menu_items` SET `icon`='user' WHERE `set_name`='sys_homepage' AND `name`='persons-home' AND `icon`='user col-blue3';
UPDATE `sys_menu_items` SET `icon`='user' WHERE `set_name`='sys_add_profile_links' AND `name`='create-persons-profile' AND `icon`='user col-blue3';
UPDATE `sys_menu_items` SET `icon`='user' WHERE `set_name`='bx_persons_view_submenu' AND `name`='view-persons-profile' AND `icon`='user col-blue3';
UPDATE `sys_menu_items` SET `icon`='info-circle' WHERE `set_name`='bx_persons_view_submenu' AND `name`='persons-profile-info' AND `icon`='info-circle col-gray';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='bx_persons_view_submenu' AND `name`='persons-profile-friends' AND `icon`='users col-blue3';
UPDATE `sys_menu_items` SET `icon`='sync' WHERE `set_name`='bx_persons_view_submenu' AND `name`='persons-profile-relations' AND `icon`='sync col-blue3';
UPDATE `sys_menu_items` SET `icon`='check' WHERE `set_name`='bx_persons_view_submenu' AND `name`='persons-profile-subscriptions' AND `icon`='check col-blue3';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-friend-requests' AND `icon`='users col-blue3';
UPDATE `sys_menu_items` SET `icon`='sync' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-relation-requests' AND `icon`='sync col-blue3';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-friend-requests' AND `icon`='users col-blue3';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-profiles' AND `icon`='users col-blue3';
UPDATE `sys_menu_items` SET `icon`='star' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-favorite-persons' AND `icon`='star col-blue3';
UPDATE `sys_menu_items` SET `icon`='rss' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-subscriptions' AND `icon`='rss col-blue3';
UPDATE `sys_menu_items` SET `icon`='rss' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-subscribed-me' AND `icon`='rss col-blue3';
UPDATE `sys_menu_items` SET `icon`='sync' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-relations' AND `icon`='sync col-blue3';
UPDATE `sys_menu_items` SET `icon`='sync' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-related-me' AND `icon`='sync col-blue3';
UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_profile_followings' AND `name`='persons' AND `icon`='users col-blue3';


-- STATS
UPDATE `sys_statistics` SET `icon`='user' WHERE `name`='bx_persons' AND `icon`='user col-blue3';
