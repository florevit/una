<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Snipcart',
    'version_from' => '13.0.4',
    'version_to' => '15.0.0',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '15.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/snipcart/updates/update_13.0.4_15.0.0/',
    'home_uri' => 'snipcart_update_1304_1500',

    'module_dir' => 'boonex/snipcart/',
    'module_uri' => 'snipcart',

    'db_prefix' => 'bx_snipcart_',
    'class_prefix' => 'BxSnipcart',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Snipcart',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
