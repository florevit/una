<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reputation Reputation
 * @ingroup     UnaModules
 *
 * @{
 */

class BxReputationTemplate extends BxBaseModNotificationsTemplate
{
    public function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getBlockActions($iStart = 0, $iLimit = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$iLimit)
            $iLimit = !$this->_bIsApi ? (int)getParam($CNF['PARAM_ACTIONS_LIMIT']) : 9999;

        $aHandlers = $this->_oDb->getHandlers([
            'type' => 'all', 
            'active' => true,
            'start' => $iStart,
            'limit' => $iLimit + 1
        ]);

        $aTmplVarsActions = [];
        foreach($aHandlers as $aHandler) {
            $aTmplVarsActions[] = [
                'action' => _t('_bx_reputation_txt_unit_action_value', $this->_oModule->getUnitTitle($aHandler['alert_unit']), $this->_oModule->getActionTitle($aHandler['alert_action'])),
                'points_active' => ($iPa = (int)$aHandler['points_active']) > 0 ? '+' . $iPa : '-' . abs($iPa),
                'points_passive' => ($iPp = (int)$aHandler['points_passive']) > 0 ? '+' . $iPp : '-' . abs($iPp)
            ];
        }

        if($this->_bIsApi)
            return $aTmplVarsActions;

        $oPaginate = new BxTemplPaginate([
            'start' => $iStart,
            'per_page' => $iLimit,
            'on_change_page' => "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}')"
        ]);
        $oPaginate->setNumFromDataArray($aTmplVarsActions);
        $sPaginate = $oPaginate->getSimplePaginate();

        $this->addCss(['main.css']);
        return $this->parseHtmlByName('block_actions.html', [
            'bx_repeat:actions' => $aTmplVarsActions,
            'bx_if:show_paginate' => [
                'condition' => !empty($sPaginate),
                'content' => [
                    'paginate' => $sPaginate
                ]
            ]
        ]);
    }

    public function getBlockLevels()
    {
        $oFunctions = BxTemplFunctions::getInstance();

        $aLevels = $this->_oDb->getLevels([
            'sample' => 'all', 
            'active' => true
        ]);

        $aTmplVarsLevels = [];
        foreach($aLevels as $aLevel) {
            list($sIconFont, $sIconUrl, $sIconA, $sIconHtml) = $oFunctions->getIcon($aLevel['icon']);
            $bIconFont = !empty($sIconFont);
            $bIconHtml = !empty($sIconHtml);

            $aTmplVarsLevels[] = array_merge([
                'id' => $aLevel['id'],
                'name' => $aLevel['name'],
                'title' => _t($aLevel['title']),
                'icon' => $aLevel['icon'],
                'points_in' => (int)$aLevel['points_in'],
                'points_out' => (int)$aLevel['points_out'],
            ], (!$this->_bIsApi ? [
                'bx_if:icon' => [
                    'condition' => $bIconFont || $bIconHtml,
                    'content' => [
                        'bx_if:icon_font' => [
                            'condition' => $bIconFont,
                            'content' => [
                                'icon' => $sIconFont
                            ]
                        ],
                        'bx_if:icon_html' => [
                            'condition' => $bIconHtml,
                            'content' => [
                                'icon' => $sIconHtml
                            ]
                        ],
                    ]
                ],
                'points' => _t('_bx_reputation_txt_from_to', (int)$aLevel['points_in'], (int)$aLevel['points_out'])
            ] : [
                'icon' => $bIconHtml ? $sIconHtml : ''
            ]));
        }

        return $this->_bIsApi ? $aTmplVarsLevels : $this->parseHtmlByName('block_levels.html', [
            'bx_repeat:levels' => $aTmplVarsLevels
        ]);
    }

    public function getBlockSummary($iProfileId, $iContextId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile)
            return false;

        $aProfileInfo = $this->_oDb->getProfiles([
            'sample' => 'profile_id', 
            'profile_id' => $iProfileId, 
            'context_id' => $iContextId
        ]);
        $bProfileInfo = !empty($aProfileInfo) && is_array($aProfileInfo);
        $aProfileLevels = $this->_oDb->getLevels([
            'sample' => 'profile_id', 
            'profile_id' => $iProfileId,
            'context_id' => $iContextId
        ]);

        $oFunctions = BxTemplFunctions::getInstance();

        $aTmplVarsLevels = [];
        foreach($aProfileLevels as $aProfileLevel) {
            list($sIconFont, $sIconUrl, $sIconA, $sIconHtml) = $oFunctions->getIcon($aProfileLevel['icon']);
            $bIconFont = !empty($sIconFont);
            $bIconHtml = !empty($sIconHtml);

            $sTitle = _t($aProfileLevel['title']);

            $aTmplVarsLevels[] = !$this->_bIsApi ? [
                'bx_if:icon' => [
                    'condition' => $bIconFont || $bIconHtml,
                    'content' => [
                        'bx_if:icon_font' => [
                            'condition' => $bIconFont,
                            'content' => [
                                'icon' => $sIconFont
                            ]
                        ],
                        'bx_if:icon_html' => [
                            'condition' => $bIconHtml,
                            'content' => [
                                'icon' => $sIconHtml
                            ]
                        ],
                    ]
                ],
                'title' => $sTitle
            ] : [
                'name' => $aProfileLevel['name'],
                'title' => $sTitle,
                'icon' => $bIconHtml ? $sIconHtml : '',
            ];
        }

        $iProfilePoints = $bProfileInfo ? (int)$aProfileInfo['points'] : 0;

        if($this->_bIsApi)
            return [
                'author_data' => BxDolProfile::getData($iProfileId),
                'points' => $iProfilePoints,
                'levels' => $aTmplVarsLevels,
                'actions_list' => $this->getBlockActions(),
                'levels_list' => $this->getBlockLevels()
            ];

        return $this->parseHtmlByName('block_summary.html', [
            'profile_image' => $oProfile->getUnit($iProfileId, ['template' => ['name' => 'unit_wo_info', 'size' => 'ava']]),
            'profile_name' => $oProfile->getDisplayName(),
            'points' => $iProfilePoints,
            'history_url' => BxDolPermalinks::getInstance()->permalink($CNF['URL_HISTORY']),
            'bx_repeat:levels' => $aTmplVarsLevels
        ]);
    }

    public function getBlockHistory($iProfileId, $iContextId = 0, $iStart = 0, $iLimit = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        $oModule = $this->getModule();

        if(!$iLimit)
            $iLimit = !$this->_bIsApi ? (int)getParam($CNF['PARAM_HISTORY_LIMIT']) : 9999;

        $aItems = $this->_oDb->getEvents([
            'sample' => 'owner_id', 
            'owner_id' => $iProfileId,
            'context_id' => ($iContextId = (int)$iContextId) ? $iContextId : false,
            'start' => $iStart,
            'limit' => $iLimit + 1
        ]);

        $aTmplVarsItems = [];        
        foreach($aItems as $aItem) {
            $aTmplVarsItems[] = [
                'unit' => $oModule->getUnitTitle($aItem['type']),
                'action' => $oModule->getActionTitle($aItem['action']),
                'points' => ($iPoints = (int)$aItem['points']) > 0 ? '+' . $iPoints : '-' . abs($iPoints),
                'date' => $this->_bIsApi ? $aItem['date'] : bx_time_js($aItem['date'])
            ];
        }

        if($this->_bIsApi)
            return [
                bx_api_get_block('reputation_history', $aTmplVarsItems)
            ];

        $oPaginate = new BxTemplPaginate([
            'start' => $iStart,
            'per_page' => $iLimit,
            'on_change_page' => "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}')"
        ]);
        $oPaginate->setNumFromDataArray($aTmplVarsItems);
        $sPaginate = $oPaginate->getSimplePaginate();

        $this->addCss(['main.css']);
        return $this->parseHtmlByName('block_history.html', [
            'bx_repeat:items' => $aTmplVarsItems,
            'bx_if:show_paginate' => [
                'condition' => !empty($sPaginate),
                'content' => [
                    'paginate' => $sPaginate
                ]
            ]
        ]);
    }

    public function getBlockLeaderboard($iContextId = 0, $iDays = 0, $iLimit = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $iContextId = (int)$iContextId;
        $iDays = (int)$iDays;
        $iLimit = $iLimit ? (int)$iLimit : (int)getParam($CNF['PARAM_LEADERBOARD_LIMIT']);
        $bGrowth = $iDays > 0;

        if($bGrowth) 
            $aItems = $this->_oDb->getEvents([
                'sample' => 'stats', 
                'context_id' => $iContextId ?: false,
                'days' => $iDays, 
                'limit' => $iLimit
            ]);
        else
            $aItems = $this->_oDb->getProfiles([
                'sample' => 'stats',
                'context_id' => $iContextId,
                'limit' => $iLimit
            ]);

        $aTmplVarsProfiles = [];
        foreach($aItems as $iProfileId => $iPoints)
            if($iPoints != 0 && ($iProfileId = abs($iProfileId)) && ($oProfile = BxDolProfile::getInstance($iProfileId)) !== false)
                $aTmplVarsProfiles[] = [
                    'unit' => !$this->_bIsApi ? $oProfile->getUnit($iProfileId) : BxDolProfile::getData($oProfile),
                    'sign' => $bGrowth ? ($iPoints > 0 ? '+' : '-') : '',
                    'points' => $bGrowth ? abs($iPoints) : $iPoints
                ];

        if($this->_bIsApi)
            return [
                'days' => $iDays,
                'profiles' => $aTmplVarsProfiles
            ];

        $this->addCss(['main.css']);
        return $this->parseHtmlByName('block_leaderboard.html', [
            'bx_repeat:profiles' => $aTmplVarsProfiles
        ]);
    }
}

/** @} */
