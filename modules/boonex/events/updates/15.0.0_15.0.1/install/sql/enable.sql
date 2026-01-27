SET @sName = 'bx_events';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name`='sys_site' AND `name`='events-home' AND `icon`='calendar col-red2';
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name`='sys_homepage' AND `name`='events-home' AND `icon`='calendar col-red2';
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name`='sys_add_content_links' AND `name`='create-event-profile' AND `icon`='calendar col-red2';
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name`='bx_events_view_submenu' AND `name`='view-event-profile' AND `icon`='calendar col-red2';
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-events' AND `icon`='calendar col-red2';
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name`='sys_profile_followings' AND `name`='events' AND `icon`='calendar col-red2';
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='joined-events' AND `icon`='calendar col-red2';
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name`='trigger_group_view_submenu' AND `name`='events-context' AND `icon`='calendar col-red2';
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name` LIKE '%_view_submenu' AND `name`='joined-events' AND `icon`='calendar col-red2';
UPDATE `sys_menu_items` SET `icon`='calendar' WHERE `set_name` LIKE '%_view_submenu' AND `name`='events-context' AND `icon`='calendar col-red2';


-- STATS
UPDATE `sys_statistics` SET `icon`='calendar' WHERE `name`='bx_events' AND `icon`='calendar col-red2';
