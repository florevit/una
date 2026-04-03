<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    GitHub GitHub
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGitHubDb extends BxDolModuleDb
{
    protected $_oConfig;

    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;
    }

    public function getSettings($aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

        $sSelectClause = "*";
        $sWhereClause = $sOrderByClause = "";

        switch($aParams['sample']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause .= " AND `id`=:id";
                break;

            case 'profile_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'profile_id' => $aParams['profile_id']
                ];

                $sWhereClause .= " AND `profile_id`=:profile_id";
                break;
        }

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_SETTINGS'] . "` WHERE 1 " . $sWhereClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }
}

/** @} */
