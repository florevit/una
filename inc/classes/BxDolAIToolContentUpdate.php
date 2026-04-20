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

class BxDolAIToolContentUpdate extends Tool
{
    public function __construct()
    {
        parent::__construct(
            'content_update',
            'Use this tool to update content. Always use "content_structure" tool to get knowledge about content modules fields. Available modules: ' . bx_srv('system', 'modules_list', [true, true], 'TemplServiceContent') . '.',
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
                description: 'Content ID to update.',
                required: true
            ),
            new ToolProperty(
                name: 'data',
                type: PropertyType::OBJECT,
                description: 'The data to update, as key-value pairs.',
                required: true
            ),
            new ToolProperty(
                name: 'profile_id',
                type: PropertyType::INTEGER,
                description: 'Author profile ID.',
                required: true
            )
        ];
    }

    public function __invoke(string $module, int $content_id, array $data, int $profile_id): string
    {
        if ($profile_id) {
            $GLOBALS['glForceCurrentProfileId'] = $profile_id;
        }

        $a = bx_srv('system', 'update', [$module, $content_id, $data], 'TemplServiceContent');

        $GLOBALS['glForceCurrentProfileId'] = 0;

        return $a;
    }
}
