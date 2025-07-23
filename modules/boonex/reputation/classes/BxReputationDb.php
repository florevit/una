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

class BxReputationDb extends BxBaseModNotificationsDb
{
    protected $_oConfig;

    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;
    }

    public function updateEvent($aParamsSet, $aParamsWhere)
    {
        return false;
    }

    public function deleteEvent($aParams, $sWhereAddon = "")
    {
        return false;
    }

    public function getEvents($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '*';
        $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = '';

        switch($aParams['sample']) {
            case 'owner_id':
                $aMethod['params'][1] = [
                    'owner_id' => $aParams['owner_id']
                ];

                $sWhereClause = 'AND `owner_id` = :owner_id';

                if(isset($aParams['context_id']) && $aParams['context_id'] !== false) {
                    $aMethod['params'][1]['context_id'] = $aParams['context_id'];

                    $sWhereClause .= ' AND `context_id` = :context_id';
                }

                $sOrderClause = '`date` DESC';
                if(isset($aParams['start'], $aParams['limit']))
                    $sLimitClause = $aParams['start'] . ', ' . $aParams['limit'];
                break;

            case 'stats':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'owner_id';
                $aMethod['params'][2] = 'points';
                $aMethod['params'][3] = [];

                $sSelectClause = '`owner_id`, SUM(`points`) AS `points`';

                if(isset($aParams['context_id']) && $aParams['context_id'] !== false) {
                    $aMethod['params'][3]['context_id'] = $aParams['context_id'];

                    $sWhereClause .= ' AND `context_id` = :context_id';
                }

                if(!empty($aParams['days'])) {
                    $aMethod['params'][3]['days'] = (int)$aParams['days'];

                    $sWhereClause .= ' AND `date` >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL :days DAY))';
                }

                $sGroupClause = '`owner_id`';
                $sOrderClause = '`points` DESC';
                $sLimitClause = '0, ' . (int)$aParams['limit'];
                break;
        }
        
        if($sGroupClause)
            $sGroupClause = "GROUP BY " . $sGroupClause;

        if($sOrderClause)
            $sOrderClause = "ORDER BY " . $sOrderClause;

        if($sLimitClause)
            $sLimitClause = "LIMIT " . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_EVENTS'] . "` WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function getLevels($aParams = []) 
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '`trl`.*';
        $sJoinClause = $sWhereClause = $sOrderClause = '';

        if(!empty($aParams))
            switch($aParams['sample']) {
                case 'id':
                    $aMethod['name'] = 'getRow';
                    $aMethod['params'][1] = [
                        'id' => $aParams['id']
                    ];

                    $sWhereClause = "AND `trl`.`id` = :id";
                    break;

                case 'profile_id':
                    $aMethod['params'][1] = [
                        'profile_id' => $aParams['profile_id']
                    ];

                    $sSelectClause .= ", `trpl`.`date` AS `date_assign`";
                    $sJoinClause = " INNER JOIN `" . $CNF['TABLE_PROFILES_LEVELS'] . "` AS `trpl` ON `trl`.`id`=`trpl`.`level_id` AND `trpl`.`profile_id`=:profile_id";

                    if(isset($aParams['context_id']) && $aParams['context_id'] !== false) {
                        $aMethod['params'][1]['context_id'] = $aParams['context_id'];

                        $sJoinClause .= "  AND `trpl`.`context_id`=:context_id";
                    }
                    break;

                case 'points':
                    $aMethod['params'][1] = [
                        'points' => $aParams['points']
                    ];

                    $sWhereClause = "AND `trl`.`points_in` <= :points AND IF(`trl`.`points_out` <> 0, `trl`.`points_out` > :points, 1)";
                    break;
                
                case 'all':
                    if(isset($aParams['active'])) {
                        $aMethod['params'][1] = [
                            'active' => (int)$aParams['active'] ? 1 : 0
                        ];

                        $sWhereClause = "AND `trl`.`active` = :active";
                    }

                    $sOrderClause = "`trl`.`order` ASC";
                    break;
            }

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT 
                " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_LEVELS'] . "` AS `trl` " . $sJoinClause . " 
            WHERE 1 " . $sWhereClause . " " . $sOrderClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function insertProfile($iProfileId, $iContextId, $iPoints)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("INSERT INTO `" . $CNF['TABLE_PROFILES'] . "` (`profile_id`, `context_id`, `points`) VALUES (:profile_id, :context_id, :points) ON DUPLICATE KEY UPDATE `points`=`points`+:points", [
            'profile_id' => $iProfileId,
            'context_id' => $iContextId,
            'points' => $iPoints
        ]);
    }

    public function deleteProfile($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("DELETE FROM `" . $CNF['TABLE_PROFILES'] . "` WHERE `profile_id`=:profile_id", ['profile_id' => $iProfileId]);
    }
    
    public function getProfiles($aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '`trp`.*';
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = '';
        
        switch($aParams['sample']) {
            case 'profile_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'profile_id' => $aParams['profile_id'],
                    'context_id' => isset($aParams['context_id']) ? $aParams['context_id'] : 0 
                ];

                $sWhereClause = "AND `trp`.`profile_id` = :profile_id AND `trp`.`context_id` = :context_id";
                break;

            case 'stats':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'profile_id';
                $aMethod['params'][2] = 'points';
                $aMethod['params'][3] = [];

                if(isset($aParams['context_id']) && $aParams['context_id'] !== false) {
                    $aMethod['params'][3]['context_id'] = $aParams['context_id'];

                    $sWhereClause .= ' AND `context_id` = :context_id';
                }

                $sOrderClause = '`trp`.`points` DESC';
                $sLimitClause = '0, ' . (int)$aParams['limit'];
                break;

            case 'points_range':
                $aMethod['params'][1] = [];

                if(!empty($aParams['points_in'])) {
                    $aMethod['params'][1]['points_in'] = $aParams['points_in'];
                    
                    $sWhereClause .= "AND `trp`.`points` >= :points_in ";
                }

                if(!empty($aParams['points_out'])) {
                    $aMethod['params'][1]['points_out'] = $aParams['points_out'];
                    
                    $sWhereClause .= "AND `trp`.`points` <= :points_out ";
                }
                break;
        }

        if($sOrderClause)
            $sOrderClause = "ORDER BY " . $sOrderClause;

        if($sLimitClause)
            $sLimitClause = "LIMIT " . $sLimitClause;

        $aMethod['params'][0] = "SELECT 
                " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_PROFILES'] . "` AS `trp` " . $sJoinClause . " 
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function getProfilePoints($iProfileId, $iContextId = 0)
    {
        $aProfile = $this->getProfiles([
            'sample' => 'profile_id', 
            'profile_id' => $iProfileId,
            'context_id' => $iContextId
        ]);

        return $aProfile && isset($aProfile['points']) ? (int)$aProfile['points'] : 0;
    }

    public function insertProfilesLevels($aSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($aSet['date']))
            $aSet['date'] = time();

        return $this->query("INSERT INTO `" . $CNF['TABLE_PROFILES_LEVELS'] . "` SET " . $this->arrayToSQL($aSet));
    }

    public function deleteProfilesLevels($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aBindings = [];
        $sWhereClause = "";

        switch($aParams['sample']) {
            case 'profile_id':
                $aBindings = [
                    'profile_id' => $aParams['profile_id']
                ];

                $sWhereClause = "`profile_id`=:profile_id";
                
                if(isset($aParams['context_id']) && $aParams['context_id'] !== false) {
                    $aBindings['context_id'] = $aParams['context_id'];

                    $sWhereClause .= " AND `context_id` = :context_id";
                }
                break;
        }

        if(!$sWhereClause)
            return false;

        return $this->query("DELETE FROM `" . $CNF['TABLE_PROFILES_LEVELS'] . "` WHERE " . $sWhereClause, $aBindings);
    }

    public function deleteProfilesLevelsByPoints($iProfileId, $iContextId, $iPoints)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("DELETE FROM `trpl` 
                USING `" . $CNF['TABLE_PROFILES_LEVELS'] . "` AS `trpl` 
                LEFT JOIN `" . $CNF['TABLE_LEVELS'] . "` AS `trl` ON `trpl`.`level_id`=`trl`.`id` 
                WHERE `trpl`.`profile_id` = :profile_id AND `trpl`.`context_id` = :context_id AND (`trl`.`points_in` > :points OR IF(`trl`.`points_out` <> 0, `trl`.`points_out` <= :points, 0))", [
            'profile_id' => $iProfileId,
            'context_id' => $iContextId,
            'points' => $iPoints
        ]);
    }

    public function getHandlers($aParams = []) 
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '*';
        $sWhereClause = $sOrderClause = $sLimitClause = '';

        if(!empty($aParams))
            switch($aParams['type']) {
                case 'alert_units_list':
                    $aMethod['name'] = 'getColumn';
                    $aMethod['params'][1] = 'alert_unit';

                    $sSelectClause = 'DISTINCT `alert_unit`';
                    break;

                case 'all':
                    if(isset($aParams['active'])) {
                        $aMethod['params'][1] = [
                            'active' => (int)$aParams['active'] ? 1 : 0
                        ];

                        $sWhereClause = "AND `active` = :active";
                    }

                    $sOrderClause = "`group` ASC, `type` ASC";
                    if(isset($aParams['start'], $aParams['limit']))
                        $sLimitClause = $aParams['start'] . ', ' . $aParams['limit'];
                    break;

                default:
                    return parent::getHandlers($aParams);
            }

        if($sOrderClause)
            $sOrderClause = "ORDER BY " . $sOrderClause;

        if($sLimitClause)
            $sLimitClause = "LIMIT " . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `{$this->_sTableHandlers}` WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
}

/** @} */
