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
            'Use this tool to add, update or delete content. Always use "content_structure" tool if it\'s available to get knowledge about content modules fields for "add" and "update" actions. ALL module fields MUST be placed inside data - no exceptions, flat fields are invalid. Available modules: ' . self::getModules() . '.',
        );
    }
    
    static public function getModules(): string
    {
        $aModules = $aContentModules = bx_srv('system', 'modules_list', [true], 'TemplServiceContent');
        return implode(',', array_keys($aModules));
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
                description: 'The ID of the content item to delete or update, use 0 for creating new content, never pass 0 for update and delete actions.',
                required: true
            ),
            new ToolProperty(
                name: 'data',
                type: PropertyType::OBJECT,
                description: 'The data to update the content item with, as key-value pairs, where key is the field name and value is the value. Skip this parameter for delete action, for "create" and "update" actions this is mandatory.',
                required: false
            ),
            new ToolProperty(
                name: 'profile_id',
                type: PropertyType::INTEGER,
                description: 'The ID of the profile to perform the action on behalf of.',
                required: false
            )
        ];
    }

    public function __invoke(string $action, string $module, ?int $content_id = 0, ?array $data = null, ?int $profile_id = 0): string
    {
        if ($profile_id) {
            $GLOBALS['glForceCurrentProfileId'] = $profile_id;
        }

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
                $a = ['code' => 400, 'error' => 'Error: Invalid action'];
        }

        $GLOBALS['glForceCurrentProfileId'] = 0;

        return json_encode($a);
    }
}
