-- MENUS
UPDATE `sys_menu_items` SET `icon`='rss' WHERE `set_name`='sys_add_content_links' AND `name`='create-stream' AND `icon`='rss col-red3';
UPDATE `sys_menu_items` SET `icon`='rss' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-streams' AND `icon`='rss col-red3';


-- STATS
UPDATE `sys_statistics` SET `icon`='rss' WHERE `name`='bx_stream' AND `icon`='rss col-red3';


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_stream_publishing';
