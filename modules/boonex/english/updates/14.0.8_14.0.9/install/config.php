<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'English',
    'version_from' => '14.0.8',
    'version_to' => '14.0.9',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '14.0.0-RC5'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/english/updates/update_14.0.8_14.0.9/',
    'home_uri' => 'en_update_1408_1409',

    'module_dir' => 'boonex/english/',
    'module_uri' => 'en',

    'db_prefix' => 'bx_eng_',
    'class_prefix' => 'BxEng',

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
    'language_category' => 'System',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
