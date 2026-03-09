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
    protected $_aDataExt = [
        'txt' => 'getFileContentPlain',
        'md' => 'getFileContentPlain',
        'html' => 'getFileContentHtml',
    ];

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
    
    public function performActionAddData()
    {
        $sAction = 'add_data';

        $iId = $this->_getId();
        $aVectorStore = $this->_oDb->getVectorStoreById($iId);

        $aForm = $this->_getFormAddData($sAction, $aVectorStore);
        $oForm = new BxTemplFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $iFilesAdded = 0;
            foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
                $sFileName = $_FILES['files']['name'][$key];
                $sFileType = $_FILES['files']['type'][$key];
                $sFileSize = $_FILES['files']['size'][$key];
                $sFileTmpName = $_FILES['files']['tmp_name'][$key];
                $sMetadata = $oForm->getCleanValue('metadata');
                $iVectorStoreId = $oForm->getCleanValue('vector_store_id');
                $sSettings = json_encode([
                    'chunk_size' => $oForm->getCleanValue('chunk_size'),
                    'delimeter' => trim($oForm->getCleanValue('delimeter')),
                    'overlap' => $oForm->getCleanValue('overlap'),
                ]);

                $sExt = pathinfo($sFileName, PATHINFO_EXTENSION);
                $sMethod = isset($this->_aDataExt[$sExt]) ? $this->_aDataExt[$sExt] : 'getFileContentPlain';
                $sContent = $this->$sMethod($sFileTmpName);

                if (empty($sContent)) {
                    continue;
                }   
                $bRet = $this->_oDb->addVectorStoreData ($iVectorStoreId, 'custom', $sFileName, (int)$sFileSize, $sMetadata, $sSettings, $sContent);
                if ($bRet) {
                    $iFilesAdded++;
                }
            }
            return echoJson(['msg' => _t('_sys_agents_vector_store_data_queued', $iFilesAdded) . ' / num:' . $iFilesAdded]);

            if($oForm->add($iId) === false)
                return echoJson(['msg' => _t('_sys_txt_error_occured')]);

            return echoJson(['grid' => $this->getCode(false), 'blink' => $iId]);
        } 

        $sFormId = $oForm->getId();
        $sForm = $oForm->getCode(true);
        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($sFormId . '_popup_' . $sAction, _t('_sys_agents_vector_store_popup_add_data'), $this->_oTemplate->parseHtmlByName('agents_automator_form.html', [
            'form_id' => $sFormId,
            'form' => $sForm,
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
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

    public function performActionFiles()
    {
        $iId = $this->_getId();
        $aVectorStore = $this->_oDb->getVectorStoreById($iId);

        $oGrid = BxDolGrid::getObjectInstance('sys_studio_agents_vector_store_data');
        $oGrid->addMarkers(['vector_store_id' => $iId]);
        $oGrid->setBrowseParams(['vector_store_id' => $iId]);
        $sGrid = $oGrid->getCode();

        $sContent = BxTemplStudioFunctions::getInstance()->popupBox('popup_files_' . $iId, _t('_sys_agents_vector_store_popup_files'), $this->_oTemplate->parseHtmlByName('agents_popup_grid.html', [
            'grid' => $sGrid,
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
                        'error' => _t('_sys_agents_json_field_err'),
                    ],
                    'db' => [
                        'pass' => 'All'
                    ]
                ],
                'submit' => $this->_getFormControls(),
            ]
        ];
    }

    protected function _getFormAddData(string $sAction, array $aVectorStore)
    {
        $sJsObject = $this->getPageJsObject();
        $aExt = array_keys($this->_aDataExt);
        $sExtList = '.' . implode(', .', $aExt);

        return [
            'form_attrs' => [
                'id' => 'bx_std_agents_helpers_' . $sAction,
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=sys_studio_agents_vector_store&a=' . $sAction,
                'method' => 'post',
            ],
            'params' => array (
                'db' => array(
                    'table' => 'sys_agents_vector_store_data',
                    'key' => 'id',
                    'submit_name' => 'do_submit',
                ),
            ),
            'inputs' => [
                'vector_store_id' => [
                    'type' => 'hidden',
                    'name' => 'vector_store_id',
                    'value' => $aVectorStore ? $aVectorStore['id'] : 0,
                ],
                'chunk_size' => [
                    'type' => 'text',
                    'name' => 'chunk_size',
                    'caption' => '_sys_agents_vector_store_field_chunk_size',
                    'info' => '_sys_agents_vector_store_field_chunk_size_info',
                    'required' => true,
                    'value' => 512,
                    'db' => [
                        'pass' => 'Int'
                    ]
                ],
                'delimeter' => [
                    'type' => 'text',
                    'name' => 'delimeter',
                    'caption' => '_sys_agents_vector_store_field_delimeter',
                    'info' => '_sys_agents_vector_store_field_chunk_size_info',
                    'required' => true,
                    'value' => '.',
                    'db' => [
                        'pass' => 'Xss'
                    ]
                ],
                'overlap' => [
                    'type' => 'text',
                    'name' => 'overlap',
                    'caption' => '_sys_agents_vector_store_field_overlap',
                    'info' => '_sys_agents_vector_store_field_chunk_size_info',
                    'required' => true,
                    'value' => 2,
                    'db' => [
                        'pass' => 'Int'
                    ]
                ],
                'metadata' => [
                    'type' => 'textarea',
                    'name' => 'metadata',
                    'caption' => '_sys_agents_vector_store_field_metadata',
                    'info' => '_sys_agents_vector_store_field_chunk_size_info',
                    'required' => false,
                    'checker' => [
                        'func' => 'Json',
                        'params' => ['allow_empty' => true],
                        'error' => _t('_sys_agents_vector_store_json_field_err'),
                    ],
                    'db' => [
                        'pass' => 'All'
                    ]
                ],
                'files' => [
                    'attrs' => [
                        'multiple' => true,
                        'accept' => $sExtList
                    ],
                    'type' => 'file',
                    'name' => 'files[]',
                    'caption' => _t('_sys_agents_vector_store_field_files'),
                    'caption_info' => _t('_sys_agents_vector_store_field_files_info'),
                    'required' => true,
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

    protected function getFileContentPlain($sFilePath)
    {
        return file_get_contents($sFilePath);
    }
    protected function getFileContentHtml($sFilePath)  
    {
        $s = file_get_contents($sFilePath);
        $html = new \Html2Text\Html2Text($s);
        return $html->getText();
    }
}

/** @} */
