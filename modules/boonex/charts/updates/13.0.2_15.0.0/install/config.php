<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Charts',
    'version_from' => '13.0.2',
    'version_to' => '15.0.0',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '15.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/charts/updates/update_13.0.2_15.0.0/',
    'home_uri' => 'charts_update_1302_1500',

    'module_dir' => 'boonex/charts/',
    'module_uri' => 'charts',

    'db_prefix' => 'bx_charts_',
    'class_prefix' => 'BxCharts',

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
    'language_category' => 'Charts',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
