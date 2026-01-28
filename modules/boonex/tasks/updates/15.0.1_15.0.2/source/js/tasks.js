/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

function BxTasksView(oOptions) {
    this._oOptions = oOptions

    var $this = this;
    $(document).ready(function () {
        $this.init();
    });
}

BxTasksView.prototype.init = function () {
    var $this = this;
};

BxTasksView.prototype.setCompleted = function (iId, oObj) {
    var iVal = ($(oObj).prop('checked') ? 1 : 0);

    this._setCompleted(iId, iVal);
};

BxTasksView.prototype.setCompletedByMenu = function (iId, iValue, oObj) {
    $(oObj).addClass('bx-btn-disabled');

    this._setCompleted(iId, iValue, function(oData) {
        processJsonData(oData);
    });
};

BxTasksView.prototype._setCompleted = function (iId, iValue, onComplete) {
    $.getJSON(this._oOptions.sActionUrl + 'set_completed/' + iId + '/' + iValue + '/', function(oData) {
        if(typeof onComplete === 'function')
            onComplete(oData);
    });
};
	
BxTasksView.prototype.processTaskList = function (iContextId, iId, obj) {
    var $this = this;
    $(window).dolPopupAjax({
        url: $this._oOptions.sActionUrl + 'process_task_list_form/' + iContextId + '/' + iId + '/',
        closeOnOuterClick: false,
        removeOnClose: true
    });   
};

BxTasksView.prototype.deleteTaskList = function (iId, iContextId, obj) {
    var $this = this;
    bx_confirm($this._oOptions.t_confirm_block_deletion, function () {
        $.getJSON($this._oOptions.sActionUrl + 'delete_task_list/' + iId + '/' + iContextId + '/', {}, function (oData) {
            $this.reloadData(oData, iContextId)
        });
    });
};
	
BxTasksView.prototype.processTask = function (iContextId, iListId, oSource) {
    var $this = this;
    $(window).dolPopupAjax({
        url: $this._oOptions.sActionUrl + 'process_task_form/' + iContextId + '/' + iListId + '/',
        closeOnOuterClick: false,
        removeOnClose: true
    });
};

BxTasksView.prototype.processTaskEditType = function (iContentId, oSource) {
    this.processTaskEditProperty(iContentId, 'type', oSource);
};

BxTasksView.prototype.processTaskEditPriority = function (iContentId, oSource) {
    this.processTaskEditProperty(iContentId, 'priority', oSource);
};

BxTasksView.prototype.processTaskEditEstimate = function (iContentId, oSource) {
    this.processTaskEditProperty(iContentId, 'estimate', oSource);
};

BxTasksView.prototype.processTaskEditDueDate = function (iContentId, oSource) {
    this.processTaskEditProperty(iContentId, 'due_date', oSource);
};

BxTasksView.prototype.processTaskEditState = function (iContentId, oSource) {
    this.processTaskEditProperty(iContentId, 'state', oSource);
};

BxTasksView.prototype.processTaskEditProperty = function (iContentId, sProperty, oSource) {
    var $this = this;
    $(window).dolPopupAjax({
        url: $this._oOptions.sActionUrl + 'process_task_form_edit_property/' + iContentId + '/' + sProperty + '/',
        closeOnOuterClick: false,
        removeOnClose: true
    });
};

BxTasksView.prototype.reloadData = function (oData, iContextId) {
    $(".bx-popup-applied:visible").dolPopupHide();

    loadDynamicBlockAuto($('.bx-tasks-tasklist-add'), window.location.href )
};

BxTasksView.prototype.reload = function (oData) {
    $(".bx-popup-applied:visible").dolPopupHide();

    document.location = document.location;
};

BxTasksView.prototype.setFilter = function (iListId, object) {
    var $this = this;
    var val = $(object).val();

    $('#bx-tasks-tasklist-' + iListId).removeClass (function (index, className) {
    	return (className.match (/(^|\s)bx-tasks-tasklist-filter-\S+/g) || []).join(' ');
    });

    if(val)
        $('#bx-tasks-tasklist-' + iListId).addClass('bx-tasks-tasklist-filter-' + val);

    $.getJSON($this._oOptions.sActionUrl + 'setFilterValue/' + iListId + '/' + val + '/', function (oData) {});
};
