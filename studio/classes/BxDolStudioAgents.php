<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

define('BX_DOL_STUDIO_AGENTS_TYPE_SETTINGS', 'settings');
define('BX_DOL_STUDIO_AGENTS_TYPE_ASSISTANTS', 'assistants');

define('BX_DOL_STUDIO_AGENTS_TYPE_AI_PROVIDERS', 'ai_providers');
define('BX_DOL_STUDIO_AGENTS_TYPE_TOOLS', 'tools');
define('BX_DOL_STUDIO_AGENTS_TYPE_EMBEDDING_PROVIDERS', 'embedding_providers');
define('BX_DOL_STUDIO_AGENTS_TYPE_VECTOR_STORE', 'vector_store');
define('BX_DOL_STUDIO_AGENTS_TYPE_AGENTS', 'agents');

/*
 * Isn't used for now. Most probably they will be removed.
 */
define('BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS', 'automators');
define('BX_DOL_STUDIO_AGENTS_TYPE_PROVIDERS', 'providers');
define('BX_DOL_STUDIO_AGENTS_TYPE_HELPERS', 'helpers');

define('BX_DOL_STUDIO_AGENTS_TYPE_DEFAULT', BX_DOL_STUDIO_AGENTS_TYPE_SETTINGS);

class BxDolStudioAgents extends BxTemplStudioWidget
{
    protected $sPage;

    function __construct($sPage = "")
    {
        parent::__construct('agents');

        $this->sPage = BX_DOL_STUDIO_AGENTS_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;
    }
}

/** @} */
