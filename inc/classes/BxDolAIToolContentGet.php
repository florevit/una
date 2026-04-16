<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;

class BxDolAIToolContentGet extends Tool
{
    public function __construct()
    {
        parent::__construct(
            'get_content_info',
            'Retrieve content info for a given module and content id.',
        );
    }
    
    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'module',
                type: PropertyType::STRING,
                description: 'The module name to retrieve content info from. Use "system" for site accounts. ',
                required: true
            ),
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'The ID of the content item to retrieve info for.',
                required: true
            )
        ];
    }

    public function __invoke(string $module, int $content_id): string
    {
        $a = bx_srv('system', 'get_info', [$module, $content_id], 'TemplServiceContent');
        if (!$a)
            return "Error: content item was not found";

        return json_encode($a);
    }
}
