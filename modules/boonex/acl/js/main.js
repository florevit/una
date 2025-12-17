/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAclMain(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oAclMain' : oOptions.sObjName;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxAclMain.prototype.viewActions = function(oElement, iLevelId) {
    var $this = this;
    var oData = this._getDefaultData();

    if(oElement)
        this.loadingInBlock(oElement, true);

    jQuery.get(
        this._sActionsUrl + 'get_actions_in_popup/' + iLevelId + '/',
        oData,
        function(oData) {
            if(oElement)
                $this.loadingInBlock(oElement, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxAclMain.prototype.getActions = function(oElement, iLevelId, iStart, iPerPage) {
    var $this = this;
    var oData = this._getData({
        start: iStart,
        per_page: iPerPage
    });

    if(oElement)
        this.loadingInList(oElement, true);

    jQuery.get(
        this._sActionsUrl + 'get_actions/' + iLevelId + '/',
        oData,
        function(oData) {
            if(oElement)
                $this.loadingInList(oElement, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxAclMain.prototype.onGetActions = function(oData)
{
    if(oData && oData.level_id != undefined && oData.content != undefined)
        $('#' + this._aHtmlIds['actions_list'] + oData.level_id).replaceWith(oData.content);
};

BxAclMain.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxAclMain.prototype.loadingInList = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-acl-actions:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxAclMain.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};

BxAclMain.prototype._getData = function (aData) {
    return jQuery.extend({}, this._getDefaultData(), aData);
};

BxAclMain.prototype._getDefaultData = function () {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
