<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxTasksDb extends BxBaseModTextDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
	
    public function getLists ($iContextId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $sQuery = $this->prepare ("SELECT * FROM `" . $CNF['TABLE_LISTS'] . "` WHERE `context_id` = ?", $iContextId);
        return $this->getAll($sQuery);
    }
	
    public function getList ($iId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $sQuery = $this->prepare ("SELECT * FROM `" . $CNF['TABLE_LISTS'] . "` WHERE `id` = ?", $iId);
        return $this->getRow($sQuery);
    }
    
    public function deleteList ($iId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $sQuery = $this->prepare ("DELETE FROM `" . $CNF['TABLE_LISTS'] . "` WHERE `id` = ?", $iId);
        $this->query($sQuery);
        
        $sQuery = $this->prepare ("DELETE FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_TASKLIST'] . "` = ?", $iId);
        $this->query($sQuery);
    }
	
    public function getTasks ($iContextId = 0, $iListId = 0, $bWithStats = false)
    {
        $CNF = &$this->_oConfig->CNF;

        $aBindings = [];
        $sSelectClause = "`te`.*";
        $sJoinClause = $sWhereClause = "";

        if(($sField = $CNF['FIELD_ALLOW_VIEW_TO']) && $iContextId) {
            $aBindings[$sField] = $iContextId;
            $sWhereClause .= " AND `te`.`" . $sField . "` = :" . $sField;
        }

        if(($sField = $CNF['FIELD_TASKLIST']) && $iListId) {
            $aBindings[$sField] = $iListId;
            $sWhereClause .= " AND `te`.`" . $sField . "` = :" . $sField;
        }

        if(($oCf = BxDolContentFilter::getInstance()) && $oCf->isEnabled())
            $sWhereClause .= $oCf->getSQLParts('te', $CNF['FIELD_CF']);

        if($bWithStats) {
            $sSelectClause .= $this->prepareAsString(", (SELECT SUM(`value`) FROM `" . $CNF['TABLE_TIME_TRACK'] . "` WHERE `object_id`=`te`.`id` AND `author_id`=?) AS `time`, `tt`.`sum` AS `time_total`", bx_get_logged_profile_id());

            $sJoinClause .= " LEFT JOIN `" . $CNF['TABLE_TIME'] . "` AS `tt` ON `te`.`id`=`tt`.`object_id`";
        }

        return $this->getAll("SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_ENTRIES'] . "` AS `te`" . $sJoinClause . " WHERE 1" . $sWhereClause, $aBindings);
    }

    public function getEntriesByDate($sDateFrom, $sDateTo, $aSQLPart = array())
    {
        // validate input data
        if (false === ($oDateFrom = date_create($sDateFrom, new DateTimeZone('UTC'))))
            return array();
        if (false === ($oDateTo = date_create($sDateTo, new DateTimeZone('UTC'))))
            return array();
        if ($oDateFrom > $oDateTo)
            return array();

        // increase start and end date to cover timezones
        $oDateFrom = $oDateFrom->sub(new DateInterval("P1D"));
        $oDateTo = $oDateTo->add(new DateInterval("P1D"));

        // look throught all days in the interval
        $oDateIter = clone($oDateFrom);
        $aEntries = array();
        while ($oDateIter->format('Y-m-d') != $oDateTo->format('Y-m-d')) {

            $oDateMin = date_create($oDateIter->format('Y-m-d') . '00:00:00', new DateTimeZone('UTC'));
            $oDateMax = date_create($oDateIter->format('Y-m-d') . '23:59:59', new DateTimeZone('UTC'));
                
            // get all events for the specific day            
            $oDateMonthBegin = date_create($oDateIter->format('Y-m-01'), new DateTimeZone('UTC'));
            $iWeekOfMonth = $oDateIter->format('W') - $oDateMonthBegin->format('W') + 1;
            $aBindings = array(
                'timestamp_min' => $oDateMin->getTimestamp(),
                'timestamp_max' => $oDateMax->getTimestamp(),
              
            );

            $sWhere = isset($aSQLPart['where']) ? $aSQLPart['where'] : '';
            $a = $this->getAll("SELECT DISTINCT `bx_tasks_tasks`.`id`, `bx_tasks_tasks`.`title` AS `title`, `bx_tasks_tasks`.`due_date`
				FROM `bx_tasks_tasks`
                WHERE (`bx_tasks_tasks`.`due_date` >= :timestamp_min AND `bx_tasks_tasks`.`due_date` <= :timestamp_max ) $sWhere
            ", $aBindings);

            // prepare variables for each event
            $sCurrentDay = $oDateIter->format('Y-m-d');
            foreach ($a as $k => $r) {
                $oDateStart = new DateTime();
                $oDateStart->setTimestamp($r['due_date']);
                $oDateStart->setTimezone(new DateTimeZone('UTC'));
                $oDateEnd = new DateTime();
                $oDateEnd->setTimestamp($r['due_date']);
                $oDateEnd->setTimezone(new DateTimeZone('UTC'));
                $oDuration = $oDateStart->diff($oDateEnd);

                $sHoursStart = $oDateStart->format('H:i:s');

                $oStart = date_create($sCurrentDay . ' ' . $sHoursStart, new DateTimeZone('UTC'));
                $oEnd = $oStart ? clone($oStart) : null;
                $oEnd = $oEnd ? $oEnd->add($oDuration) : null;

                $a[$k]['start'] = $oStart ? $oStart->format('Y-m-d') : 0;
                $a[$k]['start_utc'] = $oStart ? $oStart->getTimestamp() : 0;
                $a[$k]['url'] = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oConfig->CNF['URI_VIEW_ENTRY'] . '&id=' . $r['id']));
            }

            // merge with all other events
            $aEntries = array_merge($aEntries, $a);

            // go to the next day
            $oDateIter = $oDateIter->add(new DateInterval("P1D"));
        }

        return $aEntries;
    }

    public function expireEntries()
    {
        $CNF = $this->_oConfig->CNF;

        $aResult = $this->getColumn("SELECT `id` FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_DUE_DATE'] . "` < UNIX_TIMESTAMP()  AND `" . $CNF['FIELD_EXPIRED'] . "` = '0'");
        if(empty($aResult) || !is_array($aResult))
            return false;

        return count($aResult) == (int)$this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET `" . $CNF['FIELD_EXPIRED'] . "` = '1' WHERE `id` IN (" . $this->implode_escape($aResult) . ")") ? $aResult : false;
    }
    
    public function getTimeTracks($aParams = []) 
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '`ttt`.*';
        $sJoinClause = $sWhereClause = $sOrderClause = '';

        if(!empty($aParams))
            switch($aParams['sample']) {
                case 'id':
                    $aMethod['name'] = 'getRow';
                    $aMethod['params'][1] = [
                        'id' => $aParams['id']
                    ];

                    $sWhereClause = "AND `ttt`.`id` = :id";
                    break;

                case 'assignees':
                    $aMethod['name'] = 'getColumn';

                    $sSelectClause = 'DISTINCT `ttt`.`author_id`';
                    break;
            }

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT 
                " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_TIME_TRACK'] . "` AS `ttt` " . $sJoinClause . " 
            WHERE 1 " . $sWhereClause . " " . $sOrderClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }
}

/** @} */
