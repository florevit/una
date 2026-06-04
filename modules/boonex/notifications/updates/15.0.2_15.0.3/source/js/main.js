function BxNtfsMain(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxNtfsMain.prototype.onChangeType = function(oElement) {
    var $this = this;
    var oData = this._getDefaultData();

    jQuery.get(
        this._sActionsUrl + 'get_action/' + $(oElement).val(),
        oData,
        function(oData) {
            if(!oData || oData.code != 0)
                return;

            $('#' + $this._aHtmlIds['field_action']).html(oData.content).removeAttr('disabled');
        },
        'json'
    );

    return false;
};

BxNtfsMain.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};

BxNtfsMain.prototype.loadingInItem = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-ntfs-item:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxNtfsMain.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxNtfsMain.prototype._loading = function(e, bShow) {
    var oParent = $(e).length ? $(e) : $('body'); 
    bx_loading(oParent, bShow);
};

BxNtfsMain.prototype._getDefaultData = function () {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};
