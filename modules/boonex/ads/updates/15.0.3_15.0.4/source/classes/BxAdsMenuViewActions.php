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

/**
 * View entry social actions menu
 */
class BxAdsMenuViewActions extends BxBaseModTextMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_ads';

        parent::__construct($aObject, $oTemplate);
    }

    public function getCode ()
    {
        $sCode = parent::getCode();

        if(!empty($this->_oMenuActions))
            $sCode .= $this->_oMenuActions->getJsCode();

    	return $sCode;
    }

    protected function _getMenuItemInterested($aItem)
    {
        $aResult = $this->_getMenuItemByNameActions($aItem);

        if($this->_bIsApi && is_array($aResult)) {
            return array_merge($aResult, [
                'display_type' => 'callback',
                'data' => [
                    'request_url' => $this->_sModule . '/interested/&params[]=' . $this->_iContentId, 
                    'on_callback' => 'hide'
                ]
            ]);
        }

        return $aResult;
    }

    protected function _getMenuItemAddToCart($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemMakeOffer($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemViewOffers($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemEditAd($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemEditAdBudget($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemViewAdPromotion($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemDeleteAd($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemShipped($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemReceived($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemReview($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return parent::_getMenuItemComment($aItem, array_merge($aParams, array(
            'object' => $CNF['OBJECT_REVIEWS']
        )));
    }
}

/** @} */
