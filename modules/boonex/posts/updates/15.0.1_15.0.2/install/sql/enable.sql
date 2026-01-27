-- MENUS
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='sys_site' AND `name`='posts-home' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='sys_homepage' AND `name`='posts-home' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='sys_add_content_links' AND `name`='create-post' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='sys_create_post' AND `name`='create-post' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-posts' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='posts-author' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name`='trigger_group_view_submenu' AND `name`='posts-context' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name` LIKE '%_view_submenu' AND `name`='posts-author' AND `icon`='file-alt col-red3';
UPDATE `sys_menu_items` SET `icon`='file-alt' WHERE `set_name` LIKE '%_view_submenu' AND `name`='posts-context' AND `icon`='file-alt col-red3';


-- STATS
UPDATE `sys_statistics` SET `icon`='file-alt' WHERE `name`='bx_posts' AND `icon`='file-alt col-red3';
