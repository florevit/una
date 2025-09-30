<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Basic Lucide iconset representation.
 * @see BxDolIconset
 */
class BxBaseIconsetLucide extends BxBaseIconset
{
    protected $_aMap;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_aMap = [
            'ad' => 'circle-star',
            'address-card' => 'id-card',
            'book-reader' => 'book-open-text',
            'building' => 'building',
            'bullhorn' => 'megaphone',
            'calendar-day' => 'calendar-days',
            'calendar-plus' => 'calendar-plus',
            'camera-retro' => 'camera',
            'cart-arrow-down' => 'shopping-cart',
            'cart-plus' => 'shopping-cart',
            'cc-stripe' => 'credit-card',
            'certificate' => 'award',
            'chart-pie' => 'pie-chart',
            'check-circle' => 'check-circle',
            'check-double' => 'check-check',
            'clipboard-check' => 'clipboard-check',
            'cog' => 'settings',
            'cogs' => 'settings',
            'comment' => 'message-circle',
            'comment-dots' => 'message-square-dashed',
            'comments' => 'messages-square',
            'donate' => 'hand-coins',
            'ellipsis-h' => 'more-horizontal',
            'ellipsis-v' => 'more-vertical',
            'envelope' => 'mail',
            'envelope-open-text' => 'mail-open',
            'exchange-alt' => 'repeat',
            'exclamation-circle' => 'alert-circle',
            'exclamation-triangle' => 'alert-triangle',
            'fa-book' => 'book-text',
            'fa-smile' => 'smile',
            'fa-thumbs-up' => 'thumbs-up',
            'file-alt' => 'file',
            'file-alt' => 'file-text',
            'file-export' => 'file-output',
            'file-invoice' => 'receipt',
            'file-word' => 'file-text',
            'group' => 'users',
            'hand-holding-usd' => 'hand-coins',
            'hashtag' => 'hash',
            'helpcircle' => 'help-circle',
            'house' => 'home',
            'info-circle' => 'info',
            'keyround' => 'key-round',
            'language' => 'languages',
            'lockopen' => 'unlock',
            'mail-bulk' => 'mails',
            'map-marker' => 'map-pin',
            'money-check-alt' => 'wallet-cards',
            'object-group' => 'layout-dashboard',
            'pencil-alt' => 'pencil-line',
            'pencil-ruler' => 'ruler',
            'photo-video' => 'image-play',
            'plus-circle' => 'plus-circle',
            'qrcode' => 'qr-code',
            'quote-right' => 'quote',
            'reply-all' => 'reply-all',
            'share-alt' => 'share-2',
            'shield-alt' => 'shield',
            'sign-in-alt' => 'log-in',
            'sign-out-alt' => 'log-out',
            'star-half-o' => 'star-half',
            'sync' => 'refresh-ccw',
            'tachometer-alt' => 'gauge',
            'tasks' => 'check-square',
            'thumbtack' => 'pin',
            'times' => 'x',
            'times-circle' => 'x-circle',
            'trash2' => 'trash-2',
            'unlock-alt' => 'unlock',
            'user-friends' => 'users',
            'user-round' => 'user-round',
            'user-slash' => 'user-x',
            'user-times' => 'user-x',
            'users-round' => 'users-round',
            'video-camera' => 'video',
        ];
    }

    public function getPreloaderJs()
    {
        return 'https://unpkg.com/lucide@latest';
    }

    public function getIcon($sIcon)
    {
        $sIcon = trim(preg_replace('/(sys-icon|far|col-\w+)/i', '', $sIcon));
        if(isset($this->_aMap[$sIcon]))
            $sIcon = $this->_aMap[$sIcon];

        return bx_gen_method_name($sIcon, ['_', '-']);
    }

    public function getCode()
    {
        $sMap = json_encode($this->_aMap);

        $sCode = <<<BLAH
        (function initClassOnlyLucide() {
            if(!window.lucide || !lucide.icons)
                return;
 
            const aMap = $sMap;
 
            document.querySelectorAll('i.sys-icon').forEach(el => {
                if(el.hasAttribute('data-lucide'))
                    return;

                const sName = el.getAttribute('class').replace(/(sys-icon|far|col-\w+)/gi, '').trim().split(' ').shift();
                if(sName)
                  el.setAttribute('data-lucide', aMap[sName] != undefined ? aMap[sName] : sName);
                else
                  console.warn('Lucide: no icon class found on', el);
            });

            lucide.createIcons({
              attrs: { class: ['sys-icon'] },
              nameAttr: 'data-lucide'
            });
        })();
BLAH;

        return $this->_oTemplate->_wrapInTagJsCode($sCode);
    }
}

/** @} */
