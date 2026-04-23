/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTasksPreValues(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxTasksPreValues' : oOptions.sObjName;
    this._sPageUrl = oOptions.sPageUrl == undefined ? '' : oOptions.sPageUrl;
    
}

BxTasksPreValues.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxTasksPreValues.prototype.constructor = BxTasksPreValues;

BxTasksPreValues.prototype.onChangeList = function(oElement) {
    var sList = $(oElement).val();
    document.location.href = this._sPageUrl + (sList.length > 0 ? '&list=' + sList : ''); 
};

BxTasksPreValues.prototype.onChangeFilter = function(oFilter) {
    var $this = this;

    var oSearch = $('#bx-grid-search-' + this._sObjNameGrid);
    var sValueSearch = oSearch.length > 0 ? oSearch.val() : '';
    if(sValueSearch == _t('_sys_grid_search'))
        sValueSearch = '';

    clearTimeout($this._iSearchTimeoutId);
    $this._iSearchTimeoutId = setTimeout(function () {
        glGrids[$this._sObjNameGrid].setFilter(sValueSearch, true);
    }, 500);
};

/** @} */
