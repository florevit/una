-- PAGES: config_api
UPDATE `sys_objects_page` SET `config_api`='{\r\n    layout: \'post\',\r\n    top: true,\r\n    blocks: {\r\n        author: {\r\n            name: \'bx_forum:entity_author\',\r\n            showTitle: false,\r\n            showBg: false,\r\n            forList: true,\r\n            forHeader: true,\r\n        },\r\n        text: {\r\n            name: \'bx_forum:entity_text_block\',\r\n            showTitle: false,\r\n            showBg: false,\r\n            forList: true,\r\n        },\r\n        attachments: {\r\n            name: \'bx_forum:entity_attachments\',\r\n            showTitle: false,\r\n            showBg: false,\r\n            forList: true,\r\n        },\r\n        actions: {\r\n            name: \'bx_forum:entity_all_actions\',\r\n            showTitle: false,\r\n            showBg: false,\r\n            forList: true,\r\n        },\r\n        \'comments-empty\': {\r\n            name: \'static:comments_empty\',\r\n            showTitle: false,\r\n            showBg: false,\r\n            forList: true,\r\n        },\r\n        comments: {\r\n            name: \'bx_forum:entity_comments\',\r\n            showTitle: false,\r\n            showBg: false,\r\n        },\r\n    },\r\n    headerSettings: {\r\n        header: false,\r\n        footer: false,\r\n        offset: false,\r\n        title: false,\r\n    },\r\n}' WHERE `object`='bx_forum_view_entry';
UPDATE `sys_objects_page` SET `config_api`='{\r\n    layout: \'navigator\',\r\n    blocks: {\r\n        browse: {\r\n            name: \'system:categories_list\',\r\n            showTitle: false,\r\n            showBg: false,\r\n        },\r\n        browse_sidebar: {\r\n            name: \'bx_forum:browse_popular\',\r\n            showTitle: true,\r\n            showBg: false,\r\n            sidebar: true,\r\n        },\r\n        categories: {\r\n            name: \'system:categories_list\',\r\n            showTitle: false,\r\n            showBg: true,\r\n            sidebar: false,\r\n            hidden: true,\r\n        },\r\n    },\r\n}' WHERE `object`='bx_forum_categories';
UPDATE `sys_objects_page` SET `config_api`='{\r\n    layout: \'navigator\',\r\n    blocks: {\r\n        browse: {\r\n            name: \'bx_forum:browse_category\',\r\n            showTitle: false,\r\n            showBg: false,\r\n            perLine: 1,\r\n            skeleton: \'notifications\',\r\n        },\r\n        browse_sidebar: {\r\n            name: \'bx_forum:browse_popular\',\r\n            showTitle: true,\r\n            showBg: false,\r\n            sidebar: true,\r\n        },\r\n        categories: {\r\n            name: \'system:categories_list\',\r\n            showTitle: false,\r\n            showBg: true,\r\n            sidebar: false,\r\n            hidden: true,\r\n        },\r\n    },\r\n}' WHERE `object`='bx_forum_category';
UPDATE `sys_objects_page` SET `config_api`='{\r\n    layout: \'navigator\',\r\n    blocks: {\r\n        browse: {\r\n            name: \'bx_forum:browse_new\',\r\n            showTitle: false,\r\n            showBg: false,\r\n            perLine: 1,\r\n        },\r\n        browse_sidebar: {\r\n            name: \'bx_forum:browse_popular\',\r\n            showTitle: true,\r\n            showBg: false,\r\n            sidebar: true,\r\n            unitType: \'small\',\r\n        },\r\n        categories: {\r\n            name: \'system:categories_list\',\r\n            showTitle: false,\r\n            showBg: true,\r\n            sidebar: false,\r\n            hidden: true,\r\n        },\r\n    },\r\n}' WHERE `object`='bx_forum_home';

