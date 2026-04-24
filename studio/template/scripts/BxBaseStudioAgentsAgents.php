<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgentsAgents extends BxDolStudioAgentsAgents
{
    protected $_sUrlPage;
    protected $_sFieldName;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sUrlPage = BX_DOL_URL_STUDIO . 'agents.php?page=agents';

        $this->_sFieldName = 'name';
    }

    public function getPageJsObject()
    {
        return 'oBxDolStudioPageAgents';
    }

    public function performActionGetAlert()
    {
        $aAlert = $this->_getAlertPayload(bx_get('alert'));
        echoJson([
            'code' => $aAlert ? 200 : 404,
            'desc' => $aAlert ? $this->_oDb->getAlertDesc(bx_get('alert')) : '',
            'payload' => $aAlert,
        ]);
    }

    public function performActionManual()
    {
        $iId = $this->_getId();        
        $aAgent = BxDolAiQuery::getAgentObject($iId);
        if (!$aAgent) {
            echoJson(['msg' => _t('_sys_txt_error_occured')]);
            return;
        }
        if (!$aAgent['active']) {
            echoJson(['msg' => _t('_sys_txt_agent_inactive')]);
            return;
        }

        $oAi = BxDolAI::getInstance();
        $sResponse = $oAi->callAgent('manual', $aAgent);

        $oParsedown = new Parsedown();
        $oParsedown->setSafeMode(true);
        $sMessageHtml = $oParsedown->text($sResponse);

        $oTemplate = BxDolStudioTemplate::getInstance();
        $sHtml = $oTemplate->parseHtmlByName('agents_manual_response.html', [
            'response' => $sMessageHtml
        ]);
        echoJson(['msg' => $sHtml]);
    }

    public function performActionWipeChatHistory()
    {
        $iId = $this->_getId();        
        $aAgent = BxDolAiQuery::getAgentObject($iId);
        if (!$aAgent) {
            echoJson(['msg' => _t('_sys_txt_error_occured')]);
            return;
        }

        $oDb = new BxDolAIQuery();
        $oDb->wipeAgentChatHistory($aAgent);

        $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
        echoJson($aRes);
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $oTemplate = BxDolStudioTemplate::getInstance();
        $oAI = BxDolAI::getInstance();

        $aForm = $this->_getForm($sAction);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if ($oForm->isSubmitted() && $oForm->getCleanValue('trigger') == 'message') {
            $aAgent = $this->_getAgentWithProfile($oForm->getCleanValue('profile_id'));
            if ($aAgent) {
                 $oForm->aInputs['profile_id']['error'] = _t('_sys_agents_form_field_err_profile_has_agent', $aAgent['name']);
                 $oForm->setValid(false);
            }
        }

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [
                'added' => time()
            ];

            $sName = $oForm->getCleanValue($this->_sFieldName);
            $sName = $this->_getUniqName($sName);
            BxDolForm::setSubmittedValue($this->_sFieldName, $sName, $oForm->aFormAttrs['method']);

            $iProfileId = $oForm->getCleanValue('profile_id');
            if(empty($iProfileId)) {
                $iProfileId = (int)getParam('sys_agents_profile');
                if(empty($iProfileId))
                    $iProfileId = current(bx_srv('system', 'get_options_agents_profile', [false], 'TemplServices'))['key'];

                $aValsToAdd['profile_id'] = $iProfileId;
            }
  
            $iModel = $oForm->getCleanValue('model_id');
            $sTrigger = $oForm->getCleanValue('trigger');

            $sTools = is_array($oForm->getCleanValue('tools')) ? implode(',', $oForm->getCleanValue('tools')) : '';
            $aValsToAdd['tools'] = $sTools;

            $bIsValid = true;
            if($bIsValid) {
                if(($iId = $oForm->insert($aValsToAdd)) !== false) {
                    $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
                }
                else
                    $aRes = ['msg' => _t('_sys_txt_error_occured')];

                return echoJson($aRes);
            }
        }

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_agents_popup_add'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
            'form_id' => $sFormId,
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionEdit()
    {
        $sAction = 'edit';

        $oTemplate = BxDolStudioTemplate::getInstance();
        $oAI = BxDolAI::getInstance();

        $iId = $this->_getId();
        $aAgent = BxDolAiQuery::getAgentObject($iId);

        $aForm = $this->_getFormEdit($sAction, $aAgent);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if ($oForm->isSubmitted() && $oForm->getCleanValue('trigger') == 'message') {
            $aAgent = $this->_getAgentWithProfile($oForm->getCleanValue('profile_id'), $iId);
            if ($aAgent) {
                 $oForm->aInputs['profile_id']['error'] = _t('_sys_agents_form_field_err_profile_has_agent', $aAgent['name']);
                 $oForm->setValid(false);
            }
        }

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [];

            $sName = $oForm->getCleanValue($this->_sFieldName);
            $sName = $this->_getUniqName($sName);
            BxDolForm::setSubmittedValue($this->_sFieldName, $sName, $oForm->aFormAttrs['method']);

            $iProfileId = $oForm->getCleanValue('profile_id');
            if(empty($iProfileId)) {
                $iProfileId = (int)getParam('sys_agents_profile');
                if(empty($iProfileId))
                    $iProfileId = current(bx_srv('system', 'get_options_agents_profile', [false], 'TemplServices'))['key'];

                $aValsToAdd['profile_id'] = $iProfileId;
            }
  
            $iModel = $oForm->getCleanValue('model_id');
            $sTrigger = $oForm->getCleanValue('trigger');

            $sTools = is_array($oForm->getCleanValue('tools')) ? implode(',', $oForm->getCleanValue('tools')) : '';
            $aValsToAdd['tools'] = $sTools;

            $bIsValid = true;
            if($bIsValid) {
                if(($iId = $oForm->update($iId, $aValsToAdd)) !== false) {
                    $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
                }
                else
                    $aRes = ['msg' => _t('_sys_txt_error_occured')];

                return echoJson($aRes);
            }
        } 

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_agents_popup_edit', $aAgent['name']), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
            'form_id' => $sFormId,
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionLogs()
    {
        $iId = $this->_getId();
        $aAgent = BxDolAiQuery::getAgentObject($iId);
        if (!$aAgent) {
            echoJson(['msg' => _t('_sys_txt_error_occured')]);
            return;
        }

        $oGrid = BxDolGrid::getObjectInstance('sys_studio_agents_logs');
        $oGrid->addMarkers(['agent_id' => $iId]);
        $oGrid->setBrowseParams(['agent_id' => $iId]);
        $sGrid = $oGrid->getCode();

        $sContent = BxTemplStudioFunctions::getInstance()->popupBox('popup_files_' . $iId, _t('_sys_agents_logs_popup', $aAgent['name']), $this->_oTemplate->parseHtmlByName('agents_popup_grid.html', [
            'grid' => $sGrid,
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    protected function _getCellSwitcher ($mixedValue, $sKey, $aField, $aRow)
    {
        // if(empty($aRow['code']) || $aRow['status'] != BX_DOL_AI_AUTOMATOR_STATUS_READY)
        //    return parent::_getCellDefault('', $sKey, $aField, $aRow);

        return parent::_getCellSwitcher ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellType($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _delete ($mixedId)
    {
        $mixedResult = parent::_delete($mixedId);
        if($mixedResult) {
            // $this->_oDb->deleteAutomatorProviders(['automator_id' => (int)$mixedId]);
            // $this->_oDb->deleteAutomatorHelpers(['automator_id' => (int)$mixedId]);
            // $this->_oDb->deleteAutomatorAssistants(['automator_id' => (int)$mixedId]);

            // if(($oCmts = BxDolAI::getInstance()->getAutomatorCmtsObject($mixedId)) !== false)
            //    $oCmts->onObjectDelete();
        }

        return $mixedResult;
    }

    protected function _getFormEdit($sAction, $aAgent = [])
    {
        $aForm = $this->_getForm($sAction, $aAgent);
        $aForm['form_attrs']['action'] .= '&id=' . $aAgent['id'];

        unset($aForm['inputs']['name']);

        return $aForm;
    }

    protected function _getForm($sAction, $aAgent = [])
    {
        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx_std_agents_' . $sAction,
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=sys_studio_agents_agents&a=' . $sAction,
                'method' => 'post',
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_agents_agents',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => array(
                'name' => [
                    'type' => 'text',
                    'name' => 'name',
                    'caption' => _t('_sys_agents_field_name'),
                    'info' => _t('_sys_agents_field_name_info'),
                    'value' => isset($aAgent['name']) ? $aAgent['name'] : '',
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'error' => _t('_sys_agents_form_field_err_enter'),
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'model_id' => [
                    'type' => 'select',
                    'name' => 'model_id',
                    'caption' => _t('_sys_agents_field_model_id'),
                    'info' => _t('_sys_agents_field_model_id_info'),
                    'value' => isset($aAgent['model_id']) ? $aAgent['model_id'] : BxDolAI::getDefaultModel(),
                    'values' => $this->_oDb->getModelsBy(['sample' => 'all_pairs', 'active' => 1, 'capabilities' => ['chatvlm', 'chatllm']]),
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'error' => _t('_sys_agents_form_field_err_select'),
                    ],
                    'db' => [
                        'pass' => 'Int',
                    ],
                ],
                'profile_id' => [
                    'type' => 'select',
                    'name' => 'profile_id',
                    'caption' => _t('_sys_agents_field_profile_id'),
                    'info' => _t('_sys_agents_field_profile_id_inf'),
                    'value' => isset($aAgent['profile_id']) ? $aAgent['profile_id'] : 0,
                    'values' => bx_srv('system', 'get_options_agents_profile', [], 'TemplServices'),
                    'required' => '1',
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'error' => _t('_sys_agents_form_field_err_select'),
                    ],
                    'db' => [
                        'pass' => 'Int',
                    ]
                ],
                'trigger' => [
                    'type' => 'select',
                    'name' => 'trigger',
                    'caption' => _t('_sys_agents_field_trigger'),
                    'info' => _t('_sys_agents_field_trigger_info'),                    
                    'value' => isset($aAgent['trigger']) ? $aAgent['trigger'] : 0,
                    'values' => [
                        'alert' => 'alert',
                        'scheduler' => 'scheduler',
                        'webhook' => 'webhook',
                        'manual' => 'manual',
                        'agent' => 'agent',
                        'message' => 'message',
                    ],
                    'attrs' => [
                        'onchange' => $this->getPageJsObject() . '.onChangeAgentTrigger(this)',
                    ],
                    'required' => '1',
                    'db' => [
                        'pass' => 'Xss',
                    ]
                ],

                'alert_section' => array(
                    'type' => 'block_header',
                    'caption' => 'Trigger - alert',
                    'collapsable' => true,
                    'collapsed' => true,
                ),

                'alert_sample' => [
                    'type' => 'custom',
                    'name' => 'alert_sample',
                    'caption' => _t('_sys_agents_field_alert_sample'),
                    'content' => $this->_oTemplate->parseHtmlByName('agents_agents_alerts_payload.html', [
                        'text-visibility' => empty($aAgent['alert']) ? 'block' : 'none',
                        'text' => _t('_sys_agents_waiting_for_sample_data'),

                        'payload-visibility' => !empty($aAgent['alert']) ? 'block' : 'none',
                        'desc' => $aAgent ? $this->_oDb->getAlertDesc($aAgent['alert']) : '',
                        'payload' => !empty($aAgent['alert']) ? json_encode($this->_getAlertPayload($aAgent['alert']), JSON_PRETTY_PRINT) : '',
                    ]),
                ],

                'alert' => [
                    'type' => 'custom',
                    'name' => 'alert',
                    'caption' => _t('_sys_agents_field_alert'),
                    'info' => _t('_sys_agents_field_alert_info'),
                    'content' => $this->_oTemplate->parseHtmlByName('agents_agents_alerts_select.html', $this->_getAlertValues(isset($aAgent['alert']) ? $aAgent['alert'] : '')),
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],

                'scheduler_section' => [
                    'type' => 'block_header',
                    'caption' => 'Trigger - scheduler',
                    'collapsable' => true,
                    'collapsed' => true,
                ],

                'scheduler_cron' => [
                    'type' => 'text',
                    'name' => 'scheduler_cron',
                    'caption' => _t('_sys_agents_field_scheduler_cron'),
                    'info' => _t('_sys_agents_field_scheduler_cron_info'),
                    'value' => isset($aAgent['scheduler_cron']) ? $aAgent['scheduler_cron'] : '',
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],

                'webhook_section' => array(
                    'type' => 'block_header',
                    'caption' => 'Trigger - webhook',
                    'collapsable' => true,
                    'collapsed' => true,
                ),

                'webhook_sample' => [
                    'type' => 'custom',
                    'name' => 'webhook_sample',
                    'caption' => _t('_sys_agents_field_webhook_sample'),
                    'content' => isset($aAgent['webhook_sample']) ? $aAgent['webhook_sample'] : _t('_sys_agents_waiting_for_sample_data'),
                ],
                'webhook_key' => [
                    'type' => 'text',
                    'name' => 'webhook_key',
                    'caption' => _t('_sys_agents_field_webhook_key'),
                    'info' => _t('_sys_agents_field_webhook_key_info'),
                    'value' => isset($aAgent['webhook_key']) ? $aAgent['webhook_key'] : '',
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],

                'message_section' => array(
                    'type' => 'block_header',
                    'caption' => 'Trigger - message',
                    'collapsable' => true,
                    'collapsed' => true,
                ),

                'message_profile_id' => [
                    'type' => 'select',
                    'name' => 'message_profile_id',
                    'caption' => _t('_sys_agents_field_message_profile'),
                    'info' => _t('_sys_agents_field_message_profile_info'),
                    'value' => isset($aAgent['message_profile_id']) ? $aAgent['message_profile_id'] : '',
                    'values' => bx_srv('system', 'get_options_agents_profile', [true, '_adm_nav_txt_items_visible_for_all'], 'TemplServices'),
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],

                'message_section_end' => array(
                    'type' => 'block_end',
                ),

                'vector_store_id' => [
                    'type' => 'select',
                    'name' => 'vector_store_id',
                    'caption' => _t('_sys_agents_field_vector_store_id'),
                    'info' => _t('_sys_agents_field_vector_store_id_info'),
                    'value' => isset($aAgent['vector_store_id']) ? $aAgent['vector_store_id'] : 0,
                    'values' => $this->_oDb->getVectorStores(),
                    'required' => '1',
                    'checker' => [
                        'func' => 'Avail',
                        'error' => _t('_sys_agents_form_field_err_select'),
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'chat_history_context' => [
                    'type' => 'text',
                    'name' => 'chat_history_context',
                    'caption' => _t('_sys_agents_field_chat_history_context'),
                    'info' => _t('_sys_agents_field_chat_history_context_info'),
                    'value' => isset($aAgent['chat_history_context']) ? $aAgent['chat_history_context'] : 50000,
                    'db' => [
                        'pass' => 'Int',
                    ],
                ],
                'tools' => [
                    'type' => 'custom',
                    'name' => 'tools',
                    'caption' => _t('_sys_agents_field_tools'),
                    'info' => _t('_sys_agents_field_tools_info'),
                    'value' => '',
                ],
                'prompt_system' => [
                    'type' => 'textarea',
                    'name' => 'prompt_system',
                    'caption' => _t('_sys_agents_field_prompt_system'),
                    'info' => _t('_sys_agents_field_prompt_system_info'),
                    'value' => isset($aAgent['prompt_system']) ? $aAgent['prompt_system'] : '',
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],

                'prompts_section' => array(
                    'type' => 'block_header',
                    'caption' => 'Other prompts',
                    'collapsable' => true,
                    'collapsed' => true,
                ),

                'prompt_steps' => [
                    'type' => 'textarea',
                    'name' => 'prompt_steps',
                    'caption' => _t('_sys_agents_field_prompt_steps'),
                    'info' => _t('_sys_agents_field_prompt_steps_info'),
                    'value' => isset($aAgent['prompt_steps']) ? $aAgent['prompt_steps'] : '',
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'prompt_output' => [
                    'type' => 'textarea',
                    'name' => 'prompt_output',
                    'caption' => _t('_sys_agents_field_prompt_output'),
                    'info' => _t('_sys_agents_field_prompt_output_info'),
                    'value' => isset($aAgent['prompt_output']) ? $aAgent['prompt_output'] : '',
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'prompt_tools' => [
                    'type' => 'textarea',
                    'name' => 'prompt_tools',
                    'caption' => _t('_sys_agents_field_prompt_tools'),
                    'info' => _t('_sys_agents_field_prompt_tools_info'),
                    'value' => isset($aAgent['prompt_tools']) ? $aAgent['prompt_tools'] : '',
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'prompts_section_end' => array(
                    'type' => 'block_end',
                ),
                'submit' => $this->_getFormControls(),

            ),
        );
        
        $this->_getMultiField('tools', $aAgent, 'getTools', 'toolAdd', 'agents_agents_form_tools.html', $aForm);

        return $aForm;
    }

    protected function _getAlertPayload($s)
    {
        $aAlert = $this->_oDb->getAlert($s);
        if (!$aAlert)
            return '';
        return [
            'object_id' => $aAlert['object'],
            'sender_profile_id' => $aAlert['sender'],            
            'extra' => json_decode($aAlert['extra'], true),
            'extra_modifiable_keys' => json_decode($aAlert['extra_refs'], true),
        ];
    }

    protected function _getAlertValues($sValue)
    {
        $aAlerts = $this->_oDb->getAlerts();
        $a = [
            'grid_object' => $this->_sObject,
            'bx_repeat:alerts' => [
                [
                    'name' => _t('_sys_please_select'), 
                    'value' => '', 
                    'desc' => '', 
                    'bx_if:sel' => [
                        'condition' => false,
                        'content' => ['sel' => '']
                    ]
                ]
            ]
        ];
        foreach ($aAlerts as $k => $r) {
            $a['bx_repeat:alerts'][] = [
                'name' => $r['name'],
                'value' => $r['key'],
                'desc' => bx_html_attribute($r['desc']),
                'bx_if:sel' => [
                    'condition' => $sValue == $r['key'],
                    'content' => [
                        'sel' => $sValue == $r['key'] ? 'selected' : '',
                    ]
                ]
            ];
        }
        return $a;
    }

    protected function _getUniqName($sName)
    {
        return uriGenerate($sName, 'sys_agents_automators', 'name', ['lowercase' => false]);
    }

    protected function _getMultiField($sField, $aAgent, $sGetValuesMethod, $sJsAddMethod, $sTemplateName, &$aForm)
    {
        if(!isset($aForm['inputs']['tools'])) 
            return;

        $sJsObject = $this->getPageJsObject();

        $oForm = new BxTemplFormView([]);

        $aValues = ['0' => _t('_sys_please_select')];
        $a = $this->_oDb->$sGetValuesMethod();
        foreach ($a as $k => $v) {
            $aValues[$k] = $v;
        }

        $aTmplVars = [];
        if(!empty($aAgent[$sField])) {
            $a = explode(',', $aAgent[$sField]);
            foreach($a as $v) {
                $aInputSelect = [
                    'type' => 'select',
                    'name' => $sField . '[]',
                    'values' => $aValues,
                    'value' => $v,
                    'attrs' => [
                        'class' => 'bx-def-margin-sec-top-auto'
                    ]
                ];
                $sInput = $oForm->genInput($aInputSelect);

                $aInputHidden = [
                    'type' => 'hidden',
                    'name' => $sField . '_ids[]',
                    'value' => $v,
                ];
                $sInput .= $oForm->genInput($aInputHidden);

                $aTmplVars[] = ['js_object' => $sJsObject, 'input_select' => $sInput];
            }
        }
        else  {
            $aInputSelect = [
                'type' => 'select',
                'name' => $sField . '[]',
                'values' => $aValues,
                'value' => '',
                'attrs' => [
                    'class' => 'bx-def-margin-sec-top-auto'
                ]
            ];

            $aTmplVars = [
                ['js_object' => $sJsObject, 'input_select' => $oForm->genInput($aInputSelect)],
            ];
        }
        
        $aInputButton = [
            'type' => 'button',
            'name' => $sField . '_add',
            'value' => _t('_adm_nav_btn_items_add'),
            'attrs' => [
                'class' => 'bx-def-margin-sec-top',
                'onclick' => $sJsObject . ".$sJsAddMethod(this, '" . $sField . "');"
            ]
        ];

        $aForm['inputs'][$sField]['content'] = $this->_oTemplate->parseHtmlByName($sTemplateName, [
            "bx_repeat:$sField" => $aTmplVars,
            'btn_add' => $oForm->genInputButton($aInputButton)
        ]);
    }

    protected function _getActionManual ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if ($aRow['trigger'] != 'manual' && $aRow['trigger'] != 'scheduler')
            return '';
        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getAgentWithProfile($iProfileId, $iExcludeAgentId = 0)
    {
        $aAgents = BxDolAi::getInstance()->getAgentsByProfileId($iProfileId);
        foreach($aAgents as $aAgent) {
            if($aAgent['id'] != $iExcludeAgentId)
                return $aAgent;
        }

        return null;
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oTemplate->addJs(['select2/js/select2.min.js']);
        $this->_oTemplate->addCss([BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'select2/css/|select2.min.css']);
    }
}

/** @} */
