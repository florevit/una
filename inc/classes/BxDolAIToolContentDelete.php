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
use NeuronAI\Tools\ArrayProperty;
use NeuronAI\Tools\ObjectProperty;

class BxDolAIToolContentChange extends Tool
{
    public function __construct()
    {
        parent::__construct(
            'content_delete',
            'Use this tool to delete content. Available modules: ' . bx_srv('system', 'modules_list', [true, true], 'TemplServiceContent') . '.',
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'module',
                type: PropertyType::STRING,
                description: 'The module name to perform content changes on. Use "system" for site accounts. ',
                required: true
            ),
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'Content ID to delete.',
                required: true
            ),
        ];
    }

    public function __invoke(string $module, int $content_id = 0): string
    {        
        $a = bx_srv('system', 'delete', [$module, $content_id], 'TemplServiceContent');
        return json_encode($a);
    }
}
