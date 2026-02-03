/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 *
 * @{
 */

function BxCreditsWithdraw(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxCreditsWithdraw' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
}

BxCreditsWithdraw.prototype.getResult = function(oElement) {
    var fRate = parseFloat($('#' + this._aHtmlIds['withdraw_field_rate']).val());
    var fAmount = parseFloat($(oElement).val())

    $('#' + this._aHtmlIds['withdraw_field_result']).val(this._round(fRate * fAmount, 2));
};

BxCreditsWithdraw.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};

BxCreditsWithdraw.prototype.loadingInBox = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-base-text-poll:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxCreditsWithdraw.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxCreditsWithdraw.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

BxCreditsWithdraw.prototype._round = function(fNum, iPrecision = 0) {
    var iMux = Math.pow(10, iPrecision);
    var iNum = (fNum * iMux) * (1 + Number.EPSILON);
    return Math.round(iNum) / iMux;
}

/** @} */
