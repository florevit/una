-- MENUS
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name`='sys_site' AND `name`='courses-home' AND `icon`='book-reader col-blue3-dark';
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name`='sys_homepage' AND `name`='courses-home' AND `icon`='book-reader col-blue3-dark';
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name`='sys_add_content_links' AND `name`='create-course-profile' AND `icon`='book-reader col-blue3-dark';
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name`='bx_courses_view_submenu' AND `name`='view-course-profile' AND `icon`='book-reader col-blue3-dark';
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-courses' AND `icon`='book-reader col-blue3-dark';
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name`='sys_profile_followings' AND `name`='courses' AND `icon`='book-reader col-blue3-dark';
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='joined-courses' AND `icon`='book-reader col-blue3-dark';
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name`='trigger_group_view_submenu' AND `name`='courses-context' AND `icon`='book-reader col-blue3-dark';
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name` LIKE '%_view_submenu' AND `name`='joined-courses' AND `icon`='book-reader col-blue3-dark';
UPDATE `sys_menu_items` SET `icon`='book-reader' WHERE `set_name` LIKE '%_view_submenu' AND `name`='courses-context' AND `icon`='book-reader col-blue3-dark';


-- STATS
UPDATE `sys_statistics` SET `icon`='book-reader' WHERE `name`='bx_courses' AND `icon`='book-reader col-blue3-dark';
