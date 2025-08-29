<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfileConfig extends BxBaseModGeneralConfig
{
    protected $_aMenuItems2MethodsActions = array();
    protected $_aMenuItems2MethodsSubmenu = array();

    protected $_aConnectionToFunctionCheck;

    protected $_bRoles;
    protected $_aRoles;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array(
            'PARAM_MULTICAT_ENABLED' => false,
        );
        
        $this->_aMenuItems2MethodsActions = array (
            'profile-friends' => 'checkAllowedFriends',
            'profile-friend-add' => 'checkAllowedFriendAdd',
            'profile-friend-remove' => 'checkAllowedFriendRemove',
            'profile-relations' => 'checkAllowedRelations',
            'profile-relation-add' => 'checkAllowedRelationAdd',
            'profile-relation-remove' => 'checkAllowedRelationRemove',
            'profile-subscriptions' => 'checkAllowedSubscriptions',
            'profile-subscribe-add' => 'checkAllowedSubscribeAdd',
            'profile-subscribe-remove' => 'checkAllowedSubscribeRemove',
            'profile-actions-more' => 'checkAllowedViewMoreMenu',
            'profile-set-acl-level' => 'checkAllowedSetMembership',
            'convos-compose' => 'checkAllowedCompose',
            'messenger' => 'checkAllowedCompose',
        );

        $this->_aConnectionToFunctionCheck = [
            'sys_profiles_friends' => [
                'friends' => 'checkAllowedFriends',
            	'add' => 'checkAllowedFriendAdd', 
            	'remove' => 'checkAllowedFriendRemove'
            ],
            'sys_profiles_subscriptions' => [
                'subscriptions' => 'checkAllowedSubscriptions',
                'add' => 'checkAllowedSubscribeAdd',
                'remove' => 'checkAllowedSubscribeRemove'
            ]
        ];

        $this->_bRoles = false;
        $this->_aRoles = false;
    }

    public function isFriends()
    {
        return ($sKey = 'PARAM_FRIENDS') && (!isset($this->CNF[$sKey]) || getParam($this->CNF[$sKey]) == 'on');
    }

    public function isRoles()
    {
        if($this->_aRoles === false)
            $this->_initRoles();

        return $this->_bRoles;
    }

    public function isMultiRoles()
    {
        return !empty($this->CNF['PARAM_MMODE']) && getParam($this->CNF['PARAM_MMODE']) == BX_BASE_MOD_GROUPS_MMODE_MULTI_ROLES;
    }

    public function getRoles()
    {
        if($this->_aRoles === false)
            $this->_initRoles();

        return $this->_aRoles;
    }

    public function roleIdI2S($iValue)
    {
        return 'r' . $iValue;
    }

    public function roleIdS2I($sValue)
    {
        return (int)substr($sValue, 1);
    }

    protected function _initRoles()
    {
        if(empty($this->CNF['OBJECT_PRE_LIST_ROLES'])) 
            return;

        $this->_aRoles = BxDolFormQuery::getDataItems($this->CNF['OBJECT_PRE_LIST_ROLES']);
        $this->_bRoles = !empty($this->_aRoles) && is_array($this->_aRoles);
    }

    public function getConnectionToFunctionCheck()
    {
        return $this->_aConnectionToFunctionCheck;
    }
}

/** @} */
