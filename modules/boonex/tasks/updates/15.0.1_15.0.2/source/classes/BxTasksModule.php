<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Task states
 */
define('BX_TASKS_STATE_BACBLOG', 1);
define('BX_TASKS_STATE_TODO', 2);
define('BX_TASKS_STATE_IN_PROGRESS', 3);
define('BX_TASKS_STATE_IN_REVIEW', 4);
define('BX_TASKS_STATE_CANCELLED', 5);
define('BX_TASKS_STATE_DUPLICATE', 6);
define('BX_TASKS_STATE_DONE', 7);

class BxTasksModule extends BxBaseModTextModule implements iBxDolCalendarService 
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
            $CNF['FIELD_PUBLISHED'],
            $CNF['FIELD_ALLOW_COMMENTS']
        ));
    }
	
    /**
    * Action methods
    */
	
    /**
     * Get possible recipients for start conversation form
     */
    public function actionAjaxGetInitialMembers($iContextPid)
    {
        $sTerm = bx_get('term');
        $sModule = $this->_oConfig->getName();

        $a = bx_srv('system', 'profiles_search', [$sTerm, [
            'module' => $sModule,
            'search_params' => ['name' => $sModule . '_initial_members', 'context_pid' => $iContextPid]
        ]], 'TemplServiceProfiles');

        header('Content-Type:text/javascript; charset=utf-8');
        echo(json_encode($a));
    }
          
    public function actionSetCompleted($iContentId, $iCompleted)
    {
        if(!$this->isAllowManage($iContentId) || !$this->complete($iContentId, $iCompleted))
            return echoJson(['code' => 1]);

        return echoJson(['code' => 0, 'reload' => 1]);
    }
	
    public function actionSetFilterValue($iListId, $sValue)
    {
        $CNF = &$this->_oConfig->CNF;

        $aTmp = array();
        if (isset($_COOKIE[$CNF['COOKIE_SETTING_KEY']]))
            $aTmp = json_decode($_COOKIE[$CNF['COOKIE_SETTING_KEY']], true);

        if ($sValue != '')
            $aTmp[$iListId] = $sValue;
        else
            unset($aTmp[$iListId]);

        bx_setcookie($CNF['COOKIE_SETTING_KEY'], json_encode($aTmp), time() + 60*60*24*365);
    }
	
    public function actionProcessTaskListForm($iContextId, $iId)
    {
        if (!$this->isAllowAdd(-$iContextId))
            return;

        $CNF = &$this->_oConfig->CNF;

        $oForm = null;
        $sPopupTitle = "";
        $aContentInfo = array();
        if ($iId == 0){
            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_LIST_ENTRY'], $CNF['OBJECT_FORM_LIST_ENTRY_DISPLAY_ADD']);
            $sPopupTitle = _t('_bx_tasks_form_list_entry_display_add');
        }
        else {
            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_LIST_ENTRY'], $CNF['OBJECT_FORM_LIST_ENTRY_DISPLAY_EDIT']);
            $aContentInfo = $this->_oDb->getList($iId);
            $sPopupTitle = _t('_bx_tasks_form_list_entry_display_edit');
        }

        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'process_task_list_form/' . $iContextId . '/' . $iId . '/';
        if (!$oForm)
            return '';
		
        $oForm->initChecker($aContentInfo, array());
        if($oForm->isSubmittedAndValid()) {
            if ($iId == 0){
                $aValsToAdd['context_id'] = $iContextId;
                $iId = $oForm->insert($aValsToAdd);
            }
            else {
                $iId = $oForm->update($iId);
            }

            return echoJson(array(
                'eval' => $this->_oConfig->getJsObject('tasks') . '.reloadData(oData, ' . $iContextId . ')',
            ));
        }
        else {	
            $sContent = $this->_oTemplate->parseHtmlByName('popup_form.html', array(
                    'form_id' => $oForm->getId(),
                    'form' => $oForm->getCode(true)
            ));

            if (!$oForm->isSubmitted()) {
                    echo $sContent;
                    return;
            }

            return echoJson(array('form' => $sContent, 'form_id' => $oForm->getId()));
        }
    }
    
    public function actionDeleteTaskList($iId, $iContextId)
    {
        if (!$this->isAllowManageByContext($iContextId))
            return;

        $CNF = &$this->_oConfig->CNF;

        $aTasks = $this->_oDb->getTasks($iContextId, $iId);
        $this->_oDb->deleteList($iId);
        if (!empty($aTasks) && ($oConn = BxDolConnection::getObjectInstance($this->_oConfig->CNF['OBJECT_CONNECTION']))) {
            foreach ($aTasks as &$aTask)
                $oConn->onDeleteContent($aTask[$CNF['FIELD_ID']]);
        }

        echoJson(array(
            'context_id' => $iContextId,
        ));
    }
	
    public function actionProcessTaskForm($iContextId, $iListId)
    {
        $_iContextId = abs($iContextId);

        if(!$this->isAllowAdd($_iContextId))
            return echoJson([]);

        $CNF = &$this->_oConfig->CNF;

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']);
        if(!$oForm)
            return echoJson([]);

        $oForm->setAction(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'process_task_form/' . $iContextId . '/' . $iListId . '/');
        $oForm->setContextId($_iContextId);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iContentId = $oForm->insert([$CNF['FIELD_ALLOW_VIEW_TO'] => $iContextId, $CNF['FIELD_TASKLIST'] => $iListId]);
            if($iContentId)
                $this->onPublished($iContentId);

            return echoJson([
                'eval' => $this->_oConfig->getJsObject('tasks') . '.reloadData(oData, ' . $iContextId . ')',
            ]);
        }
        else {
            $sContent = $this->_oTemplate->parseHtmlByName('popup_form.html', [
                'form_id' => $oForm->getId(),
                'form' => $oForm->getCode(true)
            ]);

            if($oForm->isSubmitted()) 
                return echoJson(['form' => $sContent, 'form_id' => $oForm->getId()]);

            echo $sContent;
        }
    }

    public function actionProcessTaskFormEditProperty($iContentId, $sProperty)
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(!$this->isAllowAdd(abs($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']])))
            return '';

        $sForm = '';
        if(($sKey = 'OBJECT_FORM_ENTRY_DISPLAY_EDIT_' . strtoupper($sProperty)) && !empty($CNF[$sKey]))
            $sForm = $CNF[$sKey];
        else
            return '';

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $sForm);
        $oForm->setId($sForm);
        $oForm->setName($sForm);
        $oForm->setAction(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'process_task_form_edit_property/' . $iContentId . '/' . $sProperty . '/');
        if(!$oForm)
            return '';

        $oForm->initChecker($aContentInfo);
        if($oForm->isSubmittedAndValid()) {
            if(!$oForm->update($iContentId))
                return echoJson(['msg' => _t('_bx_tasks_txt_err_cannot_perform_action')]);

            if(($sMethod = '_onEdit' . bx_gen_method_name($sProperty)) && method_exists($this, $sMethod))
                $this->$sMethod($aContentInfo, $oForm);
            else
                $this->_onEditProperty($aContentInfo, $sProperty, $oForm);

            return echoJson([
                'eval' => $this->_oConfig->getJsObject('tasks') . '.reload(oData)',
            ]);
        }
        else {
            $sContent = $this->_oTemplate->parseHtmlByName('popup_form.html', [
                'form_id' => $oForm->getId(),
                'form' => $oForm->getCode(true)
            ]);
																	 
            if (!$oForm->isSubmitted()) {
                echo $sContent;
                return;
            }

            return echoJson(['form' => $sContent, 'form_id' => $oForm->getId()]);
        }
    }

    public function actionCalendarData()
    {
        // check permissions
        $aSQLPart = array();
        $iContextId = (int)bx_get('context_id');
        
        if(!$this->isAllowView($iContextId))
            return; 
		
        $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);

        if($iContextId) {
            $aSQLPart = $oPrivacy ? $oPrivacy->getContentByGroupAsSQLPart(- $iContextId) : array();
        }

        // get entries
        $aEntries = $this->_oDb->getEntriesByDate(bx_get('start'), bx_get('end'), bx_get('event'), $aSQLPart);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($aEntries);
    }

    public function serviceManageTools($sType = 'common')
    {
        $CNF = &$this->_oConfig->CNF;

        $mixedResults = '';
        if(!($mixedResults = parent::serviceManageTools($sType)))
            return $mixedResults;

        return array_merge(parent::serviceManageTools($sType), [
            'menu' => BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_MANAGE_TOOLS_SUBMENU'])
        ]);
    }

    public function serviceGetBlockMenuContext($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        $oMenu = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_SUBMENU_VIEW_CONTEXT']);
        if(!$oMenu)
            return '';

        $oMenu->addMarkers(['profile_id' => $iProfileId]);
        return $oMenu->getCode();
    }

    public function serviceGetBlockManageTime($sType = 'common', $iContextPid = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $bContextPid = !empty($iContextPid);
        
        $sGrid = $CNF['OBJECT_GRID_TIME_' . ($bContextPid ? 'CONTEXT_' : '') . strtoupper($sType)];
        $oGrid = BxDolGrid::getObjectInstance($sGrid);
        if(!$oGrid)
            return $this->_bIsApi ? [] : '';

        if($bContextPid)
            $oGrid->setContextPid($iContextPid);

        if($this->_bIsApi)
            return [
                bx_api_get_block('grid', $oGrid->getCodeAPI())
            ];

        list($aCssCalendar, $aJsCalendar) = BxBaseFormView::getCssJsCalendar();

        $this->_oTemplate->addCss(array_merge($aCssCalendar, ['manage_tools.css', 'time.css']));
        $this->_oTemplate->addJs(array_merge($aJsCalendar, ['modules/base/text/js/|manage_tools.js', 'time.js']));
        $this->_oTemplate->addJsTranslation(['_sys_grid_search']);
        $aResult = [
            'content' => $this->_oTemplate->getJsCode('time', [
                'sObjNameGrid' => $sGrid
            ]) . $oGrid->getCode()
        ];

        if(!$bContextPid)
            $aResult['menu'] = BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_MANAGE_TOOLS_SUBMENU']);

        return $aResult;
    }

    /**
     * Data for Timeline module
     */
    public function serviceGetTimelineData()
    {
    	$sModule = $this->_aModule['name'];
        return array(
            'handlers' => array(
                array('group' => $sModule . '_object', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'added', 'module_name' => $sModule, 'module_method' => 'get_timeline_post', 'module_class' => 'Module', 'groupable' => 0, 'group_by' => ''),
                array('group' => $sModule . '_completed', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'completed', 'module_name' => $sModule, 'module_method' => 'get_timeline_completed', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
                array('group' => $sModule . '_reopened', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'reopened', 'module_name' => $sModule, 'module_method' => 'get_timeline_reopened', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
                array('group' => $sModule . '_object', 'type' => 'update', 'alert_unit' => $sModule, 'alert_action' => 'edited'),
                array('group' => $sModule . '_object', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'deleted'),
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'added'),
                array('unit' => $sModule, 'action' => 'completed'),
                array('unit' => $sModule, 'action' => 'reopened'),
                array('unit' => $sModule, 'action' => 'edited'),
                array('unit' => $sModule, 'action' => 'deleted'),
            )
        );
    }

    /**
     * Entry task for Timeline module
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];

        return $aResult;
    }
	
    public function serviceGetTimelineCompleted($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];

        $aResult['sample_action'] = $aResult['content']['sample_action'] = _t('_bx_tasks_txt_action_completed');
        return $aResult;
    }

    public function serviceGetTimelineReopened($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];

        $aResult['sample_action'] = $aResult['content']['sample_action'] = _t('_bx_tasks_txt_action_reopened');
        return $aResult;
    }

    public function serviceGetNotificationsData()
    {
        $sModule = $this->_aModule['name'];

        $sEventPrivacy = $sModule . '_allow_view_event_to';
        if(BxDolPrivacy::getObjectInstance($sEventPrivacy) === false)
            $sEventPrivacy = '';

        $aResult = parent::serviceGetNotificationsData();
        $aResult['handlers'] = array_merge($aResult['handlers'], array(
            array('group' => $sModule . '_completed', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'completed', 'module_name' => $sModule, 'module_method' => 'get_notifications_completed', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_reopened', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'reopened', 'module_name' => $sModule, 'module_method' => 'get_notifications_reopened', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_expired', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'expired', 'module_name' => $sModule, 'module_method' => 'get_notifications_expired', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),

            array('group' => $sModule . '_assign', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'assigned', 'module_name' => $sModule, 'module_method' => 'get_notifications_assigned', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_assign', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'unassigned'),
        ));

        $aResult['settings'] = array_merge($aResult['settings'], array(
            array('group' => 'content', 'unit' => $sModule, 'action' => 'completed', 'types' => array('personal', 'follow_member', 'follow_context')),
            array('group' => 'content', 'unit' => $sModule, 'action' => 'reopened', 'types' => array('personal', 'follow_member', 'follow_context')),
            array('group' => 'content', 'unit' => $sModule, 'action' => 'expired', 'types' => array('personal')),
            array('group' => 'content', 'unit' => $sModule, 'action' => 'assigned', 'types' => array('personal')),
        ));

        $aResult['alerts'] = array_merge($aResult['alerts'], array(
            array('unit' => $sModule, 'action' => 'completed'),
            array('unit' => $sModule, 'action' => 'reopened'),
            array('unit' => $sModule, 'action' => 'expired'),

            array('unit' => $sModule, 'action' => 'assigned'),
            array('unit' => $sModule, 'action' => 'unassigned'),
        ));

        return $aResult; 
    }

    public function serviceGetNotificationsCompleted($aEvent)
    {
        return $this->_serviceGetNotificationsByAction($aEvent, 'completed');
    }

    public function serviceGetNotificationsReopened($aEvent)
    {
        return $this->_serviceGetNotificationsByAction($aEvent, 'reopened');
    }

    public function serviceGetNotificationsExpired($aEvent)
    {
        return $this->_serviceGetNotificationsByAction($aEvent, 'expired');
    }

    public function serviceGetNotificationsAssigned($aEvent)
    {
        return $this->_serviceGetNotificationsByAction($aEvent, 'assigned');
    }

    protected function _serviceGetNotificationsByAction($aEvent, $sAction)
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetNotificationsPost($aEvent);
        if(empty($aResult) || !is_array($aResult))
            return $aResult;

        $aResult['entry_author'] = $aEvent['object_owner_id'];
        $aResult['entry_author_name'] = '';
        if(($oAuthor = BxDolProfile::getInstance($aResult['entry_author'])) !== false)
            $aResult['entry_author_name'] = $oAuthor->getDisplayName();

        $sLangKey = '_bx_tasks_txt_notification_' . $sAction;
        if((int)$aEvent['object_privacy_view'] < 0)
            $sLangKey .= '_in_context';

        $aResult['lang_key'] = _t($sLangKey);
        return $aResult;
    }

    public function serviceCheckAllowedManageInContext($iContextPid)
    {
        if(!$this->isAllowManageByContext($iContextPid))
            return false;

        return true;
    }

    public function serviceCheckAllowedManage($iContentId)
    {
        if(!$this->isAllowManage($iContentId))
            return false;

        return true;
    }

    public function serviceCheckAllowedComplete($iContentId)
    {
        if(!$this->serviceCheckAllowedManage($iContentId))
            return false;

        return !$this->isCompleted($iContentId);
    }
    
    public function serviceCheckAllowedUncomplete($iContentId)
    {
        if(!$this->serviceCheckAllowedManage($iContentId))
            return false;

        return $this->isCompleted($iContentId);
    }

    public function serviceIsCompleted($iContentId)
    {
        return $this->isCompleted($iContentId);
    }

    public function serviceIsUncompleted($iContentId)
    {
        return !$this->isCompleted($iContentId);
    }
    
    public function serviceIsAllowBadges($iContentId)
    {
        if (!$this->isAllowManage($iContentId))
            return false;
        
        if (!$this->serviceIsBadgesAvaliable())
            return false;
        
        return true; 
    }

    public function serviceEntityAssignments($iContentId = 0, $bAsArray = false)
    {
        if(!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(!$iContentId)
            return false;

        $CNF = &$this->_oConfig->CNF;

        $aProfiles = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION'])->getConnectedInitiators($iContentId);
        if($bAsArray)
            return $aProfiles;

        return $this->_oTemplate->entryAssignments($aProfiles);
    }

    public function serviceCheckAllowedCommentsTask($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if($aContentInfo[$CNF['FIELD_ALLOW_COMMENTS']] == 0)
            return false;

        return parent::serviceCheckAllowedCommentsTask($iContentId, $sObjectComments);
    }

    public function serviceCheckAllowedCommentsView($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;

        //negative id used in comments for reports
        if($iContentId < 0)
            return CHECK_ACTION_RESULT_ALLOWED;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if($aContentInfo[$CNF['FIELD_ALLOW_COMMENTS']] == 0)
            return false;

        return parent::serviceCheckAllowedCommentsView($iContentId, $sObjectComments);
    }
	
    /**
     * @page service Service Calls
     * @section bx_tasks Tasks
     * @subsection bx_tasks-page_blocks Page Blocks
     * @subsubsection bx_tasks-calendar calendar
     * 
     * @code bx_srv('bx_tasks', 'calendar', [...]); @endcode
     * 
     * Shows tasks calendar baced on die date
     * 
     * @param $aData additional data to point which events to show, leave empty to show all events, specify event's ID in 'event' array key to show calendar for one event only, specify context's ID in 'context_id' array key to show calendar for one context events only. If only one event is specified then it will show calendar only if it's repeating event.
     * @param $sTemplate template to use to show calendar, or leave empty for default template, possible options: calendar.html, calendar_compact.html
     * @return HTML string with calendar to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML. On error empty string is returned.
     *
     * @see BxTasksModule::serviceCalendar
     */
    /** 
     * @ref bx_tasks-calendar "calendar"
     */
    public function serviceCalendar($aData = array(), $sTemplate = 'calendar.html')
    {
        if (!$this->isAllowView($aData['context_id']))
            return; 
        
        $o = new BxTemplCalendar(array(
            'eventSources' => array (
                bx_append_url_params(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'calendar_data', $aData),
            ),
        ), $this->_oTemplate);
        return $o->display($sTemplate);
    }
	
    public function serviceGetCalendarEntries($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;
        $oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION']);
        $aData = $oConn->getConnectedInitiators($iProfileId);
        $aData2 = array(0);
        foreach($aData as $iProfileId2) {
            $oProfile = BxDolProfile::getInstance($iProfileId2);
            array_push($aData2, $oProfile->getContentId());
        }
        $aSQLPart['where'] = " AND " . $CNF['TABLE_ENTRIES'] . ".`" . $CNF['FIELD_ID'] . "` IN(" . implode(',', $aData2) . ")";
        return $this->_oDb->getEntriesByDate(bx_get('start'), bx_get('end'), null, $aSQLPart);
    }
	
    public function serviceBrowseContext ($iProfileId = 0, $aParams = array())
    {
        if(!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if(!$iProfileId)
            return '';

        return $this->serviceBrowseTasks (-$iProfileId, $aParams);
    }
	
    public function serviceBrowseTasks ($iContextId = 0, $aParams = array())
    {
        $_iContextId = abs($iContextId);

        if(!$this->isAllowView($_iContextId))
            return;  

        if(!($oProfileContext = BxDolProfile::getInstance($_iContextId)) || $oProfileContext->checkAllowedProfileView($_iContextId) !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        return $this->_oTemplate->getEntriesList($iContextId);
    }

    /**
     * Common methods
     */
    public function getModuleTitle($sName)
    {
        $sResult = $sName;

        if(($_sTitle = '_' . $sName) && ($sTitle = _t($_sTitle)) && strcmp($_sTitle, $sTitle) != 0)
            $sResult = $sTitle;
        else if(($aModule = $this->_oModule->_oDb->getModuleByName($sName)) && is_array($aModule))
            $sResult = $aModule['title'] ?? _t('_undefined');

        return $sResult;
    }

    public function getAssignees()
    {
        $aResults = [];
        if(($oProfile = BxDolProfile::getInstance()) !== false && ($aIds = $this->_oDb->getTimeTracks(['sample' => 'assignees'])))
            foreach($aIds as $iId)
                $aResults[] = ['key' => $iId, 'value' => $oProfile->getDisplayName($iId)];

        return $aResults;
    }

    public function getContexts()
    {
        $aContexts = [];

        $aModules = bx_srv('system', 'get_modules_by_type', ['context', ['name_as_key' => true]]);
        foreach($aModules as $sModule => $aModule)
            $aContexts[$sModule] = ($_sTitle = '_' . $sModule) && ($sTitle = _t($_sTitle)) && strcmp($_sTitle, $sTitle) != 0 ? $sTitle : $aModule['title'];
        
        return $aContexts; 
    }
    
    public function getContextMembers($iContextPid)
    {
        $aMembers = [];
        if(($oContext = BxDolProfile::getInstance($iContextPid)) !== false) {
            $aPids = bx_srv($oContext->getModule(), 'fans', [$oContext->getContentId(), true]);
            foreach($aPids as $iPid)
                $aMembers[$iPid] = $oContext->getDisplayName($iPid);
        }

        return $aMembers;
    }

    public function getContextEntries($iContextPid)
    {
        $CNF = &$this->_oConfig->CNF;

        $aEntries = [];
        if(($aTasks = $this->_oDb->getTasks(-$iContextPid)) && is_array($aTasks))
            foreach($aTasks as $aTask)
                $aEntries[$aTask[$CNF['FIELD_ID']]] = $aTask[$CNF['FIELD_TITLE']];

        return $aEntries;
    }

    public function onExpired($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION']);
        if($oConnection) {
            $aProfileIds = $oConnection->getConnectedContent($iContentId);
            if(!empty($aProfileIds) && is_array($aProfileIds))
                foreach($aProfileIds as $iProfileId){
                    /**
                     * @hooks
                     * @hookdef hook-bx_tasks-expired 'bx_tasks', 'expired' - hook on task unassigned to profile
                     * - $unit_name - equals `bx_tasks`
                     * - $action - equals `expired`
                     * - $object_id - task id 
                     * - $sender_id - not used 
                     * - $extra_params - array of additional params with the following array keys:
                     *      - `object_author_id` - [int] profile_id for task's author
                     *      - `privacy_view` - [string] privacy view value
                     * @hook @ref hook-bx_tasks-expired
                     */
                    bx_alert($this->getName(), 'expired', $iContentId, false, array(
                        'object_author_id' => $iProfileId,
                        'privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]
                    ));
                }
        }
    }
    
    public function isAllowView($iContextId)
    {
        if(!($oContext = BxDolProfile::getInstance($iContextId)) || $oContext->checkAllowedProfileView($iContextId) !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        return true;
    }

    public function isAllowAdd($iContextId)
    {
        if(!($oContext = BxDolProfile::getInstance($iContextId)) || $oContext->checkAllowedPostInProfile($iContextId) !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        return true;
    }

    public function isAllowManageByContext($iContextId)
    {
        if(isAdmin())
            return true;
      
        $oProfileContext = BxDolProfile::getInstance($iContextId);
        if(BxDolService::call($oProfileContext->getModule(), 'is_admin', array($iContextId)))
            return true;
        
        return false;
    }
    
    public function isAllowManage($mixedContent)
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = !is_array($mixedContent) ? $this->_oDb->getContentInfoById((int)$mixedContent) : $mixedContent;
        if($this->checkAllowedEdit($aContentInfo) === CHECK_ACTION_RESULT_ALLOWED)
            return true;

        if($this->isAllowManageByContext(abs($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']])))
            return true;

        if(($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION'])) !== false) {
            $iLoggedId = bx_get_logged_profile_id();
            $aProfileIds = $oConnection->getConnectedInitiators($aContentInfo[$CNF['FIELD_ID']]);
            if(!empty($aProfileIds) && is_array($aProfileIds) && in_array($iLoggedId, $aProfileIds))
                return true;
        }

        return false;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedManage ($aDataEntry, $isPerformAction = false)
    {
        return $this->isAllowManage($aDataEntry) ? CHECK_ACTION_RESULT_ALLOWED : _t('_sys_txt_access_denied');
    }

    public function isCompleted($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        return (bool)$aContentInfo[$CNF['FIELD_COMPLETED']];
    }

    public function complete($iContentId, $iCompleted)
    {
        $CNF = &$this->_oConfig->CNF;

        $iCompleted = (int)$iCompleted;
        $this->_oDb->updateEntriesBy([$CNF['FIELD_COMPLETED'] => $iCompleted], [$CNF['FIELD_ID'] => (int)$iContentId]);

        $sModule = $this->getName();
        $sAction = !$iCompleted ? 'reopened' : 'completed';

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        $iContentAuthor = (int)$aContentInfo[$CNF['FIELD_AUTHOR']];
        /**
         * @hooks
         * @hookdef hook-bx_tasks-completed 'bx_tasks', 'completed' - hook on task unassigned to profile
         * - $unit_name - equals `bx_tasks`
         * - $action - can be `completed` or `reopened`
         * - $object_id - task id 
         * - $sender_id - not used 
         * - $extra_params - array of additional params with the following array keys:
         *      - `object_author_id` - [int] profile_id for task's author
         *      - `privacy_view` - [string] privacy view value
         * @hook @ref hook-bx_tasks-completed
         */
        bx_alert($sModule, $sAction, $iContentId, false, array(
            'object_author_id' => $iContentAuthor,
            'privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]
        ));

        if(($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION'])) !== false) {
            $aProfileIds = $oConnection->getConnectedContent($iContentId);
            if(!empty($aProfileIds) && is_array($aProfileIds))
                foreach($aProfileIds as $iProfileId) {
                    if($iProfileId == $iContentAuthor)
                        continue;

                    bx_alert($sModule, $sAction, $iContentId, false, array(
                        'object_author_id' => $iProfileId,
                        'privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]
                    ));
                }
        }

        $this->logActivity($iContentId, ['key' => '_bx_tasks_txt_msg_' . ($iCompleted ? '' : 'un') . 'completed']);

        return true;
    }

    public function logActivity($iContentId, $aMessage)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $oCmts = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId);
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

        if(($oAuthor = BxDolProfile::getInstance()) !== false)
            $aMessage['markers'] = array_merge($aMessage['markers'] ?? [], [
                'author_link' => $oAuthor->getUrl(),
                'author_name' => $oAuthor->getDisplayName(),
            ]);

        return $oCmts->addAuto($aMessage);
    }

    /**
     * Internal methods
     */
    protected function _onEditProperty($aContentInfo, $sProperty, &$oForm)
    {
        $CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];

        $mixedValue = $oForm->getCleanValue($CNF['FIELD_' . strtoupper($sProperty)]);
        if(($sMethod = 'get' . bx_gen_method_name($sProperty) . 'Title') && method_exists($this->_oConfig, $sMethod))
            $mixedValue = $this->_oConfig->$sMethod($mixedValue);

        $this->logActivity($iContentId, ['key' => '_bx_tasks_txt_msg_edit_' . $sProperty, 'markers' => ['value' => $mixedValue]]);
    }

    protected function _onEditState($aContentInfo, &$oForm)
    {
        $CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];

        $iState = $oForm->getCleanValue($CNF['FIELD_STATE']);
        $this->logActivity($iContentId, ['key' => '_bx_tasks_txt_msg_edit_state', 'markers' => ['value' => $this->_oConfig->getStateTitle($iState)]]);

        $iCompleted = (int)$this->_oConfig->isCompleted($iState);
        if($iCompleted != (int)$this->_oConfig->isCompleted($aContentInfo[$CNF['FIELD_STATE']]))
            $this->complete($iContentId, $iCompleted);
    }
}

/** @} */
