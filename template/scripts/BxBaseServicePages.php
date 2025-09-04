<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System service for pages functionality.
 */
class BxBaseServicePages extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @page service Service Calls
     * @section bx_system_pages System Services 
     * @subsection bx_system_pages-pages Pages
     * @subsubsection bx_system_pages-get_page_by_request Get page by page URI
     * 
     * @code bx_srv('system', 'get_page_by_request', ['about'], 'TemplServicePages'); @endcode
     * @code {{~system:get_page_by_request:TemplServicePages['about']~}} @endcode
     * 
     * Test method which page data
     * @param $sURI page URI 
     * 
     * @see BxBaseServicePages::serviceGetPage
     */
    /** 
     * @ref bx_system_general-get_page_by_request "get_page_by_request"
     */
    public function serviceGetPageByRequest ($sRequest, $sBlocks = '', $sParams = '')
    {
        $mixed = null;

        if (substr_count($sRequest, 'page/')> 0){
            $_GET['i'] = str_replace('page/', '', $sRequest);
            $aParams = json_decode($sParams, true);
            if(!empty($aParams) && is_array($aParams))
                $_GET = array_merge($_GET, $aParams);
            
            $mixed = BxDolPage::getObjectInstanceByURI();
        }
        else{
            if(!empty($sParams)) {
                $aParams = json_decode($sParams, true);
                if(!empty($aParams) && is_array($aParams))
                    $_GET = array_merge($_GET, $aParams);
            }
            $mixed = BxDolPage::getPageBySeoLink($sRequest);
        }

        $aExtras = [
            'request' => $sRequest,
        ];

        if (($sUrl = $mixed) && is_string($sUrl)) {
            $aRes = ['redirect' => $sUrl];

            $aExtras = array_merge($aExtras, [
                'url' => $sUrl
            ]);
        }
        elseif (($oPage = $mixed) && is_object($oPage)) {
            $aBlocks = [];
            if(!empty($sBlocks))
                $aBlocks = explode(',', $sBlocks);

            $aRes = $oPage->getPageAPI($aBlocks);

            $aExtras = array_merge($aExtras, [
                'page' => $oPage,
                'blocks' => $aBlocks
            ]);
        }
        else {
            $aRes = ['code' => 404, 'error' => _t("_sys_request_page_not_found_cpt"), 'data' => ['page_status' => 404]];

            if(isLogged())
                $aRes['data']['user'] = BxDolProfile::getDataForPage();
        }

        $aExtras['data'] = &$aRes;

        /**
         * @hooks
         * @hookdef hook-system-get_page_api 'system', 'get_page_api' - hook to override page peremeters, is used in API calls
         * - $unit_name - equals `system`
         * - $action - equals `get_page_api`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `page` - [object] an instance of page class, @see BxDolPage 
         *      - `blocks` - [array] array with page blocks
         *      - `data` - [array] by ref, page peremeters array as key&value pairs, can be overridden in hook processing
         * @hook @ref hook-system-get_page_api
         */
        bx_alert('system', 'get_page_api', 0, 0, $aExtras);

        return $aRes;
    }

    public function serviceGetPageContentByRequest ($sRequest, $sBlocks = '', $sParams = '')
    {
        $mixed = null;

        if (substr_count($sRequest, 'page/')> 0){
            $_GET['i'] = str_replace('page/', '', $sRequest);
            $aParams = json_decode($sParams, true);
            if(!empty($aParams) && is_array($aParams))
                $_GET = array_merge($_GET, $aParams);
            
            $mixed = BxDolPage::getObjectInstanceByURI();
        }
        else{
            if(!empty($sParams)) {
                $aParams = json_decode($sParams, true);
                if(!empty($aParams) && is_array($aParams))
                    $_GET = array_merge($_GET, $aParams);
            }
            $mixed = BxDolPage::getPageBySeoLink($sRequest);
        }

        $aExtras = [
            'request' => $sRequest,
        ];

        if (($sUrl = $mixed) && is_string($sUrl)) {
            $aRes = ['redirect' => $sUrl];

            $aExtras = array_merge($aExtras, [
                'url' => $sUrl
            ]);
        }
        elseif (($oPage = $mixed) && is_object($oPage)) {
            $aBlocks = [];
            if(!empty($sBlocks))
                $aBlocks = explode(',', $sBlocks);

            $aRes = $oPage->getPageContentAPI($aBlocks);

            $aExtras = array_merge($aExtras, [
                'page' => $oPage,
                'blocks' => $aBlocks
            ]);
        }
        else {
            $aRes = ['code' => 404, 'error' => _t("_sys_request_page_not_found_cpt"), 'data' => ['page_status' => 404]];
        }

        $aExtras['data'] = &$aRes;

        /**
         * @hooks
         * @hookdef hook-system-get_page_api 'system', 'get_page_content_api' - hook to override page content, is used in API calls
         * - $unit_name - equals `system`
         * - $action - equals `get_page_content_api`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `page` - [object] an instance of page class, @see BxDolPage 
         *      - `blocks` - [array] array with page blocks
         *      - `data` - [array] by ref, page peremeters array as key&value pairs, can be overridden in hook processing
         * @hook @ref hook-system-get_page_content_api
         */
        bx_alert('system', 'get_page_content_api', 0, 0, $aExtras);

        return $aRes;
    }

    /**
     * @page service Service Calls
     * @section bx_system_pages System Services 
     * @subsection bx_system_pages-pages Pages
     * @subsubsection bx_system_pages-get_page_by_uri Get page by page URI
     * 
     * @code bx_srv('system', 'get_page_by_uri', ['about'], 'TemplServicePages'); @endcode
     * @code {{~system:get_page_by_uri:TemplServicePages['about']~}} @endcode
     * 
     * Test method which page data
     * @param $sURI page URI 
     * 
     * @see BxBaseServicePages::serviceGetPage
     */
    /** 
     * @ref bx_system_general-get_page_by_uri "get_page_by_uri"
     */
    public function serviceGetPageByUri ($sURI)
    {
        $oPage = BxDolPage::getObjectInstanceByURI($sURI, false, true);
        if (!$oPage) {
            $aRes = ['error' => _t("_sys_request_page_not_found_cpt")];
        } 
        else {
            $aRes = $oPage->getPage();
        }
        return $aRes;
    }

    public function serviceGetPageBlockData($iBlockId, $iContentId = 0, $sContentModule = '')
    {
        return BxDolPage::getPageBlockData($iBlockId, $iContentId, $sContentModule);
    }

    public function serviceSetPageBlockData($iBlockId, $iContentId = 0, $sContentModule = '')
    {
        $sData = @file_get_contents("php://input");
        $aData = json_decode($sData, true);
        if($aData === null)
            return false;

        return BxDolPage::setPageBlockData($iBlockId, $iContentId, $sContentModule, $sData);
    }

    public function serviceGetUrlInfo($sUrl)
    {
        $oEmbed = BxDolEmbed::getObjectInstance('sys_system');
        return $oEmbed->getData($sUrl, '');
    }
}

/** @} */
