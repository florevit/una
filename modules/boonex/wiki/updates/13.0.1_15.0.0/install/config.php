<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Wiki',
    'version_from' => '13.0.1',
    'version_to' => '15.0.0',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '15.0.0-B2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/wiki/updates/update_13.0.1_15.0.0/',
    'home_uri' => 'wiki_update_1301_1500',

    'module_dir' => 'boonex/wiki/',
    'module_uri' => 'wiki',

    'db_prefix' => 'bx_wiki_',
    'class_prefix' => 'BxWiki',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 0,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Wiki',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