-- PAGES: active_api
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_create_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_create_entry';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_edit_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_edit_entry';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_delete_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_delete_entry';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_view_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entry_breadcrumb';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_view_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entry_author';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_view_entry' AND `module`='bx_forum' AND `title_system`='_bx_forum_page_block_title_sys_entry_context' AND `title`='_bx_forum_page_block_title_entry_context';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_view_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entry_participants';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_view_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entry_text';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_view_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entry_all_actions';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_view_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entry_attachments';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_view_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entry_polls';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_view_entry' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entry_comments';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_categories' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entries_categories';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_category' AND `module`='bx_forum' AND `title_system`='_bx_forum_page_block_title_sys_entries_by_category' AND `title`='_bx_forum_page_block_title_entries_by_category';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_category' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_cats';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_keyword' AND `module`='bx_forum' AND `title_system`='_bx_forum_page_block_title_sys_entries_by_keyword' AND `title`='_bx_forum_page_block_title_entries_by_keyword';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_author' AND `module`='bx_forum' AND `title_system`='_bx_forum_page_block_title_sys_entries_of_author' AND `title`='_bx_forum_page_block_title_entries_of_author';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_favorites' AND `module`='bx_forum' AND `title_system`='_bx_forum_page_block_title_sys_favorites_entries' AND `title`='_bx_forum_page_block_title_favorites_entries';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_favorites' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_favorites_entries_info';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_favorites' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_favorites_entries_actions';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_context' AND `module`='bx_forum' AND `title_system`='_bx_forum_page_block_title_sys_entries_in_context' AND `title`='_bx_forum_page_block_title_entries_in_context';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_home' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_cats';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_search' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entries_search_form';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_search' AND `module`='bx_forum' AND `title_system`='' AND `title`='_bx_forum_page_block_title_entries_search_results';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_manage' AND `module`='bx_forum' AND `title_system`='_bx_forum_page_block_title_system_manage' AND `title`='_bx_forum_page_block_title_manage';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_forum_administration' AND `module`='bx_forum' AND `title_system`='_bx_forum_page_block_title_system_manage_administration' AND `title`='_bx_forum_page_block_title_manage';
UPDATE `sys_pages_blocks` SET `active_api`=1 WHERE `object`='bx_organizations_view_profile' AND `module`='bx_forum' AND `title_system`='_bx_forum_page_block_title_sys_my_entries' AND `title`='_bx_forum_page_block_title_my_entries';

-- MENUS:

-- MENUS: config_api

-- MENUS: active_api
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='sys_site' AND `module`='bx_forum' AND `name`='discussions-home';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='sys_homepage' AND `module`='bx_forum' AND `name`='discussions-home';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='sys_add_content_links' AND `module`='bx_forum' AND `name`='create-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='sys_create_post' AND `module`='bx_forum' AND `name`='create-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='sys_profile_stats' AND `module`='bx_forum' AND `name`='profile-stats-my-forum';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='sys_account_dashboard_manage_tools' AND `module`='bx_forum' AND `name`='discussions-administration';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_entry_attachments' AND `module`='bx_forum' AND `name`='photo_html5';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_entry_attachments' AND `module`='bx_forum' AND `name`='video_html5';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_entry_attachments' AND `module`='bx_forum' AND `name`='video_record_video';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_entry_attachments' AND `module`='bx_forum' AND `name`='file_html5';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_entry_attachments' AND `module`='bx_forum' AND `name`='poll';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_entry_attachments' AND `module`='bx_forum' AND `name`='add-link';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view' AND `module`='bx_forum' AND `name`='resolve-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view' AND `module`='bx_forum' AND `name`='stick-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view' AND `module`='bx_forum' AND `name`='lock-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view' AND `module`='bx_forum' AND `name`='hide-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view' AND `module`='bx_forum' AND `name`='approve';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view' AND `module`='bx_forum' AND `name`='more';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view_more' AND `module`='bx_forum' AND `name`='unresolve-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view_more' AND `module`='bx_forum' AND `name`='unstick-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view_more' AND `module`='bx_forum' AND `name`='unlock-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view_more' AND `module`='bx_forum' AND `name`='unhide-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view_more' AND `module`='bx_forum' AND `name`='edit-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view_more' AND `module`='bx_forum' AND `name`='delete-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view_actions' AND `module`='bx_forum' AND `name`='edit-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_view_actions' AND `module`='bx_forum' AND `name`='delete-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_my' AND `module`='bx_forum' AND `name`='create-discussion';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_submenu' AND `module`='bx_forum' AND `name`='discussions-home';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_submenu' AND `module`='bx_forum' AND `name`='discussions-search';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_submenu' AND `module`='bx_forum' AND `name`='discussions-manage';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_snippet_meta_main' AND `module`='bx_forum' AND `name`='score';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_snippet_meta_main' AND `module`='bx_forum' AND `name`='comments';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_snippet_meta_main' AND `module`='bx_forum' AND `name`='status';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_snippet_meta_counters' AND `module`='bx_forum' AND `name`='views';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_snippet_meta_counters' AND `module`='bx_forum' AND `name`='votes';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_snippet_meta_counters' AND `module`='bx_forum' AND `name`='comments';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_snippet_meta_reply' AND `module`='bx_forum' AND `name`='reply-author';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_snippet_meta_reply' AND `module`='bx_forum' AND `name`='reply-date';
UPDATE `sys_menu_items` SET `active_api`=1 WHERE `set_name`='bx_forum_snippet_meta_reply' AND `module`='bx_forum' AND `name`='reply-text';
