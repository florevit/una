<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Groups module database queries
 */
class BxBaseModGroupsDb extends BxBaseModProfileDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function updateAuthorById ($iContentId, $iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        $sQuery = "UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `" . $CNF['FIELD_AUTHOR'] . "` = :author WHERE `" . $CNF['FIELD_ID'] . "` = :id";
        return $this->query($sQuery, array(
    		'id' => $iContentId,
    		'author' => $iProfileId,
    	));
    }

    public function toAdmins ($iGroupProfileId, $mixedFansIds)
    {
        if (is_array($mixedFansIds)) {
            foreach ($mixedFansIds as $iFanId)
                $this->toAdmins ($iGroupProfileId, $iFanId);

            return true;
        }

        $iFanId = (int)$mixedFansIds;
        $sQuery = $this->prepare("INSERT IGNORE INTO `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` SET `group_profile_id` = ?, `fan_id` = ?, `role` = ?", $iGroupProfileId, $iFanId, BX_BASE_MOD_GROUPS_ROLE_ADMINISTRATOR);
        if (!$this->res($sQuery))
            return false;

        $oModule = BxDolModule::getInstance($this->_oConfig->getName());
        if ($oModule && method_exists($oModule, 'onFanAddedToAdmins'))
            $oModule->onFanAddedToAdmins($iGroupProfileId, $iFanId);

        $oModule->doAudit($iGroupProfileId, $iFanId, '_sys_audit_action_group_to_admins');
        
        return true;
    }

    public function fromAdmins ($iGroupProfileId, $mixedFansIds)
    {
        if (is_array($mixedFansIds)) {
            foreach ($mixedFansIds as $iFanId)
                $this->fromAdmins ($iGroupProfileId, $iFanId);
            return true;
        }

        $iFanId = (int)$mixedFansIds;
        $sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ? AND `fan_id` = ?", $iGroupProfileId, $iFanId);
        if (!$this->res($sQuery))
            return false;

        $oModule = BxDolModule::getInstance($this->_oConfig->getName());
        if ($oModule && method_exists($oModule, 'onFanRemovedFromAdmins'))
            $oModule->onFanRemovedFromAdmins($iGroupProfileId, $iFanId);
        
        $oModule->doAudit($iGroupProfileId, $iFanId, '_sys_audit_action_group_from_admins');
        
        return true;
    }

    public function deleteAdminsByGroupId ($iGroupProfileId, $iProfileId = 0)
    {
        if ($iProfileId != 0)
            $sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ? AND `fan_id` = ?", $iGroupProfileId, $iProfileId);
        else
            $sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `group_profile_id` = ?", $iGroupProfileId);
        
        return $this->res($sQuery);
    }

    public function deleteAdminsByProfileId ($iProfileId)
    {
        $sQuery = $this->prepare("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE `fan_id` = ?", $iProfileId);
        return $this->res($sQuery);
    }

    public function isAdmin ($iGroupProfileId, $iFanId, $aDataEntry = array())
    {
        $CNF = &$this->_oConfig->CNF;

        if(isset($aDataEntry[$CNF['FIELD_AUTHOR']]) && $iFanId == $aDataEntry[$CNF['FIELD_AUTHOR']])
            return true;

        if(!isset($CNF['TABLE_ADMINS']))
            return false;

        $aBindings = array(
            'group_profile_id' => $iGroupProfileId, 
            'fan_id' => $iFanId, 
            'role' => BX_BASE_MOD_GROUPS_ROLE_COMMON
        );
        $sWhereClause = " AND `group_profile_id` = :group_profile_id AND `fan_id` = :fan_id AND `role` <> :role";

        return $this->getOne("SELECT `id` FROM `" . $CNF['TABLE_ADMINS'] . "` WHERE 1" . $sWhereClause, $aBindings) ? true : false;
    }

    public function getAdmin ($iGroupProfileId, $iProfileId, $bRoleOnly = false)
    {
        $CNF = &$this->_oConfig->CNF;

        $sMethod = 'getRow';
        $sSelectClause = '*';

        if($bRoleOnly) {
            $sMethod = 'getOne';
            $sSelectClause = '`role`';
        }

        $aBindings = array('group_profile_id' => $iGroupProfileId, 'fan_id' => $iProfileId, 'role' => BX_BASE_MOD_GROUPS_ROLE_COMMON);
        $sWhereClause = " AND `group_profile_id` = :group_profile_id AND `fan_id` = :fan_id AND `role` <> :role";

        return $this->$sMethod("SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_ADMINS'] . "` WHERE 1" . $sWhereClause . " LIMIT 1", $aBindings);
    }

    public function getAdmins ($iGroupProfileId, $iStart = 0, $iLimit = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $aBindings = array('group_profile_id' => $iGroupProfileId, 'role' => BX_BASE_MOD_GROUPS_ROLE_COMMON);
        $sWhereClause = " AND `group_profile_id` = :group_profile_id AND `role` <> :role";

        $sLimitClause = "";
        if($iLimit > 0)
            $sLimitClause = " LIMIT " . (int)$iStart . ", " . (int)$iLimit;

        return $this->getColumn("SELECT `fan_id` FROM `" . $CNF['TABLE_ADMINS'] . "` INNER JOIN `sys_profiles` as `p` ON (`p`.`id` = `fan_id` AND `p`.`status` = 'active') WHERE 1" . $sWhereClause . $sLimitClause, $aBindings);
    }

    public function getRole($iGroupProfileId, $iProfileId)
    {
        return (int)$this->getRoles(array('type' => 'by_gf_id', 'group_profile_id' => $iGroupProfileId, 'fan_id' => $iProfileId, 'role_only' => true));
    }

    public function getRoles($aParams)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`ta`.*";
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['value']
                );

                $sWhereClause = "AND `ta`.`id`=:id";
                break;

            case 'by_gf_id':
                $aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'group_profile_id' => $aParams['group_profile_id'],
                    'fan_id' => $aParams['fan_id'],
                );

                $sWhereClause = "AND `group_profile_id` = :group_profile_id AND `fan_id` = :fan_id";
                $sLimitClause = 1;

                if(!empty($aParams['role_only'])) {
                    $aMethod['name'] = 'getOne';
                    $sSelectClause = "`ta`.`role`";
                }
                break;

            case 'fan_pids_by_group_pid':
                $aMethod['name'] = 'getColumn';
            	$aMethod['params'][1] = array(
                    'group_profile_id' => $aParams['group_profile_id'],
                );

                $sSelectClause = "`ta`.`fan_id`";
                $sWhereClause = "AND `group_profile_id` = :group_profile_id";

                if(!empty($aParams['role'])) {
                    $aMethod['params'][1]['role'] = $aParams['role'];

                    $sWhereClause .= " AND `ta`.`role` = :role";
                }

                if(isset($aParams['start'], $aParams['limit']))
                    $sLimitClause = $aParams['start'] . ', ' . $aParams['limit'];
                break;

            case 'group_pids_by_fan_id':
                $aMethod['name'] = 'getColumn';
                $aMethod['params'][1] = [
                    'fan_id' => $aParams['fan_id'],
                ];

                $sSelectClause = "`ta`.`group_profile_id`";
                $sWhereClause = "AND `ta`.`fan_id` = :fan_id";

                if(!empty($aParams['role'])) {
                    $aMethod['params'][1]['role'] = $aParams['role'];

                    $sWhereClause .= " AND `ta`.`role` = :role";
                }
                break;

            case 'expired':
                $sWhereClause .= "AND `ta`.`added` < UNIX_TIMESTAMP() AND `ta`.`expired` <> 0 AND `ta`.`expired` < UNIX_TIMESTAMP()";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;
        
        if(!empty($sLimitClause))
            $sLimitClause = "LIMIT " . $sLimitClause;

        $sSql = "SELECT {select} FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` AS `ta` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " {order} {limit}";

        $aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array($sSelectClause, $sOrderClause, $sLimitClause), $sSql);
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function updateRoles($aSet, $aWhere)
    {
        $sWhereClause = 1;
        if(!empty($aWhere))
            $sWhereClause = $this->arrayToSQL($aWhere, ' AND ');

        return (int)$this->query("UPDATE `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . $sWhereClause) > 0;
    }
    
    public function deleteRoles($aWhere)
    {
    	if(empty($aWhere))
            return false;

        return (int)$this->query("DELETE FROM `" . $this->_oConfig->CNF['TABLE_ADMINS'] . "` WHERE " . $this->arrayToSQL($aWhere, ' AND ')) > 0;
    }

    public function setRole ($iGroupProfileId, $iProfileId, $mixedRole, $mixedPeriod = false, $sOrder = '')
    {
        $CNF = &$this->_oConfig->CNF;

        if($this->_oConfig->isMultiRoles() && is_array($mixedRole)) {
            if(count($mixedRole) != 1 || current($mixedRole) != 0) {
                $iProfileRole = 0;

                foreach($mixedRole as $iRole)
                    $iProfileRole = $iProfileRole | pow(2, ($iRole - 1));

                $mixedRole = $iProfileRole;
            }
            else 
                $mixedRole = 0;
        }

        $aBindings = array(
            'group_profile_id' => $iGroupProfileId,
            'fan_id' => $iProfileId,
            'role' => $mixedRole,
            'added' => time()
        );

        $sSetClause = "`group_profile_id` = :group_profile_id, `fan_id` = :fan_id, `role` = :role, `added` = :added";

        if(!empty($mixedPeriod)) {
            if(is_numeric($mixedPeriod))
        	$mixedPeriod = array('period' => (int)$mixedPeriod, 'period_unit' => BX_BASE_MOD_GROUPS_PERIOD_UNIT_DAY);

            $aBindings['period'] = (int)$mixedPeriod['period'];

            $sSetExpired = "";
            switch($mixedPeriod['period_unit']) {
                case BX_BASE_MOD_GROUPS_PERIOD_UNIT_DAY:
                case BX_BASE_MOD_GROUPS_PERIOD_UNIT_WEEK:
                    if($mixedPeriod['period_unit'] == BX_BASE_MOD_GROUPS_PERIOD_UNIT_WEEK)
                        $aBindings['period'] *= 7;

                    $sSetExpired = "DATE_ADD(FROM_UNIXTIME(:added), INTERVAL :period DAY)";
                    break;

                case BX_BASE_MOD_GROUPS_PERIOD_UNIT_MONTH:
                    $sSetExpired = "DATE_ADD(FROM_UNIXTIME(:added), INTERVAL :period MONTH)";
                    break;

                case BX_BASE_MOD_GROUPS_PERIOD_UNIT_YEAR:
                    $sSetExpired = "DATE_ADD(FROM_UNIXTIME(:added), INTERVAL :period YEAR)";
                    break;
            }

            if(!empty($sSetExpired) && !empty($mixedPeriod['period_reserve'])) {
                $aBindings['reserve'] = (int)$mixedPeriod['period_reserve'];

                $sSetExpired = "DATE_ADD(" . $sSetExpired . ", INTERVAL :reserve DAY)";
            }

            if(!empty($sSetExpired))
                $sSetClause .= ", `expired` = UNIX_TIMESTAMP(" . $sSetExpired . ")";
        }

        if(!empty($sOrder)) {
            $aBindings['order'] = $sOrder;
            
            $sSetClause .= ", `order` = :order";
        }

        return !$this->query("REPLACE INTO `" . $CNF['TABLE_ADMINS'] . "` SET " . $sSetClause, $aBindings) ? false : true;
    }

    public function unsetRole($iGroupProfileId, $iProfileId)
    {
        return $this->deleteRoles(array('group_profile_id' => $iGroupProfileId, 'fan_id' => $iProfileId));
    }

    public function getPrices($aParams)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`tp`.*";
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
            $sOrderClause = "ORDER BY `tp`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['value']
                );

                $sWhereClause .= "AND `tp`.`id`=:id";
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['value']
                );

                $sWhereClause .= "AND `tp`.`name`=:name";
                break;

            case 'by_profile_id':
            	$aMethod['params'][1] = array(
                    'profile_id' => $aParams['profile_id']
                );

                $sWhereClause .= "AND `tp`.`profile_id`=:profile_id";

                if(!empty($aParams['default'])) {
                    $aMethod['name'] = 'getRow';

                    $sWhereClause .= " AND `tp`.`default`='1'";
                }
                break;

            case 'by_prpp':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'profile_id' => $aParams['profile_id'],
                    'role_id' => $aParams['role_id'],
                    'period' => $aParams['period'],
                    'period_unit' => $aParams['period_unit'],
                );

                $sWhereClause .= "AND `tp`.`profile_id`=:profile_id AND `tp`.`role_id`=:role_id AND `tp`.`period`=:period AND `tp`.`period_unit`=:period_unit";
                break;
        }

        $sSql = "SELECT {select} FROM `" . $this->_oConfig->CNF['TABLE_PRICES'] . "` AS `tp` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " {order} {limit}";

        $aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array($sSelectClause, $sOrderClause, $sLimitClause), $sSql);
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function getPriceOrderMax($iRoleId)
    {
        return (int)$this->getOne("SELECT MAX(`order`) FROM `" . $this->_oConfig->CNF['TABLE_PRICES'] . "` WHERE `role_id`=:role_id", array(
            'role_id' => $iRoleId
        ));
    }

    public function updatePrices($aSet, $aWhere)
    {
        return (int)$this->query("UPDATE `" . $this->_oConfig->CNF['TABLE_PRICES'] . "` SET " . $this->arrayToSQL($aSet) . "  WHERE " . $this->arrayToSQL($aWhere, " AND ")) > 0;
    }
    
    public function deletePrices($aWhere)
    {
        return (int)$this->query("DELETE FROM `" . $this->_oConfig->CNF['TABLE_PRICES'] . "` WHERE " . $this->arrayToSQL($aWhere, " AND ")) > 0;
    }

    public function insertInvite($sKey, $sGroupProfileId, $iAuthorProfileId, $iInvitedProfileId)
    {
        $aBindings = array(
            'key' => $sKey,
            'group_profile_id' => $sGroupProfileId,
            'author_profile_id' => $iAuthorProfileId,
            'invited_profile_id' => $iInvitedProfileId,
            'added' => time()
        );
        $CNF = $this->_oConfig->CNF; 
        $this->query("INSERT `" . $CNF["TABLE_INVITES"] . "` (`key`, `group_profile_id`, `author_profile_id`, `invited_profile_id`, `added`) VALUES (:key, :group_profile_id, :author_profile_id, :invited_profile_id, :added)", $aBindings);
        return (int)$this->lastId();
    }

    public function getInvites($aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

        $sSelectClause = "`ti`.*";
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";
        switch($aParams['sample']) {
            case 'key_and_context_pid':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'key' => $aParams['key'],
                    'context_pid' => $aParams['context_pid']
                ];

                $sWhereClause = " AND `ti`.`key`=:key AND `ti`.`group_profile_id`=:context_pid";
                break;
            
            case 'invited_pid_and_context_pid':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'invited_pid' => $aParams['invited_pid'],
                    'context_pid' => $aParams['context_pid']
                ];

                $sWhereClause = " AND `ti`.`invited_profile_id`=:invited_pid AND `ti`.`group_profile_id`=:context_pid";
                break;

            case 'invited_pid':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'group_profile_id';
                $aMethod['params'][2] = [
                    'invited_pid' => $aParams['invited_pid'],
                ];

                $sWhereClause = " AND `ti`.`invited_profile_id`=:invited_pid";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = 'ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = 'LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF["TABLE_INVITES"] . "` AS `ti` " . $sJoinClause . " WHERE 1" . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }
    
    public function getInviteByKey($sKey, $iContextPid)
    {
        return $this->getInvites(['sample' => 'key_and_context_pid', 'key' => $sKey, 'context_pid' => $iContextPid]);
    }

    public function getInviteByInvited($iInvitedPid, $iContextPid)
    {
        return $this->getInvites(['sample' => 'invited_pid_and_context_pid', 'invited_pid' => $iInvitedPid, 'context_pid' => $iContextPid]);
    }

    public function isInviteByInvited($iInvitedPid, $iContextPid)
    {
        return ($aInvite = $this->getInviteByInvited($iInvitedPid, $iContextPid)) && is_array($aInvite);
    }

    public function updateInviteByKey($sKey, $iGroupProfileId, $sColumn, $sValue)
    {
        $aBindings = array(
           'key' => $sKey,
           'value' => $sValue,
           'group_profile_id' => $iGroupProfileId
       );
        $CNF = $this->_oConfig->CNF; 
        return $this->query("UPDATE `" . $CNF["TABLE_INVITES"] . "` SET `" . $sColumn . "` = :value WHERE `key` = :key AND group_profile_id = :group_profile_id", $aBindings);
    }
    
    public function deleteInviteByKey($sKey, $iGroupProfileId)
    {
        $aBindings = array(
           'key' => $sKey,
           'group_profile_id' => $iGroupProfileId
       );
        $CNF = $this->_oConfig->CNF; 
        return $this->query("DELETE FROM `" . $CNF["TABLE_INVITES"] . "` WHERE `key` = :key AND group_profile_id = :group_profile_id", $aBindings);
    }
    
    public function deleteInvite($iId)
    {
        $aBindings = array(
           'id' => $iId
       );
        $CNF = $this->_oConfig->CNF; 
        return $this->query("DELETE FROM `" . $CNF["TABLE_INVITES"] . "` WHERE `id` = :id", $aBindings);
    }

    public function getQuestions($aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

        $sSelectClause = "`tq`.*";
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";
        switch($aParams['sample']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause = " AND `tq`.`id`=:id";
                break;
            
             case 'content_id':
                $aMethod['params'][1] = [
                    'content_id' => $aParams['content_id']
                ];

                $sWhereClause = " AND `tq`.`content_id`=:content_id";
                $sOrderClause = "`tq`.`order` ASC";
                break;
            
            case 'content_pid':
                $aMethod['params'][1] = [
                    'module' => $this->_oConfig->getName(),
                    'content_pid' => $aParams['content_pid']
                ];

                $sJoinClause = "INNER JOIN `sys_profiles` AS `tp` ON `tq`.`content_id`=`tp`.`content_id` AND `tp`.`type`=:module";
                $sWhereClause = " AND `tp`.`id`=:content_pid";
                $sOrderClause = "`tq`.`order` ASC";
                break;

            case 'answers':
                $aMethod['params'][1] = [
                    'content_id' => $aParams['content_id'],
                    'profile_id' => $aParams['profile_id']
                ];

                $sSelectClause .= ", `ta`.`answer`";
                $sJoinClause = "INNER JOIN `" . $CNF['TABLE_ANSWERS'] . "` AS `ta` ON `tq`.`id`=`ta`.`question_id` AND `ta`.`profile_id`=:profile_id";
                $sWhereClause = " AND `tq`.`content_id`=:content_id";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = 'ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = 'LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_QUESTIONS'] . "` AS `tq` " . $sJoinClause . " WHERE 1" . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }
    
    public function hasQuestions($iContentId)
    {
        $aQuestions = $this->getQuestions([
            'sample' => 'content_id', 
            'content_id' => $iContentId
        ]);

        return !empty($aQuestions) && is_array($aQuestions);
    }
    
    public function areQuestionsAnswered($iContentId, $iProfileId)
    {
        $aQuestions = $this->getQuestions([
            'sample' => 'answers', 
            'content_id' => $iContentId,
            'profile_id' => $iProfileId
        ]);

        return !empty($aQuestions) && is_array($aQuestions);
    }

    public function getQuestionOrderMax($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        return (int)$this->getOne("SELECT MAX(`order`) FROM `" . $CNF['TABLE_QUESTIONS'] . "` WHERE `content_id`=:content_id", [
            'content_id' => $iContentId
        ]);
    }

    public function insertAnswer($iQuestionId, $iProfileId, $sAnswer)
    {
        $CNF = &$this->_oConfig->CNF;

        $sSetClause = $this->arrayToSQL([
            'question_id' => $iQuestionId, 
            'profile_id' => $iProfileId, 
            'answer' => $sAnswer
        ]);

        return $this->query("INSERT INTO `" . $CNF['TABLE_ANSWERS'] . "` SET " . $sSetClause . ", `added`=UNIX_TIMESTAMP() ON DUPLICATE KEY UPDATE `answer`=:answer, `added`=UNIX_TIMESTAMP()", [
            'answer' => $sAnswer
        ]) !== false ? (int)$this->lastId() : false;
    }

    public function deleteAnswersProfileId($iContentId, $iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("DELETE FROM `ta` USING `" . $CNF['TABLE_QUESTIONS'] . "` AS `tq` LEFT JOIN `" . $CNF['TABLE_ANSWERS'] . "` AS `ta` ON `tq`.`id`=`ta`.`question_id` WHERE `tq`.`content_id`=:content_id AND `ta`.`profile_id`=:profile_id", [
            'content_id' => $iContentId,
            'profile_id' => $iProfileId
        ]) !== false;
    }

    public function deleteQuestionnaires($iContentId) 
    {
        $CNF = &$this->_oConfig->CNF;

        $this->query("DELETE FROM `tq`, `ta` USING `" . $CNF['TABLE_QUESTIONS'] . "` AS `tq` LEFT JOIN `" . $CNF['TABLE_ANSWERS'] . "` AS `ta` ON `tq`.`id`=`ta`.`question_id` WHERE `tq`.`content_id`=:content_id", [
            'content_id' => $iContentId
        ]) !== false;
    }

    protected function _getEntriesBySearchIds($aParams, &$aMethod, &$sSelectClause, &$sJoinClause, &$sWhereClause, &$sOrderClause, &$sLimitClause)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!empty($CNF['FIELD_STATUS']))
            $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_STATUS'] . "`='active'";

        if(!empty($CNF['FIELD_STATUS_ADMIN']))
            $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_STATUS_ADMIN'] . "`='active'";

        parent::_getEntriesBySearchIds($aParams, $aMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);        
    }
}

/** @} */
