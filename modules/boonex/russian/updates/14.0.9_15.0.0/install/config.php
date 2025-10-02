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
    'version_from' => '14.0.9',
    'version_to' => '15.0.0',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '15.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_14.0.9_15.0.0/',
    'home_uri' => 'ru_update_1409_1500',

    'module_dir' => 'boonex/russian/',
    'module_uri' => 'ru',

    'db_prefix' => 'bx_rsn_',
    'class_prefix' => 'BxRsn',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'restore_languages' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => array(
        array('name' => 'Paid Levels', 'path' => 'bx_acl/'),
        array('name' => 'Boonex Artificer Template', 'path' => 'bx_artificer/'),
        array('name' => 'Channels', 'path' => 'bx_channels/'),
        array('name' => 'Courses', 'path' => 'bx_courses/'),
        array('name' => 'BoonEx Developer', 'path' => 'bx_developer/'),
        array('name' => 'BoonEx English', 'path' => 'bx_en/'),
        array('name' => 'Events', 'path' => 'bx_events/'),
        array('name' => 'Discussions', 'path' => 'bx_forum/'),
        array('name' => 'Groups', 'path' => 'bx_groups/'),
        array('name' => 'Invitations', 'path' => 'bx_invites/'),
        array('name' => 'Notifications', 'path' => 'bx_notifications/'),
        array('name' => 'Organizations', 'path' => 'bx_organizations/'),
        array('name' => 'Payment', 'path' => 'bx_payment/'),
        array('name' => 'Persons', 'path' => 'bx_persons/'),
        array('name' => 'Posts', 'path' => 'bx_posts/'),
        array('name' => 'Boonex Protean Template', 'path' => 'bx_protean/'),
        array('name' => 'Quote Of Day', 'path' => 'bx_quoteofday/'),
        array('name' => 'BoonEx Russian', 'path' => 'bx_ru/'),
        array('name' => 'Spaces', 'path' => 'bx_spaces/'),
        array('name' => 'Stripe Connect', 'path' => 'bx_stripe_connect/'),
        array('name' => 'Tasks', 'path' => 'bx_tasks/'),
        array('name' => 'Timeline', 'path' => 'bx_timeline/'),
        array('name' => 'Videos', 'path' => 'bx_videos/'),
        array('name' => 'System', 'path' => 'system/'),
    ),

    /**
     * Files Section
     */
    'delete_files' => array(),
);
