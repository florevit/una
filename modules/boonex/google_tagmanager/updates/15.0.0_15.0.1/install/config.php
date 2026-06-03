<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Google Tag Manager',
    'version_from' => '15.0.0',
    'version_to' => '15.0.1',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '15.0.0-B2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/google_tagmanager/updates/update_15.0.0_15.0.1/',
    'home_uri' => 'google_tagmanager_update_1500_1501',

    'module_dir' => 'boonex/google_tagmanager/',
    'module_uri' => 'google_tagmanager',

    'db_prefix' => 'bx_googletagman_',
    'class_prefix' => 'BxGoogleTagMan',

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
    'language_category' => 'Google Tag Manager',
    
    /**
     * Files Section
     */
    'delete_files' => array(),
);
