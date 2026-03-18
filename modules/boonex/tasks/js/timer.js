/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTasksTimer(oOptions) {
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxTasksTimer' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    this._bMulti = oOptions.bMulti == undefined ? false : oOptions.bMulti;

    this._aTimers = [];
}

BxTasksTimer.prototype.init = function (iContentId, iProfileId, bStarted) {
    var $this = this;

    $(document).ready(function () {
        if(bStarted)
            $this._setTimer(iContentId, iProfileId);
    });
};

BxTasksTimer.prototype.start = function (oSource, iContentId, iProfileId) {
    if(this._bMulti)
        this.stopAll();

    this._performAction(oSource, 'start', iContentId, iProfileId);
};

BxTasksTimer.prototype.stop = function (oSource, iContentId, iProfileId) {
    this._clearTimer(iContentId, iProfileId);

    this._performAction(oSource, 'stop', iContentId, iProfileId);
};

BxTasksTimer.prototype.stopAll = function () {
    if(!this._aTimers)
        return;

    for(var sKey in this._aTimers) {
        var aKey = sKey.split('-');
        if(!aKey || aKey.length != 2)
            continue;
        
        var oTimer = $('#' + this._aHtmlIds['timer'] + aKey[0] + '-' + aKey[1]);
        if(!oTimer || !oTimer.length)
            continue;

        this.stop(oTimer.get(0), aKey[0],aKey[1]);
    }
};

BxTasksTimer.prototype.resume = function (oSource, iContentId, iProfileId) {
    if(this._bMulti)
        this.stopAll();

    this._performAction(oSource, 'resume', iContentId, iProfileId);
};

BxTasksTimer.prototype.log = function (oSource, iContentId, iProfileId) {
    this._clearTimer(iContentId, iProfileId);

    this._performAction(oSource, 'log', iContentId, iProfileId);
};

BxTasksTimer.prototype.clear = function (oSource, iContentId, iProfileId) {
    var $this = this;

    bx_confirm('', function() {
        $this._clearTimer(iContentId, iProfileId);

        $this._performAction(oSource, 'clear', iContentId, iProfileId);
    });
};

BxTasksTimer.prototype.onPerformActionStart = function(oData) {
    this._onPerformActionAndSet(oData);
};
BxTasksTimer.prototype.onPerformActionStop = function(oData) {
    this._onPerformAction(oData);
};
BxTasksTimer.prototype.onPerformActionResume = function(oData) {
    this._onPerformActionAndSet(oData);
};
BxTasksTimer.prototype.onPerformActionLog = function(oData) {
    this._onPerformAction(oData);
};
BxTasksTimer.prototype.onPerformActionClear = function(oData) {
    this._onPerformAction(oData);
};

BxTasksTimer.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);
};

BxTasksTimer.prototype.loadingInTimer = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-tasks-timer:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxTasksTimer.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxTasksTimer.prototype._performAction = function (oSource, sAction, iContentId, iProfileId) {
    var $this = this;
    var oDate = new Date();

    this.loadingInTimer(oSource, true);

    $.get(
        this._sActionsUrl + 'process_timer/' + sAction + '/' + iContentId + '/' + iProfileId, 
        {
            _t: oDate.getTime()
        },
        function(oData) {
            $this.loadingInTimer(oSource, false);

            processJsonData(oData);
        },
        'json'
    );

    return false;
};

BxTasksTimer.prototype._onPerformActionAndSet = function(oData) {
    if(oData && oData.content != undefined) {
        var sTimer = '#' + this._aHtmlIds['timer'] + oData.content_id + '-' + oData.profile_id;
        $(sTimer).replaceWith(oData.content);

        this._setTimer(oData.content_id, oData.profile_id, $(sTimer));        
    }
};

BxTasksTimer.prototype._onPerformAction = function(oData) {
    if(oData && oData.content != undefined)
        $('#' + this._aHtmlIds['timer'] + oData.content_id + '-' + oData.profile_id).replaceWith(oData.content);
};

BxTasksTimer.prototype._setTimer = function(iContentId, iProfileId, oTimer) {
    var $this = this;

    this._clearTimer(iContentId, iProfileId);

    if(!oTimer)
        oTimer = $('#' + this._aHtmlIds['timer'] + iContentId + '-' + iProfileId);

    this._aTimers[iContentId + '-' + iProfileId] = setInterval(function() {
        var oH = oTimer.find('.bx-tasks-th');
        var oM = oTimer.find('.bx-tasks-tm');
        var oS = oTimer.find('.bx-tasks-ts');

        var iS = parseInt(oS.html()) + 1;
        if(iS == 60) {
            iS = 0;

            var iM = parseInt(oM.html()) + 1;
            if(iM == 60) {
                iM = 0;

                oH.html($this._padDigits(parseInt(oH.html()) + 1, 2));
            }
            oM.html($this._padDigits(iM, 2));
        }
        oS.html($this._padDigits(iS, 2));
    }, 1000);
};

BxTasksTimer.prototype._clearTimer = function(iContentId, iProfileId) {
    var sTimer = iContentId + '-' + iProfileId;
    if(!this._aTimers[sTimer]) 
        return;

    clearInterval(this._aTimers[sTimer]);
    this._aTimers = this._aTimers.splice(sTimer, 1);
};

BxTasksTimer.prototype._clearTimers = function() {
    if(!this._aTimers)
        return;

    for(var sKey in this._aTimers)
        clearInterval(this._aTimers[sKey]);

    this._aTimers = [];
};

BxTasksTimer.prototype._padDigits = function(iNumber, iDigits) {
    return Array(Math.max(iDigits - String(iNumber).length + 1, 0)).join(0) + iNumber;
};
