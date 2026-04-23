<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxTasksMenuTimer.php');

class BxTasksTemplate extends BxBaseModTextTemplate
{
    public function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_tasks';

        parent::__construct($oConfig, $oDb);
    }

    public function getJsCode($sType, $aParams = [], $mixedWrap = true)
    {
        $aParams = array_merge([
            'aHtmlIds' => $this->_oConfig->getHtmlIds()
        ], $aParams);

        return parent::getJsCode($sType, $aParams, $mixedWrap);
    }

    public function getJsCodeTimer($sType, $aParams = [], $mixedWrap = true)
    {
        $aParams = array_merge([
            'aHtmlIds' => $this->_oConfig->getHtmlIds()
        ], $aParams);

        if(is_array($mixedWrap))
            return parent::getJsCode($sType, $aParams, [
                'wrap' => true,
                'mask' => "{var} {object} = new {class}({params}); {object}.init({content_id}, {profile_id}, {started});",
                'mask_markers' => $mixedWrap
            ]);
        else
            parent::getJsCode($sType, $aParams, $mixedWrap);
    }
    
    public function getBlockMenuBrowse()
    {
        $CNF = &$this->_oConfig->CNF;

        $iProfileId = bx_get_logged_profile_id();

        $sMenuManage = '';
        if(($oMenu = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_MANAGE_TOOLS_SUBMENU'])) !== false)
            $sMenuManage = $oMenu->getCode();

        $sMenuBrowse = '';
        if(($oMenu = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_BROWSE'])) !== false) {
            $oMenu->setProfileId($iProfileId);
            $sMenuBrowse = $oMenu->getCode();
        }

        return $this->parseHtmlByName('menu_browse.html', [
            'bx_if:show_menu_manage' => [
                'condition' => $sMenuManage != '',
                'content' => [
                    'menu_manage' => $sMenuManage
                ]
            ],
            'bx_if:show_menu_browse' => [
                'condition' => $sMenuBrowse != '',
                'content' => [
                    'menu_browse' => $sMenuBrowse
                ]
            ]
        ]);
    }
    
    /**
     * Use Gallery image for both because currently there is no Unit types with small thumbnails.
     */
    protected function getUnitThumbAndGallery ($aData)
    {
        list($sPhotoThumb, $sPhotoGallery) = parent::getUnitThumbAndGallery($aData);

        return array($sPhotoGallery, $sPhotoGallery);
    }
    
    public function entryText ($aData, $sTemplateName = 'entry-text.html')
    {
        $CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aData[$CNF['FIELD_ID']];

        $sResult = '';
        if(!$this->_oModule->isAllowManage($iContentId)) {
            $aVars = $this->getTmplVarsText($aData);

            $sResult = $this->parseHtmlByName($sTemplateName, $aVars);
        }
        else 
            $sResult = $this->getModule()->serviceEntityEdit($iContentId, $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT_BODY']);

        return $sResult;
    }

    public function entryAssignments ($aProfiles)
    {
        $CNF = &$this->_oConfig->CNF;

        $aTmplVarsProfiles = [];
        foreach($aProfiles as $mixedProfile) {
            $bProfile = is_array($mixedProfile);

            $oProfile = BxDolProfile::getInstance($bProfile ? (int)$mixedProfile['id'] : (int)$mixedProfile);
            if(!$oProfile)
                continue;

            $aUnitParams = ['template' => ['name' => 'unit', 'size' => 'thumb']];
            if($bProfile && is_array($mixedProfile['info']))
                $aUnitParams['template']['vars'] = $mixedProfile['info'];

            $aTmplVarsProfiles[] = [
                'unit' => $oProfile->getUnit(0, $aUnitParams)
            ];
        }

        return $aTmplVarsProfiles ? $this->parseHtmlByName('entry-assignments.html', [
            'bx_repeat:profiles' => $aTmplVarsProfiles
        ]) : MsgBox(_t('_sys_txt_empty'));
    }

    public function entryTimer ($iContentId, $iProfileId)
    {
        $this->addCss(['timer.css']);
        $this->addJs(['timer.js']);
        return $this->getTimer($iContentId, $iProfileId) . $this->getJsCodeTimer('timer', [], [
            'content_id' => $iContentId,
            'profile_id' => $iProfileId,
            'started' => $this->_oDb->isTimerStarted($iContentId, $iProfileId),
        ]);
    }

    public function getBlockTimers ($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        $oModule = $this->getModule();
        $sJsObject = $this->_oConfig->getJsObject('timer');

        $aTimers = $this->_oDb->getTimers(['sample' => 'profile_id', 'profile_id' => $iProfileId]);
        if(!$aTimers || !is_array($aTimers))
            return MsgBox(_t('_Empty'));

        $oPermalinks = BxDolPermalinks::getInstance();

        $aTmplVarsSections = [];
        foreach($aTimers as $aTimer) {
            $aContentInfo = $this->_oDb->getContentInfoById($aTimer['content_id']);
            if(!$aContentInfo && !is_array($aContentInfo))
                continue;

            $sContextType = $sContextTitle = $sContextUrl = '';
            if(($iAllowViewTo = (int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) < 0 && ($oContext = BxDolProfile::getInstance(abs($iAllowViewTo))) !== false) {
                $sContextType = $oContext->getModule();
                $sContextTitle = $oContext->getDisplayName();
                $sContextUrl = $oContext->getUrl();
            }

            if(!isset($aTmplVarsSections[$sContextType]))
                $aTmplVarsSections[$sContextType] = [
                    'section_title' => $oModule->getModuleTitle($sContextType),
                    'bx_repeat:timers' => []
                ];

            $aTmplVarsSections[$sContextType]['bx_repeat:timers'][] = [
                'bx_if:show_context' => [
                    'condition' => $sContextTitle && $sContextUrl,
                    'content' => [
                        'title' => $sContextTitle,
                        'url' => $sContextUrl
                    ]
                ],
                'content_title' => $aContentInfo[$CNF['FIELD_TITLE']],
                'content_url' => bx_absolute_url($oPermalinks->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']])),
                'timer' => $this->getTimer($aTimer['content_id'], $iProfileId),
                'js_code_timer' => $sJsObject . '.init(' . $aTimer['content_id'] . ', ' . $iProfileId . ', ' . ($aTimer['started'] != 0 ? 1 : 0) . ');'
            ];
        }

        $this->addCss(['timer.css']);
        $this->addJs(['timer.js']);
        return $this->parseHtmlByName('browse_timers.html', [
            'js_code' => $this->getJsCode('timer', [
                'bMulti' => true
            ]),
            'bx_repeat:sections' => array_values($aTmplVarsSections)
        ]);
    }

    public function getTimer ($iContentId, $iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        $sPrefix = str_replace('_', '-', $this->_oConfig->getName());
        $sJsObject = $this->_oConfig->getJsObject('timer');

        $aTimer = $this->_oDb->getTimers(['sample' => 'content_profile_ids', 'content_id' => $iContentId, 'profile_id' => $iProfileId]);
        $bTimer = $aTimer && is_array($aTimer);
        $bStarted = $bTimer && (int)$aTimer['started'] > 0;

        $iHours = $iMinutes = $iSeconds = 0;
        if($bTimer) {
            $iDuration = (int)$aTimer['duration'];
            if($bStarted)
                $iDuration += time() - (int)$aTimer['started'];

            list($iHours, $iMinutes, $iSeconds) = $this->_oConfig->timeI2A($iDuration, true);
        }

        $oActions = new BxTasksMenuTimer([
            'object' => $sPrefix . '_timer',
            'template' => 'menu_custom_hor.html', 
            'menu_id' => $this->_oConfig->getHtmlIds('timer_actions'), 
            'persistent' => 0
        ]);
        $oActions->setParams($iContentId, $iProfileId);

        return $this->parseHtmlByName('entry-timer.html', [
            'html_id' => $this->_oConfig->getHtmlIds('timer') . $iContentId . '-' . $iProfileId,
            'hours' => sprintf("%02d", $iHours),
            'minutes' => sprintf("%02d", $iMinutes),
            'seconds' => sprintf("%02d", $iSeconds),
            'actions' => $oActions->getCode()
        ]);
    }

    public function getEntriesList($iContextId, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;
        $sJsObject = $this->_oConfig->getJsObject('tasks');

        $oModule = $this->getModule();

        $bAllowAdd = $oModule->isAllowAdd($iContextId);
        $bAllowManage = $oModule->isAllowManageByContext($iContextId);

        $aTmplVarsFilterItems = [];

        $aFilters = [];
        if(($sK = $CNF['COOKIE_SETTING_KEY']) && isset($_COOKIE[$sK]))
            $aFilters = json_decode($_COOKIE[$sK], true);
        $iFilterSelected = $aParams['filter'] ?? ($aFilters[$iContextId] ?? 0);

        $aFilterItems = array_merge([['id' => 0, 'title' => '_bx_tasks_filter_title_select']], $this->_oDb->getFilters(['sample' => 'active']));
        foreach($aFilterItems as $aFilterItem)
            $aTmplVarsFilterItems[] = [
                'filter_id' => $aFilterItem['id'],
                'filter_title' => _t($aFilterItem['title']),
                'bx_if:show_filter_selected' => [
                    'condition' => $aFilterItem['id'] == $iFilterSelected,
                    'content' => [true]
                ]
            ];

        $this->addCssJs();
        $this->addJs([
            'jquery-ui/jquery-ui.min.js',
            'tasks.js',
            'modules/base/general/js/|forms.js'
        ]);

        return $this->parseHtmlByName('browse_tasks.html', [
            'bx_if:show_filters' => [
                'condition' => !empty($aTmplVarsFilterItems),
                'content' => [
                    'object' => $sJsObject,
                    'context_id' => $iContextId,
                    'bx_repeat:filter_items' => $aTmplVarsFilterItems
                ]
            ],
            'bx_if:allow_add_list' => [
                'condition' => $bAllowAdd,
                'content' => [
                    'context_id' => $iContextId,
                    'object' => $sJsObject,
                ]
            ],
            'bx_if:allow_manage_settings' => [
                'condition' => $bAllowManage,
                'content' => [
                    'context_id' => $iContextId,
                    'object' => $sJsObject,
                ]
            ],
            'tasks' => $this->getEntries($iContextId, array_merge($aParams, [
                'filter' => $iFilterSelected,
            ])),
            'js_code' => $this->getJsCode('tasks', ['t_confirm_block_deletion' => _t('_bx_tasks_txt_msg_confirm_tasklist_deletion')])
        ]);
    }
    
    public function getEntries($iContextId, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;
        $sJsObject = $this->_oConfig->getJsObject('tasks');

        $oModule = $this->getModule();
        $oPermalinks = BxDolPermalinks::getInstance();
        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION']);

        $aTypes = BxDolFormQuery::getDataItems($CNF['OBJECT_PRE_LIST_TYPES']);
        $aPriorities = BxDolFormQuery::getDataItems($CNF['OBJECT_PRE_LIST_PRIORITIES']);
        $aStates = BxDolFormQuery::getDataItems($CNF['OBJECT_PRE_LIST_STATES']);

        $bAllowAdd = $oModule->isAllowAdd($iContextId);
        $bAllowManage = $oModule->isAllowManageByContext($iContextId);

        $aTmplVarsLists = [];

        $aLists = $this->_oDb->getLists($iContextId);
        foreach($aLists as $aList) {
            $aTasks = $this->_oDb->getTasks($iContextId, $aList['id'], array_merge($aParams, [
                'with_stats' => true
            ]));

            $aTmplVarsTasks = [];
            foreach($aTasks as $aTask) {
                $sTime = '';
                if(!empty($aTask['time_total']))
                    $sTime = _t('_bx_tasks_txt_total', $this->_oConfig->timeI2S($aTask['time_total']) . (!empty($aTask['time']) ? ' (' . $this->_oConfig->timeI2S($aTask['time']) . ')' : ''));
                if(!empty($aTask['estimate']))
                    $sTime .= ' ' . _t('_bx_tasks_txt_estimate', $this->_oConfig->timeI2S(60 * (int)$aTask['estimate']));

                $aMembers = $oConnection->getConnectedInitiators($aTask[$CNF['FIELD_ID']]);

                $aTmplVarsMembers = [];
                foreach($aMembers as $iMember)
                    if(($oProfile = BxDolProfile::getInstance($iMember)) !== false && !($oProfile instanceof BxDolProfileUndefined))
                        $aTmplVarsMembers[] = ['info' => $oProfile->getUnit(0, ['template' => 'unit_wo_info'])];

                $bCompleted = $aTask[$CNF['FIELD_COMPLETED']] == 1;

                $aTmplVarsTasks[] = [
                    'id' => $aTask[$CNF['FIELD_ID']],
                    'title' => bx_process_output($aTask[$CNF['FIELD_TITLE']]),
                    'created' => bx_time_js($aTask[$CNF['FIELD_ADDED']]),
                    'class' => $bCompleted ? 'completed' : 'uncompleted',
                    'due' => $aTask[$CNF['FIELD_DUE_DATE']] > 0 ? bx_time_js($aTask[$CNF['FIELD_DUE_DATE']]) : '',
                    'type' => $aTypes[$aTask[$CNF['FIELD_TYPE']]] ?? '',
                    'priority' => $aPriorities[$aTask[$CNF['FIELD_PRIORITY']]] ?? '',
                    'state' => $aStates[$aTask[$CNF['FIELD_STATE']]] ?? '',
                    'time' => $sTime,
                    'bx_repeat:members' => $aTmplVarsMembers,
                    'badges' => $oModule->serviceGetBadges($aTask[$CNF['FIELD_ID']], true),
                    'url' => bx_absolute_url($oPermalinks->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aTask[$CNF['FIELD_ID']])),
                    'object' => $sJsObject,
                    'bx_if:allow_manage' => [
                        'condition' => $bAllowManage,
                        'content' => [
                            'id' => $aTask[$CNF['FIELD_ID']],
                            'object' => $sJsObject,
                            'checked' => $bCompleted ? 'checked' : '',
                        ]
                    ],
                    'bx_if:deny_manage' => [
                        'condition' => !$bAllowManage,
                        'content' => [
                            'id' => $aTask[$CNF['FIELD_ID']],
                            'checked' => $bCompleted ? 'checked' : '',
                        ]
                    ]
                ];
            }

            $sClass = $sCompleted = $sAll = "";
            if (isset($aFilterValues[$aList[$CNF['FIELD_ID']]])){
                $sClass = $aFilterValues[$aList[$CNF['FIELD_ID']]];
                if ($sClass == 'completed')
                    $sCompleted= 'selected';
                if ($sClass == 'all')
                    $sAll = 'selected';
            }

            $aTmplVarsLists[] = [
                'bx_if:allow_edit_list' => [
                    'condition' => $bAllowAdd,
                    'content' => [
                        'title' => $aList[$CNF['FIELD_TITLE']],
                        'context_id' => $iContextId,
                        'list_id' => $aList[$CNF['FIELD_ID']],
                        'object' => $sJsObject,
                    ]
                ],
                'bx_if:allow_add' => [
                    'condition' => $bAllowAdd,
                    'content' => [
                        'context_id' => $iContextId,
                        'list_id' => $aList[$CNF['FIELD_ID']],
                        'object' => $sJsObject,
                    ]
                ],
                'bx_if:allow_delete_list' => [
                    'condition' => $bAllowManage,
                    'content' => [
                        'context_id' => $iContextId,
                        'list_id' => $aList[$CNF['FIELD_ID']],
                        'object' => $sJsObject,
                    ]
                ],
                'bx_if:deny_edit_list' => [
                    'condition' => !$bAllowAdd,
                    'content' => [
                        'title' => $aList[$CNF['FIELD_TITLE']],
                    ]
                ],
                'id' => $aList['id'],
                'bx_repeat:tasks' =>  $aTmplVarsTasks,
                'context_id' => $iContextId,
                'list_id' => $aList[$CNF['FIELD_ID']],
                'object' => $sJsObject
            ];
        }

        return $this->parseHtmlByName('tasks.html', [
            'html_id' => $this->_oConfig->getHtmlIds('tasks'),
            'bx_repeat:task_lists' => $aTmplVarsLists,
        ]);
    }

    public function getStickers($aStickers)
    {
        $aTmplVarsStickers = [];
        foreach($aStickers as $aSticker)
            $aTmplVarsStickers[] = [
                'title' => $aSticker['title'],
                'bx_if:show_color' => [
                    'condition' => $aSticker['color'] != '',
                    'content' => [
                        'color' => $aSticker['color']
                    ]
                ]
            ];

        return $this->parseHtmlByName('stickers.html', [
            'bx_repeat:stickers' => $aTmplVarsStickers
        ]);
    }
}

/** @} */
