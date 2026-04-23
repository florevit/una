<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTasksGridPreValues extends BxBaseModGeneralGrid
{
    protected $_iContextPid;
    
    protected $_sFilter1Name;   //--- list
    protected $_sFilter1Value;
    protected $_aFilter1Values;

    protected $_aListInfo;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_tasks';

        parent::__construct ($aOptions, $oTemplate);

        if(($sFfName = 'profile_id') && ($iValue = bx_get($sFfName)) !== false) 
            $this->setContextPid($iValue);

        /*
         * Filter by list
         */
        $this->_initFilter(1, $this->_oModule->getPreLists());

        if(($sFfName = 'filter') && ($sValue = bx_get($sFfName)) !== false) 
            $this->_parseFilterValue($sValue);

        if($this->_sFilter1Value)
            $this->_aListInfo = $this->_oModule->_oDb->getPreLists([
                'sample' => 'name', 
                'name' => $this->_sFilter1Value
            ]);
    }

    public function setContextPid($iContextPid)
    {
        $this->_iContextPid = (int)$iContextPid;
        $this->_aQueryAppend['profile_id'] = $this->_iContextPid;
    }

    public function performActionAdd()
    {
        if(!$this->_sFilter1Value)
            return echoJson(['msg' => _t('_bx_tasks_txt_err_empty_pre_list')]);

        $sAction = 'add';

        $oForm = $this->_getForm($sAction);
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            if(($iId = (int)$oForm->insert(['context_id' => $this->_iContextPid])) != 0)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $iId];
            else
                $aRes = ['msg' => _t('_bx_tasks_txt_err_cannot_perform_action')];

            return echoJson($aRes);
        }

        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('pre_values_popup') . $sAction, _t('_bx_tasks_popup_pv_title_' . $sAction), $this->_oModule->_oTemplate->parseHtmlByName('popup_pre_values.html', [
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionEdit()
    {
        $sAction = 'edit';

        $aItem = $this->_oModule->_oDb->getPreValues(['sample' => 'id', 'id' => $this->_getIds()]);
        if(!$aItem)
            return echoJson([]);

        $oForm = $this->_getForm($sAction, $aItem);
        $oForm->initChecker($aItem);
        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($aItem['id']) !== false)
                $aRes = ['grid' => $this->getCode(false), 'blink' => $aItem['id']];
            else
                $aRes = ['msg' => _t('_bx_tasks_txt_err_cannot_perform_action')];

            return echoJson($aRes);
        }

        $sContent = BxTemplFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('pre_values_popup') . $sAction, _t('_bx_tasks_popup_pv_title_' . $sAction), $this->_oModule->_oTemplate->parseHtmlByName('popup_pre_values.html', [
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    protected function _getFilterOnChange()
    {
        return $this->_oModule->_oConfig->getJsObject('pre_values') . '.onChangeFilter(this)';
    }

    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        $sContent = $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values, '_bx_tasks_grid_filter_item_title_pv_select_one_list');
        $sContent .= $this->_getSearchInput();
        return $sContent;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_parseFilterValue($sFilter);

        if(!$this->_iContextPid || !$this->_sFilter1Value)
            return [];

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(' AND `context_id`=? AND `list`=?', $this->_iContextPid, $this->_sFilter1Value);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _getForm($sAction, $aItem = [])
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aActionParams = [
            'o' => $this->_sObject, 
            'a' => $sAction,
            'profile_id' => $this->_iContextPid,
            $this->_sFilter1Name => $this->_sFilter1Value
        ];

        if($aItem)
            $aActionParams['id'] = $aItem['id'];

        $aForm = [
            'form_attrs' => [
                'id' => 'bx-tasks-pre-value-' . $sAction,
                'action' => BX_DOL_URL_ROOT . bx_append_url_params('grid.php', $aActionParams),
                'method' => BX_DOL_FORM_METHOD_DEFAULT
            ],
            'params' => [
                'db' => [
                    'table' => $CNF['TABLE_PRE_VALUES'],
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ],
            ],
            'inputs' => [
                'list' => [
                    'type' => 'hidden',
                    'name' => 'list',
                    'value' => $this->_sFilter1Value,
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'value' => [
                    'type' => 'hidden',
                    'name' => 'value',
                    'value' => (int)$this->_oModule->_oDb->getPreValues([
                        'sample' => 'value_to_use', 
                        'context_id' => $this->_iContextPid, 
                        'list' => $this->_sFilter1Value
                    ]),
                    'db' => [
                        'pass' => 'Int',
                    ]
                ],
                'title' => [
                    'type' => 'text',
                    'name' => 'title',
                    'caption' => _t('_bx_tasks_form_pv_input_title'),
                    'value' => '',
                    'required' => '1',
                    'db' => [
                        'pass' => 'Xss',
                    ],
                    'checker' => [
                        'func' => 'Avail',
                        'params' => [],
                        'error' => _t('_bx_tasks_form_pv_input_err'),
                    ],
                ],
                'color' => [
                    'type' => 'text',
                    'name' => 'color',
                    'caption' => _t('_bx_tasks_form_pv_input_color'),
                    'info' => _t('_bx_tasks_form_pv_input_color_inf'),
                    'value' => '',
                    'required' => '0',
                    'db' => [
                        'pass' => 'Xss',
                    ]
                ],
                'controls' => [
                    'name' => 'controls',
                    'type' => 'input_set',
                    [
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_bx_tasks_form_pv_input_submit'),
                    ], [
                        'type' => 'reset',
                        'name' => 'do_cancel',
                        'value' => _t('_bx_tasks_form_pv_input_cancel'),
                        'attrs' => [
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ],
                    ]
                ]
            ]
        ];

        if($this->_aListInfo && (int)$this->_aListInfo['use_color'] == 0)
            unset($aForm['inputs']['color']);

        return new BxTemplFormView($aForm);
    }

    protected function _getIds()
    {
        if(($aIds = bx_get('ids')) && is_array($aIds))
            return reset($aIds);

        if(($iId = bx_get('id')) !== false) 
            return (int)$iId;

        return false;
    }
}

/** @} */
