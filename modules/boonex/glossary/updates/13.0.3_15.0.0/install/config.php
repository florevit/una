<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Glossary',
    'version_from' => '13.0.3',
    'version_to' => '15.0.0',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '15.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/glossary/updates/update_13.0.3_15.0.0/',
    'home_uri' => 'glossary_update_1303_1500',

    'module_dir' => 'boonex/glossary/',
    'module_uri' => 'glossary',

    'db_prefix' => 'bx_glossary_',
    'class_prefix' => 'BxGlsr',

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
    'language_category' => 'Glossary',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
