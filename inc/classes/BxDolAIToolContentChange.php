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
            'content_change',
            'Use this tool to add, update or delete content.  If needed, use info from "content_structure" tool to get knowledge about content modules fields for add and update actions.',
        );
    }
    
    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'action',
                type: PropertyType::STRING,
                description: 'The action to perform on the content. Options are "update", "create" or "delete". Returns array with code = 0 on success, or array with code != 0 and error message on failure.',
                required: true
            ),
            new ToolProperty(
                name: 'module',
                type: PropertyType::STRING,
                description: 'The module name to perform content changes on. Use "system" for site accounts. ',
                required: true
            ),
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'The ID of the content item to delete or update, use 0 for creating new content.',
                required: true
            ),
            new ToolProperty(
                name: 'data',
                type: PropertyType::OBJECT,
                description: 'The data to update the content item with, as key-value pairs, where key is the field name and value is the value. Skip this parameter for delete action, for create and update actions this is required.',
                required: false
            )
        ];
    }

    public function __invoke(string $action, string $module, ?int $content_id = 0, ?array $data = null): string
    {
        echoDbgLog("content_change tool called with action: {$action}, module: {$module}, content_id: {$content_id}, data: " . json_encode($data));
        switch ($action) {
            case 'update':
                $a = bx_srv('system', 'update', [$module, $content_id, $data], 'TemplServiceContent');
                break;
            case 'create':
                $a = bx_srv('system', 'add', [$module, $data], 'TemplServiceContent');
                break;
            case 'delete':
                $a = bx_srv('system', 'delete', [$module, $content_id], 'TemplServiceContent');
                break;
            default:
                return "Error: Invalid action";
        }

        return json_encode($a);
    }
}
