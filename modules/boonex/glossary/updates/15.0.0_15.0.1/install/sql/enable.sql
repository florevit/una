-- MENUS
UPDATE `sys_menu_items` SET `icon`='fa-book' WHERE `set_name`='sys_site' AND `name`='glossary-home' AND `icon`='fa-book col-red3';
UPDATE `sys_menu_items` SET `icon`='fa-book' WHERE `set_name`='sys_homepage' AND `name`='glossary-home' AND `icon`='fa-book col-red3';
UPDATE `sys_menu_items` SET `icon`='fa-book' WHERE `set_name`='sys_add_content_links' AND `name`='create-glossary' AND `icon`='fa-book col-red3';
UPDATE `sys_menu_items` SET `icon`='fa-book' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-my-glossary' AND `icon`='fa-book col-red3';
UPDATE `sys_menu_items` SET `icon`='fa-book' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='glossary-author' AND `icon`='fa-book col-red3';
UPDATE `sys_menu_items` SET `icon`='fa-book' WHERE `set_name`='trigger_group_view_submenu' AND `name`='glossary-context' AND `icon`='fa-book col-red3';
UPDATE `sys_menu_items` SET `icon`='fa-book' WHERE `set_name` LIKE '%_view_submenu' AND `name`='glossary-author' AND `icon`='fa-book col-red3';
UPDATE `sys_menu_items` SET `icon`='fa-book' WHERE `set_name` LIKE '%_view_submenu' AND `name`='glossary-context' AND `icon`='fa-book col-red3';


-- STATS
UPDATE `sys_statistics` SET `icon`='fa-book' WHERE `name`='bx_glossary' AND `icon`='fa-book col-red3';
