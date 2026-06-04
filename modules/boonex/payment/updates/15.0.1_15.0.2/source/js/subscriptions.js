/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPaymentSubscriptions(oOptions) {
    this.init(oOptions);
}

BxPaymentSubscriptions.prototype = new BxPaymentMain();

BxPaymentSubscriptions.prototype.init = function(oOptions) {
    if($.isEmptyObject(oOptions))
        return;

    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oPmtSubscriptions' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
};

BxPaymentSubscriptions.prototype.getDetails = function(oLink, iId) {
    this._performRequest(oLink, iId, 'subscription_get_details');
};

BxPaymentSubscriptions.prototype.changeDetails = function(oLink, iId) {
    this._performRequest(oLink, iId, 'subscription_change_details');
};

BxPaymentSubscriptions.prototype.getBilling = function(oLink, iId) {
    this._performRequest(oLink, iId, 'subscription_get_billing');
};

BxPaymentSubscriptions.prototype.changeBilling = function(oLink, iId) {
    this._performRequest(oLink, iId, 'subscription_change_billing');
};

BxPaymentSubscriptions.prototype.requestCancelation = function(oLink, iId) {
    this._performRequest(oLink, iId, 'subscription_cancelation');
};

BxPaymentSubscriptions.prototype.onSubmitCancelationReason = function(oForm) {
    oForm = $(oForm);

    if(oForm.find("[name = 'items']:checked").val() == 'rc_0' && oForm.find("[name = 'other']").val() == '') {
        oForm.find(".bx-payment-cs-other .bx-form-warn").html(_t('_bx_payment_form_input_err')).show();
        return false;
    }

    return true;
};

BxPaymentSubscriptions.prototype.onChangeCancelationReason = function(oElement) {
    var bOther = $(oElement).val() == 'rc_0';
    var oOther = $(oElement).parents('.bx-form-element-wrapper:first').siblings('.bx-payment-cs-other');
    oOther.toggleClass('bx-payment-cs-hidden', !bOther);
    if(!bOther)
        oOther.find("[name = 'other']").val('');
};

BxPaymentSubscriptions.prototype.cancel = function(oLink, iId, sGrid, iConfirm) {
    var $this = this;

    var oParams = {};
    if(sGrid != undefined)
        oParams.grid = sGrid;

    var fPerformYes = function() {
        $this._performRequest(oLink, iId, 'subscription_cancel', oParams);
    };

    if(iConfirm != undefined && parseInt(iConfirm) == 0)
        fPerformYes();
    else
        bx_confirm(_t('_bx_payment_msg_confirm_cancelation'), fPerformYes, function() {}, {
            yes: {
                title: _t('_bx_payment_txt_unsubscribe_yes')
            },
            no: {
                title: _t('_bx_payment_txt_unsubscribe_no')
            }
        });
};

BxPaymentSubscriptions.prototype._performRequest = function(oLink, iId, sUri, oParams) {
    var $this = this;
    var oDate = new Date();

    this.loadingInPopup(oLink, true);

    $.post(
        this._sActionsUrl + sUri + '/' + iId,
        $.extend({}, {_t:oDate.getTime()}, oParams),
        function(oData){
            $this.loadingInPopup(oLink, false);

            $(".bx-popup-applied:visible").dolPopupHide();

            processJsonData(oData);
        },
        'json'
    );
};

/** @} */
