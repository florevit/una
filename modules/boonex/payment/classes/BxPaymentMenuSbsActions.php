<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */


class BxPaymentMenuSbsActions extends BxTemplMenu
{
    protected $_sModule;
    protected $_oModule;

    protected $_iPendingId;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_payment';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $oTemplate);

        if(($iPendingId = bx_get('id')) !== false && ($iPendingId = bx_process_input($iPendingId, BX_DATA_INT)))
            $this->setPendingId($iPendingId);
    }

    public function setContentParams($aParams)
    {
        parent::setContentParams($aParams);

        if(($iPendingId = $aParams['pending_id'] ?? false))
            $this->setPendingId($iPendingId);
    }

    public function setPendingId($iPendingId)
    {
        $this->_iPendingId = $iPendingId;

        $aPending = $this->_oModule->_oDb->getOrderPending(['type' => 'id', 'id' => $this->_iPendingId]);
        if(empty($aPending) || !is_array($aPending))
            return;

        $sMethod = 'getMenuItemsActionsRecurring';
        $oProvider = $this->_oModule->getObjectProvider($aPending['provider'], $aPending['seller_id']);
        if($oProvider === false || !$oProvider->isActive() || !method_exists($oProvider, $sMethod))
            return;

        $this->_aObject['menu_items'] = array_merge($oProvider->$sMethod($aPending['client_id'], $aPending['seller_id'], [
            'id' => $aPending['id'],
            'order' => $aPending['order']
        ]), $this->getMenuItemsRaw());

        $aMarkers = [
            'js_object' => $this->_oModule->_oConfig->getJsObject('subscription'),
            'id' => $this->_iPendingId
        ];

        if(($sGrid = bx_get('grid')) !== false)
            $aMarkers['grid'] = bx_process_input($sGrid);

        $this->addMarkers($aMarkers);
    }

    public function getCode()
    {
        if(empty($this->_aObject['menu_items']) || !is_array($this->_aObject['menu_items']))
            return MsgBox(_t('_Empty'));

        return parent::getCode();
    }

    protected function _getMenuCallbackDataAPI($a)
    {
        $aResult = [];

        switch($a['name']) {
            case 'sbs-cancel':
                $aResult = [
                    'request_url' => $this->_sModule . '/cancel_by_pending_id/Subscriptions&params[]=' . $this->_iPendingId . '&params[]=1', 
                    'on_callback' => 'hide_row',
                    'id' => $this->_iPendingId
                ];
                break;
        }

        return $aResult;
    }
}

/** @} */
