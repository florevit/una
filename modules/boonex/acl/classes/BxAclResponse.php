<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAclResponse extends BxDolAlertsResponse
{
    protected $MODULE;
    protected $_oModule;

    public function __construct()
    {
    	$this->MODULE = 'bx_acl';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct();
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
        $sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);           	
        if(!method_exists($this, $sMethod))
            return;

        $this->$sMethod($oAlert);
    }

    protected function _processSystemPageOutputBlockAclLevel($oAlert)
    {
        if((int)$oAlert->aExtras['block_owner'] != $this->_oModule->getUserId())
            return;

        $oAlert->aExtras['block_tmpl_vars']['bx_if:show_actions']['content']['bx_repeat:actions'][] = [
            'href' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($this->_oModule->_oConfig->CNF['URL_VIEW']),
            'bx_if:show_onclick' => [
                'condition' => false,
                'content' => [
                    'onclick' => ''
                ]
            ],
            'content' => _t('_bx_acl_txt_upgrade')
        ];

        $oAlert->aExtras['block_code'] = BxDolTemplate::getInstance()->parseHtmlByName($oAlert->aExtras['block_tmpl_name'], $oAlert->aExtras['block_tmpl_vars']);
    }

    protected function _processAclDeleted($oAlert)
    {
        $this->_oModule->_oDb->deletePrices(array('level_id' => $oAlert->iObject));
    }
}

/** @} */
