<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxAdsDb extends BxBaseModTextDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getSourcesDetailsForm()
    {
        $sQuery = "SELECT
                `ts`.`id` AS `source_id`,
                `ts`.`name` AS `source_name`,
                `ts`.`caption` AS `source_caption`,
                `ts`.`description` AS `source_description`,
                `ts`.`option_prefix` AS `source_option_prefix`,
                `tso`.`id` AS `id`,
                `tso`.`name` AS `name`,
                `tso`.`type` AS `type`,
                `tso`.`caption` AS `caption`,
                `tso`.`description` AS `description`,
                `tso`.`extra` AS `extra`,
                `tso`.`check_type` AS `check_type`,
                `tso`.`check_params` AS `check_params`,
                `tso`.`check_error` AS `check_error`
            FROM `" . $this->_sPrefix . "sources` AS `ts`
            LEFT JOIN `" . $this->_sPrefix . "sources_options` AS `tso` ON `ts`.`id`=`tso`.`source_id`
            WHERE `ts`.`active`='1' 
            ORDER BY `ts`.`order` ASC, `tso`.`order` ASC";

        return $this->getAll($sQuery);
    }
    
    public function getSources($aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => [0 => 'query']);

        $sWhereClause = "";
        switch($aParams['sample']) {
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'name' => $aParams['name']
                );

                $sWhereClause = " AND `ts`.`name`=:name";
                break;

            case 'all':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'name';

                if(!empty($aParams['active'])) 
                    $sWhereClause = " AND `ts`.`active`='1'";
                break;
        }          

        $aMethod['params'][0] = "SELECT
                `ts`.`id` AS `id`,
                `ts`.`name` AS `name`,
                `ts`.`caption` AS `caption`,
                `ts`.`description` AS `description`,
                `ts`.`option_prefix` AS `option_prefix`,
                `ts`.`class_name` AS `class_name`,
                `ts`.`class_file` AS `class_file`
            FROM `" . $CNF['TABLE_SOURCES'] . "` AS `ts`
            WHERE 1" . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function getOption($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

        $sSelectClause = "`tso`.*";
        $sJoinClause = $sWhereClause = "";
        switch($aParams['sample']) {
            case 'by_pid_and_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'source_id' => $aParams['source_id'],
                    'name' => $aParams['name'],
                ];

                $sWhereClause = " AND `tso`.`source_id`=:source_id AND `tso`.`name`=:name";
                break;
        }

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_SOURCES_OPTIONS'] . "` AS `tso` " . $sJoinClause . " WHERE 1" . $sWhereClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function getSourcesOptions($iProfileId = 0, $iSourceId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

    	$aBinding = [
            'profile_id' => $iProfileId
    	];

        if(empty($iProfileId) && empty($iSourceId))
           return $this->getAll("SELECT `id`, `name`, `type` FROM `" . $CNF['TABLE_SOURCES_OPTIONS'] . "`");

        $sWhereAddon = "";
        if(!empty($iSourceId)) {
            $aBinding['source_id'] = $iSourceId;

            $sWhereAddon = " AND `tso`.`source_id`=:source_id";
        }

        $sQuery = "SELECT
               `tso`.`name` AS `name`,
               `tsov`.`value` AS `value`
            FROM `" . $CNF['TABLE_SOURCES_OPTIONS'] . "` AS `tso`
            LEFT JOIN `" . $CNF['TABLE_SOURCES_OPTIONS_VALUES'] . "` AS `tsov` ON `tso`.`id`=`tsov`.`option_id`
            WHERE 1" . $sWhereAddon . " AND `tsov`.`profile_id`=:profile_id ORDER BY `tso`.`order`";

        return $this->getAllWithKey($sQuery, 'name', $aBinding);
    }

    public function updateSourceOption($iProfileId, $iOptionId, $sValue)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("INSERT INTO `" . $CNF['TABLE_SOURCES_OPTIONS_VALUES'] . "` SET `profile_id`=:profile_id, `option_id`=:option_id, `value`=:value ON DUPLICATE KEY UPDATE `value`=:value", [
            'profile_id' => $iProfileId, 
            'option_id' => $iOptionId, 
            'value' => $sValue
        ]);
    }

    function getEntriesBy($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query', 1 => array()));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`" . $CNF['TABLE_ENTRIES'] . "`.*";

        switch($aParams['type']) {
            case 'purchased':
                $aMethod['params'][1] = array(
                    'buyer_id' => $aParams['buyer_id'],
                );

                $sJoinClause .= " INNER JOIN `" . $CNF['TABLE_LICENSES'] . "` ON `" . $CNF['TABLE_ENTRIES'] . "`.`id`=`" . $CNF['TABLE_LICENSES'] . "`.`entry_id` AND `" . $CNF['TABLE_LICENSES'] . "`.`profile_id`=:buyer_id";

                if(isset($aParams['count']) && $aParams['count'] === true) {
                    $aMethod['name'] = 'getOne';

                    $sSelectClause = "COUNT(`" . $CNF['TABLE_ENTRIES'] . "`.`id`)";
                }
                break;

            case 'shipped':
                $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_SHIPPED'] . "`<>0 AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_RECEIVED'] . "`=0";

                if(!empty($aParams['seller_id'])) {
                    $aMethod['params'][1] = array(
                        'seller_id' => $aParams['seller_id']
                    );

                    $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_AUTHOR'] . "`=:seller_id";
                }

                if(!empty($aParams['buyer_id'])) {
                    $aMethod['params'][1] = array(
                        'buyer_id' => $aParams['buyer_id'],
                        'status' => BX_ADS_OFFER_STATUS_ACCEPTED
                    );

                    $sJoinClause .= " INNER JOIN `" . $CNF['TABLE_OFFERS'] . "` ON `" . $CNF['TABLE_ENTRIES'] . "`.`id`=`" . $CNF['TABLE_OFFERS'] . "`.`content_id` AND `" . $CNF['TABLE_OFFERS'] . "`.`author_id`=:buyer_id AND `" . $CNF['TABLE_OFFERS'] . "`.`status`=:status";
                }

                if(isset($aParams['count']) && $aParams['count'] === true) {
                    $aMethod['name'] = 'getOne';

                    $sSelectClause = "COUNT(`" . $CNF['TABLE_ENTRIES'] . "`.`id`)";
                }
                break;

            case 'expired':
                $aMethod['params'][1]['days'] = 86400 * (int)$aParams['days'];

                $sWhereClause .= " AND UNIX_TIMESTAMP() - `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_CHANGED'] . "` > :days";
                break;

            case 'promotion':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'weight';
                $aMethod['params'][3] = [
                    'today' => $this->_oConfig->getDay(),
                    'promotion_cpm' => $this->_oConfig->getPromotionCpm(),
                ];

                $sSelectClause = "`" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ID'] . "` AS `id`, CAST(`" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_BUDGET_DAILY'] . "` AS UNSIGNED) AS `weight`";
                $sJoinClause .= " LEFT JOIN `" . $CNF['TABLE_PROMO_TRACKER'] . "` AS `tpt` ON `tpt`.`entry_id`=`" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ID'] . "` AND `tpt`.`date` = :today";
                //--- Check Total Budget
                $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_BUDGET_TOTAL'] . "` <> 0 AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_BUDGET_TOTAL'] . "` > (:promotion_cpm * `" . $CNF['TABLE_ENTRIES'] . "`.`impressions`)/1000";
                //--- Check Daily Budget
                $sWhereClause .= " AND (ISNULL(`tpt`.`impressions`) OR `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_BUDGET_DAILY'] . "` > (:promotion_cpm * `tpt`.`impressions`)/1000)";

                if(!empty($aParams['seg_viewer']) && is_array($aParams['seg_viewer'])) {
                    $sWhereSubclause = "1";
                    if(!empty($aParams['seg_viewer']['gender'])) {
                        $aMethod['params'][3]['seg_gender'] = $aParams['seg_viewer']['gender'];
                        $sWhereSubclause .= " AND IF(`seg_gender` <> 0, `seg_gender` & :seg_gender, 1)";
                    }

                    if(!empty($aParams['seg_viewer']['age'])) {
                        $aMethod['params'][3]['seg_age'] = $aParams['seg_viewer']['age'];
                        $sWhereSubclause .= " AND IF(`seg_age_min` <> 0 AND `seg_age_max` <> 0 AND `seg_age_min` <= `seg_age_max`, `seg_age_min` <= :seg_age AND `seg_age_max` >= :seg_age, 1)";
                    }

                    if(!empty($aParams['seg_viewer']['country'])) {
                        $aMethod['params'][3]['seg_country'] = $aParams['seg_viewer']['country'];
                        $sWhereSubclause .= " AND IF(`seg_country` <> '', `seg_country` = :seg_country, 1)";
                    }
                    
                    if(!empty($aParams['seg_viewer']['tags'])) {
                        $sTags = "0";
                        foreach($aParams['seg_viewer']['tags'] as $sTag) {
                            $aMethod['params'][3]['seg_tag_' . $sTag] = '%' . $sTag . '%';
                            $sTags .= " OR LOWER(`" . $CNF['FIELD_TAGS'] . "`) LIKE :seg_tag_" . $sTag;
                        }

                        $sWhereSubclause .= " AND IF(`seg_tags` <> 0, " . $sTags . ", 1)";
                    }

                    if(!empty($sWhereSubclause))
                        $sWhereClause .= " AND IF(`seg` = 1, " . $sWhereSubclause . ", 1)";
                }
//echo $sWhereClause; exit;
                $sOrderClause = "";
                break;

            default:
                return parent::getEntriesBy($aParams);
        }

        if(!empty($sOrderClause))
            $sOrderClause = 'ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = 'LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_ENTRIES'] . "` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
            return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertCategoryType($aParamsSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet))
            return 0;

        if((int)$this->query("INSERT INTO `" . $CNF['TABLE_CATEGORIES_TYPES'] . "` SET " . $this->arrayToSQL($aParamsSet)) <= 0)
            return 0;

        return (int)$this->lastId();
    }

    public function deleteCategoryType($aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsWhere))
            return false;

        return (int)$this->query("DELETE FROM `" . $CNF['TABLE_CATEGORIES_TYPES'] . "` WHERE " . $this->arrayToSQL($aParamsWhere, ' AND ')) > 0;
    }

    public function getCategoryTypes($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`tct`.*";
        $sWhereClause = $sOrderClause = "";
        
        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tct`.`id`=:id";
                break;
            
            case 'all':
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_CATEGORIES_TYPES'] . "` AS `tct`
            WHERE 1" . $sWhereClause . " " . $sOrderClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function updateCategory($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        return $this->query("UPDATE `" . $CNF['TABLE_CATEGORIES'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, ' AND ')) !== false;
    }

    public function getCategories($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`tc`.*";
        $sJoinClause = $sWhereClause = $sGroupClause = "";
        $sOrderClause = "`tc`.`order` ASC";

        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tc`.`id`=:id";
                break;

            case 'id_full':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sSelectClause .= ", `tct`.`name` AS `type_name`, `tct`.`display_add` AS `type_display_add`, `tct`.`display_edit` AS `type_display_edit`, `tct`.`display_view` AS `type_display_view`";
                $sJoinClause = " LEFT JOIN `" . $CNF['TABLE_CATEGORIES_TYPES'] . "` AS `tct` ON `tc`.`type`=`tct`.`id`";
                $sWhereClause = " AND `tc`.`id`=:id";
                break;

            case 'parent_id':
                $aMethod['params'][1] = array(
                    'parent_id' => $aParams['parent_id']
                );

                $sWhereClause = " AND `tc`.`parent_id`=:parent_id";
                if(isset($aParams['with_content']) && $aParams['with_content'] === true)
                    $sWhereClause .= " AND `tc`.`items`>0";
                if(isset($aParams['active']) && $aParams['active'] === true)
                    $sWhereClause .= " AND `tc`.`active`=1";
                break;

            case 'parent_id_count':
                $aMethod['name'] = 'getOne';
                $aMethod['params'][1] = array(
                    'parent_id' => $aParams['parent_id']
                );

                $sSelectClause = "COUNT(`tc`.`id`)";
                $sWhereClause = " AND `tc`.`parent_id`=:parent_id";
                if(isset($aParams['active']) && $aParams['active'] === true)
                    $sWhereClause .= " AND `tc`.`active`=1";
                break;
        
            case 'parent_id_order':
                $aMethod['name'] = 'getOne';
                $aMethod['params'][1] = array(
                    'parent_id' => $aParams['parent_id']
                );

                $sSelectClause = "MAX(`tc`.`order`)";
                $sWhereClause = " AND `tc`.`parent_id`=:parent_id";
                break;

            case 'collect_stats':
                $aMethod['params'][1] = [];

                $aStatusActive = $this->_oConfig->getActiveStatus();
                $aStatusActiveAdmin = $this->_oConfig->getActiveStatusAdmin();

                $sCountClause = "SELECT COUNT(`te`.`id`) FROM `" . $CNF['TABLE_ENTRIES'] . "` AS `te` INNER JOIN `sys_profiles` AS `p` ON (`p`.`id` = `te`.`" . $CNF['FIELD_AUTHOR'] . "` AND `p`.`status` = 'active') WHERE `tc`.`id`=`te`.`" . $CNF['FIELD_CATEGORY'] . "` AND `te`.`" . $CNF['FIELD_STATUS'] . "` IN (" . $this->implode_escape($aStatusActive) . ") AND `te`.`" . $CNF['FIELD_STATUS_ADMIN'] . "` IN (" . $this->implode_escape($aStatusActiveAdmin) . ") AND (`te`.`" . $CNF['FIELD_ALLOW_VIEW_TO'] . "`=" . BX_DOL_PG_ALL . " OR `te`.`" . $CNF['FIELD_ALLOW_VIEW_TO'] . "`<0)";
                $sSelectClause = "`tc`.`id`, (" . $sCountClause . ") AS `count`";

                if(isset($aParams['category_id'])) {
                    $aMethod['params'][1]['category_id'] = $aParams['category_id'];

                    $sWhereClause = " AND `tc`.`id`=:category_id";
                }
                break;
        }

        if(!empty($sGroupClause))
            $sGroupClause = "GROUP BY " . $sGroupClause;

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_CATEGORIES'] . "` AS `tc`" . $sJoinClause . " 
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function getDisplays($sDisplayPrefix = '', $mixedDisplayType = '')
    {
        $sWhereClause = "";
        $aBindings = array(
            'display_prefix' => '%' . (!empty($sDisplayPrefix) ? $sDisplayPrefix : $this->_oConfig->getName()) . '%'
        );

        if(!empty($mixedDisplayType)) {
            if(is_string($mixedDisplayType)) {
                $sWhereClause = " AND `display_name` LIKE :display_type";

                $aBindings['display_type'] = '%' . $mixedDisplayType . '%';
            }
            else if(is_array($mixedDisplayType)) {
                $aWhereClauseOr = array();
                foreach($mixedDisplayType as $iIndex => $sValue) {
                    $aWhereClauseOr[] = "`display_name` LIKE :display_type_" . $iIndex;

                    $aBindings['display_type_' . $iIndex] = '%' . $sValue . '%';
                }

                $sWhereClause = " AND (" . implode(" OR ", $aWhereClauseOr) . ")";
            }
        }

        return $this->getAll("SELECT * FROM `sys_form_displays` WHERE `display_name` LIKE :display_prefix" . $sWhereClause, $aBindings);
    }

    public function cloneDisplay($sDisplayName, $sNewDisplayName, $sNewDisplayTitle)
    {
        $aDisplay = $this->getRow("SELECT * FROM `sys_form_displays` WHERE `display_name`=:display_name", array('display_name' => $sDisplayName));
        if(empty($aDisplay) || !is_array($aDisplay))
            return false;
        
        unset($aDisplay['id']);
        $aDisplay['display_name'] = $sNewDisplayName;
        $aDisplay['title'] = $sNewDisplayTitle;

        if((int)$this->query("INSERT INTO `sys_form_displays` SET " . $this->arrayToSQL($aDisplay)) <= 0)
            return false;

        $iNewDisplayId = (int)$this->lastId();

        if((int)$this->query("INSERT INTO `sys_form_display_inputs` SELECT NULL, '" . $sNewDisplayName . "', `input_name`, `visible_for_levels`, `active`, `order` FROM `sys_form_display_inputs` WHERE `display_name`=:display_name AND `active`='1'", array('display_name' => $sDisplayName)) <= 0)
            return false;

        return true;
    }

    public function isInterested($iEntryId, $iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        return (int)$this->getOne("SELECT `id` FROM `" . $CNF['TABLE_INTERESTED_TRACK'] . "` WHERE `entry_id`=:entry_id AND `profile_id`=:profile_id LIMIT 1", array(
            'entry_id' => $iEntryId,
            'profile_id' => $iProfileId
        )) > 0;
    }

    public function getInterested($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`tit`.*";
        $sJoinClause = $sWhereClause = $sGroupClause = "";
        $sOrderClause = "`tit`.`id` ASC";

        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tit`.`id`=:id";
                break;
        }

        if(!empty($sGroupClause))
            $sGroupClause = "GROUP BY " . $sGroupClause;

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_INTERESTED_TRACK'] . "` AS `tit`" . $sJoinClause . " 
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
    
    public function insertInterested($aParamsSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet))
            return 0;

        $sSetClause = $this->arrayToSQL($aParamsSet);
        if(!isset($aParamsSet['date']))
            $sSetClause .= ", `date`=UNIX_TIMESTAMP()";

        if((int)$this->query("INSERT INTO `" . $CNF['TABLE_INTERESTED_TRACK'] . "` SET " . $sSetClause) <= 0)
            return 0;

        return (int)$this->lastId();
    }

    public function getCommodity($aParams = [])
    {
    	$CNF = &$this->_oConfig->CNF;
    	$aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

    	$sSelectClause = "`tc`.*";
    	$sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";
        switch($aParams['sample']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause = " AND `tc`.`id`=:id";
                break;

            case 'id_with_entry':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $aFields = $this->getFields($CNF['TABLE_ENTRIES']);
                $sFields = implode(", ", array_map(function($sValue) {
                    return "`te`.`{$sValue}` AS `entry_{$sValue}`";
                }, $aFields['original']));

                $sSelectClause = "`tc`.`id`, `tc`.`type`, `tc`.`amount`, `tc`.`added`, " . $sFields;
                $sJoinClause = " INNER JOIN `" . $CNF['TABLE_ENTRIES'] . "` AS `te` ON `tc`.`entry_id`=`te`.`id`";
                $sWhereClause = " AND `tc`.`id`=:id";
                break;

            case 'entry_id':
                $aMethod['params'][1] = [
                    'entry_id' => $aParams['entry_id']
                ];

                $sWhereClause = " AND `tc`.`entry_id`=:entry_id";
                
                if(!empty($aParams['type'])) {
                    $aMethod['params'][1]['type'] = $aParams['type'];

                    $sWhereClause .= " AND `tc`.`type`=:type";

                    if(isset($aParams['latest']) && $aParams['latest'] === true) {
                        $aMethod['name'] = 'getRow';

                        $sOrderClause = "`tc`.`added` DESC";
                        $sLimitClause = "1";
                    }

                    if(isset($aParams['unpaid']) && $aParams['unpaid'] === true) {
                        $aMethod['name'] = 'getRow';

                        $sJoinClause = " LEFT JOIN `" . $CNF['TABLE_PROMO_LICENSES'] . "` AS `tp` ON `tc`.`id`=`tp`.`commodity_id`";
                        $sWhereClause .= " AND ISNULL(`tp`.`commodity_id`)";
                        $sLimitClause = "1";
                    }
                }
                break;
                
            case 'entry_author':
                $aMethod['params'][1] = [
                    'author' => $aParams['author']
                ];
                
                $sJoinClause = " INNER JOIN `" . $CNF['TABLE_ENTRIES'] . "` AS `te` ON `tc`.`entry_id`=`te`.`id`";
                $sWhereClause = " AND `te`.`" . $CNF['FIELD_AUTHOR'] . "`=:author";
                if(!empty($aParams['type'])) {
                    $aMethod['params'][1]['type'] = $aParams['type'];

                    $sWhereClause .= " AND `tc`.`type`=:type";
                }
                break;
        }

        $sOrderClause = !empty($sOrderClause) ? "ORDER BY " . $sOrderClause : $sOrderClause;
        $sLimitClause = !empty($sLimitClause) ? "LIMIT " . $sLimitClause : $sLimitClause;

        $aMethod['params'][0] = "SELECT
            " . $sSelectClause . "
            FROM `" . $CNF['TABLE_COMMODITIES'] . "` AS `tc`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertCommodity($iEntryId, $sType, $fAmount)
    {
    	$CNF = &$this->_oConfig->CNF;

        $aQueryParams = [
            'entry_id' => $iEntryId,
            'type' => $sType,
            'amount' => $fAmount
        ];

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_COMMODITIES'] . "` SET " . $this->arrayToSQL($aQueryParams) . ", `added`=UNIX_TIMESTAMP()") > 0;
    }

    public function updateCommodity($aSet, $aWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        return (int)$this->query("UPDATE `" . $CNF['TABLE_COMMODITIES'] . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1")) > 0;
    }

    public function deleteCommodity($aWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        return (int)$this->query("DELETE FROM `" . $CNF['TABLE_COMMODITIES'] . "` WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1")) > 0;
    }

    public function registerLicense($iProfileId, $iEntryId, $iCount, $sOrder, $sLicense)
    {
    	$CNF = &$this->_oConfig->CNF;

        $aQueryParams = array(
            'profile_id' => $iProfileId,
            'entry_id' => $iEntryId,
            'count' => $iCount,
            'order' => $sOrder,
            'license' => $sLicense
        );

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_LICENSES'] . "` SET " . $this->arrayToSQL($aQueryParams) . ", `added`=UNIX_TIMESTAMP()") > 0;
    }

    public function unregisterLicense($iProfileId, $iEntryId, $sOrder, $sLicense)
    {
        $CNF = &$this->_oConfig->CNF;

    	$sWhereClause = "`profile_id`=:profile_id AND `entry_id`=:entry_id AND `order`=:order AND `license`=:license";
    	$aWhereBindings = array(
            'profile_id' => $iProfileId,
            'entry_id' => $iEntryId,
            'order' => $sOrder,
            'license' => $sLicense
    	);
    	
        //--- Move to deleted licenses table with 'refund' as reason.   
    	$sQuery = "INSERT IGNORE INTO `" . $CNF['TABLE_LICENSES_DELETED'] . "` SELECT *, 'refund' AS `reason`, UNIX_TIMESTAMP() AS `deleted` FROM `" . $CNF['TABLE_LICENSES'] . "` WHERE " . $sWhereClause;
            $this->query($sQuery, $aWhereBindings);

    	$sQuery = "DELETE FROM `" . $CNF['TABLE_LICENSES'] . "` WHERE " . $sWhereClause;
        return $this->query($sQuery, $aWhereBindings) !== false;
    }

    public function getLicense($aParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sSelectClause = "`tl`.*";
    	$sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";
        switch($aParams['type']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tl`.`id`=:id";
                break;

            case 'entry_id':
                $aMethod['params'][1] = array(
                    'entry_id' => $aParams['entry_id']
                );

                $sWhereClause = " AND `tl`.`entry_id`=:entry_id";
                $sOrderClause = "`tl`.`added` DESC";

                if(isset($aParams['newest']) && $aParams['newest'] === true) {
                    $aMethod['name'] = 'getRow';
                    $sLimitClause = "1";
                }
                break;
                
            case 'entry_id_income':
                $aMethod['name'] = "getOne";
                $aMethod['params'][1] = [
                    'entry_id' => $aParams['entry_id']
                ];

                $sSelectClause = "SUM(`te`.`" . $CNF['FIELD_PRICE'] . "` * `tl`.`count`)";
                $sWhereClause = " AND `tl`.`entry_id`=:entry_id";
                $sJoinClause = " INNER JOIN `" . $CNF['TABLE_ENTRIES'] . "` AS `te` ON `tl`.`entry_id`=`te`.`" . $CNF['FIELD_ID'] . "`";
                $sGroupClause = "`tl`.`entry_id`";
                break;

            case 'has_by':
                $aMethod['name'] = "getOne";
                $aMethod['params'][1] = array(
                    'profile_id' => $aParams['profile_id'],
                    'entry_id' => $aParams['entry_id']
                );

                $sSelectClause = "`tl`.`id`";
                $sWhereClause = " AND `tl`.`profile_id`=:profile_id AND `tl`.`entry_id`=:entry_id";

                if(!empty($aParams['order'])) {
                    $aMethod['params'][1]['order'] = $aParams['order'];
                    $sWhereClause .= " AND `tl`.`order`=:order";
                }

                $sLimitClause = "1";
                break;
        }

        $sGroupClause = !empty($sGroupClause) ? "GROUP BY " . $sGroupClause : $sGroupClause;
        $sOrderClause = !empty($sOrderClause) ? "ORDER BY " . $sOrderClause : $sOrderClause;
        $sLimitClause = !empty($sLimitClause) ? "LIMIT " . $sLimitClause : $sLimitClause;

        $aMethod['params'][0] = "SELECT
            " . $sSelectClause . "
            FROM `" . $this->_oConfig->CNF['TABLE_LICENSES'] . "` AS `tl`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function updateLicense($aSet, $aWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        return (int)$this->query("UPDATE `" . $CNF['TABLE_LICENSES'] . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1")) > 0;
    }

    public function deleteLicense($aWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        return (int)$this->query("DELETE FROM `" . $CNF['TABLE_LICENSES'] . "` WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1")) > 0;
    }

    public function hasLicense($iProfileId, $iEntryId)
    {
    	return (int)$this->getLicense(array(
            'type' => 'has_by', 
            'profile_id' => $iProfileId, 
            'entry_id' => $iEntryId
    	)) > 0;
    }

    public function registerPromotion($iProfileId, $iCommodityId, $iEntryId, $fAmount, $sOrder, $sLicense)
    {
    	$CNF = &$this->_oConfig->CNF;

        $aQueryParams = [
            'profile_id' => $iProfileId,
            'commodity_id' => $iCommodityId,
            'entry_id' => $iEntryId,
            'amount' => $fAmount,
            'order' => $sOrder,
            'license' => $sLicense
        ];

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_PROMO_LICENSES'] . "` SET " . $this->arrayToSQL($aQueryParams) . ", `added`=UNIX_TIMESTAMP()") > 0;
    }

    public function unregisterPromotion($iProfileId, $iCommodityId, $iEntryId, $sOrder, $sLicense)
    {
        $CNF = &$this->_oConfig->CNF;

        $aWhereBindings = [
            'profile_id' => $iProfileId,
            'commodity_id' => $iCommodityId,
            'entry_id' => $iEntryId,
            'order' => $sOrder,
            'license' => $sLicense
    	];

    	$sWhereClause = "`profile_id`=:profile_id AND `entry_id`=:entry_id AND `order`=:order AND `license`=:license";    	

        //--- Move to Deleted Promotions table with 'refund' as reason.
    	$sQuery = "INSERT IGNORE INTO `" . $CNF['TABLE_PROMO_LICENSES_DELETED'] . "` SELECT *, 'refund' AS `reason`, UNIX_TIMESTAMP() AS `deleted` FROM `" . $CNF['TABLE_PROMO_LICENSES'] . "` WHERE " . $sWhereClause;
            $this->query($sQuery, $aWhereBindings);

    	$sQuery = "DELETE FROM `" . $CNF['TABLE_PROMO_LICENSES'] . "` WHERE " . $sWhereClause;
        return $this->query($sQuery, $aWhereBindings) !== false;
    }

    public function getPromotionLicense($aParams = [])
    {
    	$CNF = &$this->_oConfig->CNF;
    	$aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

    	$sSelectClause = "`tl`.*";
    	$sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";
        switch($aParams['type']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause = " AND `tl`.`id`=:id";
                break;

            case 'entry_id_outcome':
                $aMethod['name'] = "getOne";
                $aMethod['params'][1] = [
                    'entry_id' => $aParams['entry_id']
                ];

                $sSelectClause = "SUM(`tl`.`amount`)";
                $sWhereClause = " AND `tl`.`entry_id`=:entry_id";
                $sGroupClause = "`tl`.`entry_id`";
                break;
        }

        $sGroupClause = !empty($sGroupClause) ? "GROUP BY " . $sGroupClause : $sGroupClause;
        $sOrderClause = !empty($sOrderClause) ? "ORDER BY " . $sOrderClause : $sOrderClause;
        $sLimitClause = !empty($sLimitClause) ? "LIMIT " . $sLimitClause : $sLimitClause;

        $aMethod['params'][0] = "SELECT
            " . $sSelectClause . "
            FROM `" . $CNF['TABLE_PROMO_LICENSES'] . "` AS `tl`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    protected function _getEntriesBySearchIds($aParams, &$aMethod, &$sSelectClause, &$sJoinClause, &$sWhereClause, &$sOrderClause, &$sLimitClause)
    {
        $CNF = &$this->_oConfig->CNF;

        foreach($aParams['search_params'] as $sSearchParam => $aSearchParam) {
            if($aSearchParam['operator'] != 'between')
                continue;
            
            if(!is_array($aSearchParam['value']) || count($aSearchParam['value']) != 2) 
                continue;

            foreach($aSearchParam['value'] as $iIndex => $sValue) {
                switch($sSearchParam) {
                    case $CNF['FIELD_PRICE']:
                        $sValue = (float)$sValue;
                        break;

                    case $CNF['FIELD_YEAR']:
                        $sValue = (int)$sValue;
                        break;
                }

                $aParams['search_params'][$sSearchParam]['value'][$iIndex] = $sValue;
            }
        }

        parent::_getEntriesBySearchIds($aParams, $aMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);
    }

    public function getOffersBy($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query', 1 => array()));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`to`.*";

        if(!empty($aParams['type']))
            switch($aParams['type']) {
                case 'quantity_reserved':
                    $aMethod['name'] = 'getOne';
                    $aMethod['params'][1]['content_id'] = (int)$aParams['content_id'];
                    $aMethod['params'][1]['status'] = BX_ADS_OFFER_STATUS_ACCEPTED;

                    $sSelectClause = "SUM(`to`.`quantity`)";
                    $sWhereClause = " AND `to`.`content_id`=:content_id AND `to`.`status`=:status";
                    break;
                
                case 'id':
                    $aMethod['name'] = 'getRow';
                    $aMethod['params'][1]['id'] = (int)$aParams['id'];

                    $sWhereClause = " AND `to`.`id`=:id";
                    break;

                case 'content_id':
                    $aMethod['params'][1]['content_id'] = (int)$aParams['content_id'];

                    $sWhereClause = " AND `to`.`content_id`=:content_id";

                    if(isset($aParams['count']) && $aParams['count'] === true) {
                        $aMethod['name'] = 'getOne';

                        $sSelectClause = "COUNT(`to`.`id`)";
                    }
                    else if(isset($aParams['highest']) && $aParams['highest'] === true) {
                        $aMethod['name'] = 'getRow';

                        $sOrderClause = "`to`.`amount` DESC";
                        $sLimitClause = 1;
                    }
                    break;

                case 'author_id':
                    $aMethod['params'][1]['author_id'] = (int)$aParams['author_id'];

                    $sWhereClause = " AND `to`.`author_id`=:author_id";

                    if(isset($aParams['count']) && $aParams['count'] === true) {
                        $aMethod['name'] = 'getOne';

                        $sSelectClause = "COUNT(`to`.`id`)";
                    }
                    break;

                case 'accepted':
                    $aMethod['name'] = 'getRow';
                    $aMethod['params'][1]['content_id'] = (int)$aParams['content_id'];
                    $aMethod['params'][1]['status'] = BX_ADS_OFFER_STATUS_ACCEPTED;

                    $sWhereClause = " AND `to`.`content_id`=:content_id AND `to`.`status`=:status";
                    break;

                case 'content_author_id':
                    $aMethod['params'][1]['author_id'] = (int)$aParams['author_id'];

                    $sJoinClause = "LEFT JOIN `" . $CNF['TABLE_ENTRIES'] . "` AS `te` ON `to`.`content_id`=`te`.`id`";
                    $sWhereClause = " AND `te`.`author`=:author_id";

                    if(!empty($aParams['status'])) {
                        $aMethod['params'][1]['status'] = $aParams['status'];

                        $sWhereClause .= " AND `to`.`status`=:status";
                    }

                    if(isset($aParams['count']) && $aParams['count'] === true) {
                        $aMethod['name'] = 'getOne';

                        $sSelectClause = "COUNT(`to`.`id`)";
                    }
                    break;

                case 'content_and_author_ids':
                    if(!isset($aParams['all']) || $aParams['all'] !== true)
                        $aMethod['name'] = 'getRow';

                    $aMethod['params'][1]['content_id'] = (int)$aParams['content_id'];
                    $aMethod['params'][1]['author_id'] = (int)$aParams['author_id'];

                    $sWhereClause = " AND `to`.`content_id`=:content_id AND `to`.`author_id`=:author_id";

                    if(!empty($aParams['status'])) {
                        $aMethod['params'][1]['status'] = $aParams['status'];

                        $sWhereClause .= " AND `to`.`status`=:status";
                    }

                    $sOrderClause = "`to`.`added` DESC";
                    break;

                case 'expired':
                    $aMethod['params'][1]['status'] = BX_ADS_OFFER_STATUS_AWAITING;
                    $aMethod['params'][1]['hours'] = 3600 * (int)$aParams['hours'];

                    $sWhereClause .= " AND `to`.`" . $CNF['FIELD_OFR_STATUS'] . "`=:status AND UNIX_TIMESTAMP() - `to`.`" . $CNF['FIELD_OFR_ADDED'] . "` > :hours";
                    break;
            }

        if(!empty($sGroupClause))
            $sGroupClause = ' GROUP BY ' . $sGroupClause;
    
        if(!empty($sOrderClause))
            $sOrderClause = ' ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = ' LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_OFFERS'] . "` AS `to` " . $sJoinClause . " WHERE 1 " . $sWhereClause . $sGroupClause . $sOrderClause . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertOffer($aSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($aSet[$CNF['FIELD_OFR_ADDED']]))
            $aSet[$CNF['FIELD_OFR_ADDED']] = time();

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_OFFERS'] . "` SET " . $this->arrayToSQL($aSet)) > 0 ? $this->lastId() : false;
    }

    public function updateOffer($aSet, $aWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($aSet[$CNF['FIELD_OFR_CHANGED']]))
            $aSet[$CNF['FIELD_OFR_CHANGED']] = time();

        return (int)$this->query("UPDATE `" . $CNF['TABLE_OFFERS'] . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1")) > 0;
    }

    public function deleteOffer($aWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aWhere) || !is_array($aWhere))
            return false;

        return (int)$this->query("DELETE FROM `" . $CNF['TABLE_OFFERS'] . "` WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1")) > 0;
    }

    public function getFromPromotionTracker($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = ['name' => 'getAll', 'params' => [0 => 'query', 1 => []]];
        $sSelectClause = "`tpt`.*";
        $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";       

        switch($aParams['sample']) {                
            case 'impressions_by_entry_id':
                $aMethod['name'] = 'getOne';
                $aMethod['params'][1]['entry_id'] = (int)$aParams['entry_id'];

                $sSelectClause = "SUM(`tpt`.`impressions`)";
                $sWhereClause = "AND `tpt`.`entry_id`=:entry_id";
                $sGroupClause = "`tpt`.`entry_id`";
                break;
        }

        if(!empty($sGroupClause))
            $sGroupClause = ' GROUP BY ' . $sGroupClause;
    
        if(!empty($sOrderClause))
            $sOrderClause = ' ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = ' LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_PROMO_TRACKER'] . "` AS `tpt` " . $sJoinClause . " WHERE 1 " . $sWhereClause . $sGroupClause . $sOrderClause . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function updatePromotionTracker($iEntryId, $sCounter, $iCounter = 1)
    {
        $CNF = &$this->_oConfig->CNF;

        if($this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET `" . $sCounter . "`=`" . $sCounter . "`+:counter WHERE `" . $CNF['FIELD_ID'] ."`=:id", ['counter' => $iCounter, 'id' => $iEntryId]) === false)
            return false;

        $iDate = $this->_oConfig->getDay();
        return $this->query("INSERT INTO `" . $CNF['TABLE_PROMO_TRACKER'] . "` (`entry_id`, `date`, `" . $sCounter . "`) VALUES (:entry_id, :date, :counter) ON DUPLICATE KEY UPDATE `" . $sCounter . "`=`" . $sCounter . "`+:counter", [
            'entry_id' => $iEntryId,
            'date' => $iDate,
            'counter' => $iCounter
        ]) !== false;
    }
}

/** @} */
