<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgentsVectorStore extends BxDolStudioAgentsVectorStore
{
    protected $_sUrlPage;
    protected $_sFieldName;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sUrlPage = BX_DOL_URL_STUDIO . 'agents.php?page=vector_store';

        $this->_sFieldName = 'name';
    }

    public function getPageJsObject()
    {
        return 'oBxDolStudioPageAgents';
    }

    public function performActionEdit()
    {
        $sAction = 'edit';

        $iId = $this->_getId();
        $aVectorStore = $this->_oDb->getVectorStoreById($iId);

        $aForm = $this->_getFormEdit($sAction, $aVectorStore);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iId) === false)
                return echoJson(['msg' => _t('_sys_txt_error_occured')]);

            return echoJson(['grid' => $this->getCode(false), 'blink' => $iId]);
        } 

        $sFormId = $oForm->getId();
        $sForm = $oForm->getCode(true);
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup_' . $sAction, _t('_sys_agents_vector_store_popup_edit'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
            'form_id' => $sFormId,
            'form' => $sForm,
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    protected function _delete ($mixedId)
    {
        $mixedResult = parent::_delete($mixedId);
        if($mixedResult)
            $this->_oDb->deleteAutomatorHelpers(['helper_id' => (int)$mixedId]);

        return $mixedResult;
    }

    protected function _getForm($sAction = '', $aVectorStore = [])
    {
        $sJsObject = $this->getPageJsObject();
    
        if (empty($aVectorStore['params_user'])) {
            $aVectorStore['params_user'] = $aVectorStore['params'];
        }

        $data = json_decode($aVectorStore['params_user'], true);
        $aVectorStore['params_user'] = json_encode($data, JSON_PRETTY_PRINT);

        return [
            'form_attrs' => [
                'id' => 'bx_std_agents_helpers_' . $sAction,
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=sys_studio_agents_vector_store&a=' . $sAction,
                'method' => 'post',
            ],
            'params' => array (
                'db' => array(
                    'table' => 'sys_agents_vector_store',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => [
                'topk' => [
                    'type' => 'text',
                    'name' => 'topk',
                    'caption' => '_sys_agents_vector_store_field_topk',
                    'required' => true,
                    'value' => !empty($aVectorStore['topk']) ? $aVectorStore['topk'] : '',
                    'db' => [
                        'pass' => 'Int'
                    ]
                ],
                'params_user' => [
                    'type' => 'textarea',
                    'name' => 'params_user',
                    'caption' => '_sys_agents_vector_store_field_params',
                    'required' => false,
                    'value' => !empty($aVectorStore['params_user']) ? $aVectorStore['params_user'] : '',
                    'checker' => [
                        'func' => 'Json',
                        'params' => ['allow_empty' => true],
                        'error' => _t('_sys_agents_vector_store_field_params_err'),
                    ],
                    'db' => [
                        'pass' => 'All'
                    ]
                ],
                'submit' => $this->_getFormControls(),
            ]
        ];
    }

    protected function _getFormEdit($sAction, $aVectorStore = [])
    {
        $aForm = $this->_getForm($sAction, $aVectorStore);
        $aForm['form_attrs']['action'] .= '&id=' . $aVectorStore['id'];

        return $aForm;
    }


}

/** @} */
