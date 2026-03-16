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

    public function performActionAdd()
    {
        $sAction = 'add';

        $oTemplate = BxDolStudioTemplate::getInstance();
        $oAI = BxDolAI::getInstance();

        $aForm = $this->_getForm($sAction);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

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
        $aAutomator = $oAI->getAutomator($iId);
        $aAutomator['providers'] = $this->_oDb->getAutomatorsBy(['sample' => 'providers_by_id_pairs', 'id' => $iId]);
        $aAutomator['helpers'] = $this->_oDb->getAutomatorsBy(['sample' => 'helpers_by_id_pairs', 'id' => $iId]);

        $aForm = $this->_getFormEdit($sAction, $aAutomator);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValsToAdd = [];

            $sName = $oForm->getCleanValue($this->_sFieldName);
            if($aAutomator[$this->_sFieldName] != $sName) {
                $sName = $this->_getUniqName($sName);
                BxDolForm::setSubmittedValue($this->_sFieldName, $sName, $oForm->aFormAttrs['method']);
            }

            /**
             * Process Providers
             */
            $aProvidersIds = $oForm->getCleanValue('providers_ids');
            $bProvidersIds = !empty($aProvidersIds) && is_array($aProvidersIds);
            $aProvidersValues = $oForm->getCleanValue('providers');
            $bProvidersValues = !empty($aProvidersValues) && is_array($aProvidersValues);

            //--- Providers: Remove deleted
            if(!empty($aAutomator['providers']) && is_array($aAutomator['providers']))
                $this->_oDb->deleteAutomatorProvidersById(array_diff(array_keys($aAutomator['providers']), $bProvidersIds ? $aProvidersIds : []));

            //--- Providers: Update existed
            if($bProvidersIds)
                foreach($aProvidersIds as $iIndex => $iApId)
                    $this->_oDb->updateAutomatorProvider(['provider_id' => (int)$aProvidersValues[$iIndex]], ['id' => (int)$iApId]);

            //--- Providers: Add new
            $iProvidersIds = $bProvidersIds ? count($aProvidersIds) : 0;
            $iProvidersValues = $bProvidersValues ? count($aProvidersValues) : 0;
            if($iProvidersValues > $iProvidersIds) {
                $aProvidersValues = array_slice($aProvidersValues, $iProvidersIds);
                foreach($aProvidersValues as $iProvidersValue)
                    $this->_oDb->insertAutomatorProvider([
                        'automator_id' => $iId,
                        'provider_id' => (int)$iProvidersValue,
                    ]);
            }

            /**
             * Process Helpers
             */
            $aHelpersIds = $oForm->getCleanValue('helpers_ids');
            $bHelpersIds = !empty($aHelpersIds) && is_array($aHelpersIds);
            $aHelpersValues = $oForm->getCleanValue('helpers');
            $bHelpersValues = !empty($aHelpersValues) && is_array($aHelpersValues);

            //--- Helpers: Remove deleted
            if(!empty($aAutomator['helpers']) && is_array($aAutomator['helpers']))
                $this->_oDb->deleteAutomatorHelpersById(array_diff(array_keys($aAutomator['helpers']), $bHelpersIds ? $aHelpersIds : []));

            //--- Helpers: Update existed
            if($bHelpersIds)
                foreach($aHelpersIds as $iIndex => $iAhId)
                    $this->_oDb->updateAutomatorHelper(['helper_id' => (int)$aHelpersValues[$iIndex]], ['id' => (int)$iAhId]);

            //--- Helpers: Add new
            $iHelpersIds = $bHelpersIds ? count($aHelpersIds) : 0;
            $iHelpersValues = $bHelpersValues ? count($aHelpersValues) : 0;
            if($iHelpersValues > $iHelpersIds) {
                $aHelpersValues = array_slice($aHelpersValues, $iHelpersIds);
                foreach($aHelpersValues as $iHelpersValue)
                    $this->_oDb->insertAutomatorHelper([
                        'automator_id' => $iId,
                        'helper_id' => (int)$iHelpersValue,
                    ]);
            }

            /**
             * Process Assistants
             */
            $aAssistantsIds = $oForm->getCleanValue('assistants_ids');
            $bAssistantsIds = !empty($aAssistantsIds) && is_array($aAssistantsIds);
            $aAssistantsValues = $oForm->getCleanValue('assistants');
            $bAssistantsValues = !empty($aAssistantsValues) && is_array($aAssistantsValues);

            //--- Assistants: Remove deleted
            if(!empty($aAutomator['assistants']) && is_array($aAutomator['assistants']))
                $this->_oDb->deleteAutomatorAssistantsById(array_diff(array_keys($aAutomator['assistants']), $bAssistantsIds ? $aAssistantsIds : []));

            //--- Assistants: Update existed
            if($bAssistantsIds)
                foreach($aAssistantsIds as $iIndex => $iAhId)
                    $this->_oDb->updateAutomatorAssistant(['assistant_id' => (int)$aAssistantsValues[$iIndex]], ['id' => (int)$iAhId]);

            //--- Assistants: Add new
            $iAssistantsIds = $bAssistantsIds ? count($aAssistantsIds) : 0;
            $iAssistantsValues = $bAssistantsValues ? count($aAssistantsValues) : 0;
            if($iAssistantsValues > $iAssistantsIds) {
                $aAssistantsValues = array_slice($aAssistantsValues, $iAssistantsIds);
                foreach($aAssistantsValues as $iAssistantsValue)
                    $this->_oDb->insertAutomatorAssistant([
                        'automator_id' => $iId,
                        'assistant_id' => (int)$iAssistantsValue,
                    ]);
            }

            $iProfileId = $oForm->getCleanValue('profile_id');
            if(empty($iProfileId)) {
                $iProfileId = (int)getParam('sys_agents_profile');
                if(empty($iProfileId))
                    $iProfileId = current(bx_srv('system', 'get_options_agents_profile', [false], 'TemplServices'))['key'];

                $aValsToAdd['profile_id'] = $iProfileId;
            }

            $sSchedulerTime = $oForm->getCleanValue('scheduler_time');
            if(!empty($sSchedulerTime))
                $aValsToAdd['params'] = json_encode(['scheduler_time' => $sSchedulerTime]);

            if($oForm->update($iId, $aValsToAdd) !== false) {
                if(($oCmts = BxDolAI::getInstance()->getAutomatorCmtsObject($iId, $oTemplate)) !== null) {
                    $sInstructions = $oAI->getAutomatorInstruction('profile', $iProfileId);

                    $aProviders = $this->_oDb->getAutomatorsBy(['sample' => 'providers_by_id_pairs', 'id' => $iId]);
                    if(!empty($aProviders) && is_array($aProviders))
                        $sInstructions .= $oAI->getAutomatorInstruction('providers', array_values($aProviders));

                    $aHelpers = $this->_oDb->getAutomatorsBy(['sample' => 'helpers_by_id_pairs', 'id' => $iId]);
                    if(!empty($aHelpers) && is_array($aHelpers))
                        $sInstructions .= $oAI->getAutomatorInstruction('helpers', array_values($aHelpers));

                    $aAssistants = $this->_oDb->getAutomatorsBy(['sample' => 'assistants_by_id_pairs', 'id' => $iId]);
                    if(!empty($aAssistants) && is_array($aAssistants))
                        $sInstructions .= $oAI->getAutomatorInstruction('assistants', array_values($aAssistants));

                    $oCmts->addAuto([
                        'cmt_author_id' => $iProfileId,
                        'cmt_parent_id' => 0,
                        'cmt_text' => $sInstructions
                    ]);

                    if(($oMessage = new BxDolAIMessageString('hb', $sInstructions)) && ($sResponse = $oAI->getModelObject($aAutomator['model_id'])->getResponse($aAutomator['type'], $oMessage, $aAutomator['params'])) !== false) {
                        sleep(1);
                        $oCmts->addAuto([
                            'cmt_author_id' => $this->_iProfileIdAi,
                            'cmt_parent_id' => 0,
                            'cmt_text' => $sResponse
                        ]);
                    }
                }

                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            }
            else
                $aRes = ['msg' => _t('_sys_txt_error_occured')];

            return echoJson($aRes);
        } 

        $sFormId = $oForm->getId();
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup', _t('_sys_agents_popup_edit'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
            'form_id' => $sFormId,
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
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

        unset($aForm['inputs']['type']);
        unset($aForm['inputs']['message']);

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
                        'params' => [],
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
                        'params' => [],
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
                    'required' => '0',
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
                'alert_sample' => [
                    'type' => 'custom',
                    'name' => 'alert_sample',
                    'caption' => _t('_sys_agents_field_alert_sample'),
                    'content' => isset($aAgent['alert_sample']) ? $aAgent['alert_sample'] : _t('_sys_agents_waiting_for_sample_data'),
                    // 'tr_attrs' => [
                    //     'style' => $sType != 'alert' ? 'display:none' : ''
                    // ],
                ],
                'alert_unit' => [
                    'type' => 'text',
                    'name' => 'alert_unit',
                    'caption' => _t('_sys_agents_field_alert_unit'),
                    'info' => _t('_sys_agents_field_alert_unit_info'),
                    'value' => isset($aAgent['alert_unit']) ? $aAgent['alert_unit'] : '',
                    // 'tr_attrs' => [
                    //     'style' => $sType != 'alert' ? 'display:none' : ''
                    // ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'alert_action' => [
                    'type' => 'text',
                    'name' => 'alert_action',
                    'caption' => _t('_sys_agents_field_alert_action'),
                    'value' => isset($aAgent['alert_action']) ? $aAgent['alert_action'] : '',
                    // 'tr_attrs' => [
                    //    'style' => $sType != 'alert' ? 'display:none' : ''
                    // ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'scheduler_cron' => [
                    'type' => 'text',
                    'name' => 'scheduler_cron',
                    'caption' => _t('_sys_agents_field_scheduler_cron'),
                    'info' => _t('_sys_agents_field_scheduler_cron_info'),
                    'value' => isset($aAgent['scheduler_cron']) ? $aAgent['scheduler_cron'] : '',
                    // 'tr_attrs' => [
                    //     'style' => $sType != 'scheduler' ? 'display:none' : ''
                    // ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'webhook_sample' => [
                    'type' => 'custom',
                    'name' => 'webhook_sample',
                    'caption' => _t('_sys_agents_field_webhook_sample'),
                    'content' => isset($aAgent['webhook_sample']) ? $aAgent['webhook_sample'] : _t('_sys_agents_waiting_for_sample_data'),
                    // 'tr_attrs' => [
                    //     'style' => $sType != 'webhook' ? 'display:none' : ''
                    // ],
                ],
                'webhook_key' => [
                    'type' => 'text',
                    'name' => 'webhook_key',
                    'caption' => _t('_sys_agents_field_webhook_key'),
                    'info' => _t('_sys_agents_field_webhook_key_info'),
                    'value' => isset($aAgent['webhook_key']) ? $aAgent['webhook_key'] : '',
                    // 'tr_attrs' => [
                    //     'style' => $sType != 'webhook' ? 'display:none' : ''
                    // ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'message_profile_ids' => [
                    'type' => 'select',
                    'name' => 'message_profile_ids',
                    'caption' => _t('_sys_agents_field_message_profile'),
                    'info' => _t('_sys_agents_field_message_profile_info'),
                    'value' => isset($aAgent['message_profile_ids']) ? $aAgent['message_profile_ids'] : '',
                    // 'tr_attrs' => [
                    //     'style' => $sType != 'message' ? 'display:none' : ''
                    // ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'vector_store_id' => [
                    'type' => 'select',
                    'name' => 'vector_store_id',
                    'caption' => _t('_sys_agents_field_vector_store_id'),
                    'info' => _t('_sys_agents_field_vector_store_id_info'),
                    'value' => isset($aAgent['vector_store_id']) ? $aAgent['vector_store_id'] : 0,
                    'values' => $this->_oDb->getVectorStores(),
                    'db' => [
                        'pass' => 'Xss',
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
                'submit' => $this->_getFormControls(),

            ),
        );
        
        $this->_getMultiField('tools', $aAgent, 'getTools', 'toolAdd', 'agents_agents_form_tools.html', $aForm);

        return $aForm;
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
}

/** @} */
