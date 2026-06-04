<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Russian',
    'version_from' => '15.0.2',
    'version_to' => '15.0.3',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '15.0.0-B2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_15.0.2_15.0.3/',
    'home_uri' => 'ru_update_1502_1503',

    'module_dir' => 'boonex/russian/',
    'module_uri' => 'ru',

    'db_prefix' => 'bx_rsn_',
    'class_prefix' => 'BxRsn',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 1,
        'restore_languages' => 0,
        'clear_db_cache' => 0,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => array(
        array('name' => 'Paid Levels', 'path' => 'bx_acl/'),
        array('name' => 'Ads', 'path' => 'bx_ads/'),
        array('name' => 'Albums', 'path' => 'bx_albums/'),
        array('name' => 'Antispam', 'path' => 'bx_antispam/'),
        array('name' => 'BoonEx Developer', 'path' => 'bx_developer/'),
        array('name' => 'Events', 'path' => 'bx_events/'),
        array('name' => 'Files', 'path' => 'bx_files/'),
        array('name' => 'Discussions', 'path' => 'bx_forum/'),
        array('name' => 'Glossary', 'path' => 'bx_glossary/'),
        array('name' => 'Market', 'path' => 'bx_market/'),
        array('name' => 'MassMailer', 'path' => 'bx_massmailer/'),
        array('name' => 'Notifications', 'path' => 'bx_notifications/'),
        array('name' => 'Organizations', 'path' => 'bx_organizations/'),
        array('name' => 'Payment', 'path' => 'bx_payment/'),
        array('name' => 'Persons', 'path' => 'bx_persons/'),
        array('name' => 'Posts', 'path' => 'bx_posts/'),
        array('name' => 'Spaces', 'path' => 'bx_spaces/'),
        array('name' => 'Tasks', 'path' => 'bx_tasks/'),
        array('name' => 'Videos', 'path' => 'bx_videos/'),
        array('name' => 'System', 'path' => 'system/'),
    ),

    /**
     * Files Section
     */
    'delete_files' => array(),
);
