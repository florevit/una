<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

define('BX_DOL_AI_ASSISTANT', 'assistant');
define('BX_DOL_AI_AUTOMATOR_EVENT', 'event');
define('BX_DOL_AI_AUTOMATOR_SCHEDULER', 'scheduler');
define('BX_DOL_AI_AUTOMATOR_WEBHOOK', 'webhook');

define('BX_DOL_AI_AUTOMATOR_STATUS_AUTO', 'auto');
define('BX_DOL_AI_AUTOMATOR_STATUS_MANUAL', 'manual');
define('BX_DOL_AI_AUTOMATOR_STATUS_READY', 'ready');

class BxDolAI extends BxDolFactory implements iBxDolSingleton
{
    protected $_oDb;
    protected $_iProfileId;
    
    protected $_aExcludeAlertUnits;

    protected $_sCmtsAutomators;
    protected $_sCmtsAssistantsChats;

    protected $_bWriteLog;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_oDb = new BxDolAIQuery();

        $this->_iProfileId = (int)getParam('sys_profile_bot'); 

        $this->_aExcludeAlertUnits = [
            'system', 'module_template_method_call'
        ];

        $this->_sCmtsAutomators = 'sys_agents_automators';
        $this->_sCmtsAssistantsChats = 'sys_agents_assistants_chats';

        $this->_bWriteLog = true;
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            $GLOBALS['bxDolClasses'][__CLASS__] = BxDolDb::getInstance()->isTableExists('sys_agents_automators') ? new BxDolAI() : null;
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public static function getAiProviderInstance(int $iId):NeuronAI\Providers\AIProviderInterface
    {
        return self::getInstance()->getAiProviderInstance($iId);
    }   

    public static function getAiEmbeddingsProviderInstance(int $iId):NeuronAI\RAG\Embeddings\EmbeddingsProviderInterface
    {
        return self::getInstance()->getAiProviderInstance($iId);
    }   

    public static function getModelInstance(int $iId): NeuronAI\Providers\AIProviderInterface | NeuronAI\RAG\Embeddings\EmbeddingsProviderInterface
    {
        if (isset($GLOBALS['bxDolClasses'][__CLASS__ . '_Model_' . $iId]))
            return $GLOBALS['bxDolClasses'][__CLASS__ . '_Model_' . $iId];

        $aProvidersWithKey = ['anthropic'];
        $a = BxDolAIQuery::getModelObject($iId);
        if (!$a) {
            bx_log('sys_agents', "Agent AI Model with id {$iId} not found", BX_LOG_ERR);
            throw new Exception("Agent AI Model with id {$iId} not found");
        }
        if (in_array($a['type'], $aProvidersWithKey) && empty($a['key'])) {
            bx_log('sys_agents', "Model with id {$iId} has empty key, can't be used", BX_LOG_ERR);
            throw new Exception("Model with id {$iId} has empty key, can't be used");
        }

        if (!$a['active']) {
            bx_log('sys_agents', "Model with id {$iId} is not active, can't be used", BX_LOG_ERR);
            throw new Exception("Model with id {$iId} is not active, can't be used");
        }

        $aParameters = !empty($a['params']) ? json_decode($a['params'], true) : [];

        // replace markers {key} {model} in $aParameters recoursively
        $aParameters = bx_replace_markers($aParameters, [
            'key' => $a['key'],
            'model' => $a['model']
        ]);

        switch($a['type']) {
            // regular AI providers ------------------------
            case 'anthropic':
                $o = new NeuronAI\Providers\Anthropic\Anthropic(
                    key: $a['key'],
                    model: $a['model'],
                    version: $aParameters['version'] ?? '2023-06-01',
                    max_tokens: $aParameters['max_tokens'] ?? 8192,
                    parameters: $aParameters['parameters'] ?? [],
                );
                break;
            case 'openai-responses':
                $o = new NeuronAI\Providers\OpenAI\Responses\OpenAIResponses(
                    key: $a['key'],
                    model: $a['model'],
                    parameters: $aParameters['parameters'] ?? [],
                    strict_response: $aParameters['strict_response'] ?? false,
                );
                break;
            case 'azure-openai':
                $o = new NeuronAI\Providers\OpenAI\AzureOpenAI(
                    key: $a['key'],
                    model: $a['model'],
                    endpoint: $aParameters['endpoint'],
                    version: $aParameters['version'],
                    parameters: $aParameters['parameters'] ?? [],
                    strict_response: $aParameters['strict_response'] ?? false,
                );
                break;
            case 'openai-like':
                $o = new NeuronAI\Providers\OpenAILike(
                    baseUri: $aParameters['baseUri'],
                    key: $a['key'],
                    model: $a['model'],                    
                    parameters: $aParameters['parameters'] ?? [],
                    strict_response: $aParameters['strict_response'] ?? false,
                );
                break;
            case 'ollama':
                $o = new NeuronAI\Providers\Ollama\Ollama(
                    url: $aParameters['url'] ?? 'http://localhost:11434/api',
                    model: $a['model'],                    
                    parameters: $aParameters['parameters'] ?? [],
                );
                break;
            case 'gemini':
                $o = new NeuronAI\Providers\Gemini\Gemini(
                    key: $a['key'],
                    model: $a['model'],
                    parameters: $aParameters['parameters'] ?? [],
                );
                break;
            case 'mistral':
                $o = new NeuronAI\Providers\Mistral\Mistral(
                    key: $a['key'],
                    model: $a['model'],
                    parameters: $aParameters['parameters'] ?? [],
                    strict_response: $aParameters['strict_response'] ?? false,
                );
                break;
            case 'huggingface':
                $o = new NeuronAI\Providers\HuggingFace\HuggingFace(
                    key: $a['key'],
                    model: $a['model'],
                    inferenceProvider: $aParameters['inferenceProvider'] ?? 'hf-inference/models', // cohere, groq, etc - https://github.com/neuron-core/neuron-ai/blob/3.x/src/Providers/HuggingFace/InferenceProvider.php
                    parameters: $aParameters['parameters'] ?? [],
                    strict_response: $aParameters['strict_response'] ?? false,
                );
                break;
            case 'deepseek':
                $o = new NeuronAI\Providers\DeepSeek\DeepSeek(
                    key: $a['key'],
                    model: $a['model'],
                    parameters: $aParameters['parameters'] ?? [],
                    strict_response: $aParameters['strict_response'] ?? false,
                );
                break;
            case 'grok':
                $o = new NeuronAI\Providers\XAI\Grok(
                    key: $a['key'],
                    model: $a['model'],
                    parameters: $aParameters['parameters'] ?? [],
                    strict_response: $aParameters['strict_response'] ?? false,
                );
                break;
            case 'aws-bedrock':
                $oClient = new Aws\BedrockRuntime\BedrockRuntimeClient($aParameters['client_params']);

                $o = new NeuronAI\Providers\AWS\BedrockRuntime(
                    client: $oClient,
                    model: $a['model'],
                    inferenceConfig: $aParameters['inferenceConfig'] ?? [],
                );
                break;
            case 'cohere':
                $o = new NeuronAI\Providers\Cohere\Cohere(
                    key: $a['key'],
                    model: $a['model'],
                    baseUri: $aParameters['baseUri'] ?? 'https://api.cohere.ai/v2',
                    parameters: $aParameters['parameters'] ?? [],
                    strict_response: $aParameters['strict_response'] ?? false,
                );
                break;

            // embeddings models ------------------------
            case 'ollama-embeddings':
                $o = new NeuronAI\RAG\Embeddings\OllamaEmbeddingsProvider(
                    model: $a['model'],
                    url: $aParameters['url'] ?? 'http://localhost:11434/api',
                    parameters: $aParameters['parameters'] ?? [],
                );
                break;
            case 'voyageai-embeddings':
                $o = new NeuronAI\RAG\Embeddings\VoyageEmbeddingsProvider(
                    key: $a['key'],
                    model: $a['model'],
                    dimensions: !empty($aParameters['dimensions']) ? (int)$aParameters['dimensions'] : null
                );
                break;
            case 'openai-embeddings':
                $o = new NeuronAI\RAG\Embeddings\OpenAIEmbeddingsProvider(
                    key: $a['key'],
                    model: $a['model'],
                    dimensions: !empty($aParameters['dimensions']) ? (int)$aParameters['dimensions'] : 1024
                );
                break;
            case 'openai-like-embeddings':
                $o = new NeuronAI\RAG\Embeddings\OpenAILikeEmbeddings(
                    baseUri: $aParameters['baseUri'],
                    key: $a['key'],
                    model: $a['model'],                    
                    dimensions: !empty($aParameters['dimensions']) ? (int)$aParameters['dimensions'] : 1024
                );
                break;
            case 'aws-bedrock-embeddings':
                $oClient = new Aws\BedrockRuntime\BedrockRuntimeClient($aParameters['client_params']);
                $o = new NeuronAI\RAG\Embeddings\AwsBedrockEmbeddingsProvider(
                    client: $oClient,
                    model: $a['model']
                );
                break;
            default:
                bx_log('sys_agents', "Model type {$a['type']} is not supported", BX_LOG_ERR);
                throw new Exception("Model type {$a['type']} is not supported");
        }

        $GLOBALS['bxDolClasses'][__CLASS__ . '_Model_' . $iId] = $o;

        return $o;
    }

    public static function getVectorStoreInstance(int $iId): NeuronAI\RAG\VectorStore\VectorStoreInterface 
    {
        if (isset($GLOBALS['bxDolClasses'][__CLASS__ . '_VectorStore_' . $iId]))
            return $GLOBALS['bxDolClasses'][__CLASS__ . '_VectorStore_' . $iId];

        $a = BxDolAIQuery::getVectorStoreObject($iId);
        if (!$a) {
            bx_log('sys_agents', "Vector store with id {$iId} not found", BX_LOG_ERR);
            throw new Exception("Vector store with id {$iId} not found");
        }

        $aParameters = !empty($a['params']) ? json_decode($a['params'], true) : [];

        // replace marker {topk} in $aParameters recoursively
        $aParameters = bx_replace_markers($aParameters, [
            'topk' => $a['topk'],
        ]);
        
        // TODO: fix params
        switch($a['type']) {
            case 'file':
                $o = new NeuronAI\RAG\VectorStore\FileVectorStore(
                    directory: BX_DIRECTORY_STORAGE . 'vector_stores/' . $iId,
                    topK: $a['topk'] ?? 4
                );
                break;
            case 'pinecon':
                $o = new NeuronAI\RAG\VectorStore\PineconeVectorStore(
                    key: $a['key'],
                    indexUrl: $a['indexUrl'],
                    topK: $a['topk'] ?? 4,
                    version: $a['version'] ?? '2025-04',
                    namespace: $a['namespace'] ?? '__default__'
                );
                break;
            case 'elasticsearch':
                $oClient = Elastic\Elasticsearch\ClientBuilder::create()
                    ->setHosts([$a['client_params']['endpoint']])
                    ->setApiKey($a['client_params']['key'])
                    ->build();
                
                $o = new NeuronAI\RAG\VectorStore\ElasticsearchVectorStore(
                    client: $oClient,
                    index: $a['index'],
                    topK: $a['topk'] ?? 4
                );
                break;
            case 'opensearch':
                $oClient = (new OpenSearch\GuzzleClientFactory())->create([
                    'base_uri' => $a['client_params']['endpoint'] ?? 'http://localhost:9200',
                ]);
        
                $o = NeuronAI\RAG\VectorStore\OpenSearchVectorStore(
                    client: $oClient,
                    index: $a['index'],
                    topK: $a['topk'] ?? 4,
                );
                break;
            case 'typesense':
                $oClient = new \Typesense\Client($a['client_params']);

                $o = new NeuronAI\RAG\VectorStore\TypesenseVectorStore(
                    client: $oClient,
                    collection: $a['collection'],
                    vectorDimension: $a['vectorDimension'] ?? 1024,
                    topK: $a['topk'] ?? 4,
                );
                break;
            case 'qdrant':
                $o = new NeuronAI\RAG\VectorStore\QdrantVectorStore(
                    collectionUrl: $a['collectionUrl'],
                    key: $a['key'],
                    topK: $a['topk'] ?? 4,
                    dimension: $a['dimension'] ?? 1024
                );
                break;
            case 'chromadb':
                $o = new NeuronAI\RAG\VectorStore\ChromaVectorStore(
                    collection: $a['collection'],
                    host: $a['host'] ?? 'http://localhost:8000',
                    tenant: $a['tenant'] ?? 'default_tenant',
                    database: $a['database'] ?? 'default_database',
                    key: $a['key'] ?? null,
                    topK: $a['topk'] ?? 4
                );
                break;
            case 'meilisearch':
                $o = new NeuronAI\RAG\VectorStore\MeilisearchVectorStore(
                    indexUid: $a['indexUid'],
                    host: $a['host'] ?? 'http://localhost:8000',
                    key: $a['key'] ?? null,
                    embedder: $a['embedder'] ?? 'default',
                    topK: $a['topk'] ?? 4,
                    dimension: $a['dimension'] ?? 1024
                );
                break;
            default:
                bx_log('sys_agents', "Vector store type {$a['type']} is not supported", BX_LOG_ERR);
                throw new Exception("Vector store type {$a['type']} is not supported");
        }

        $GLOBALS['bxDolClasses'][__CLASS__ . '_VectorStore_' . $iId] = $o;

        return $o;
    }

    public static function callHelper($mixedHelper, $sMessage)
    {
        $oAI = BxDolAI::getInstance();
        if (is_numeric($mixedHelper))
            $aHelper = $oAI->getHelperById($mixedHelper);
        else
             $aHelper = $oAI->getHelperByName($mixedHelper);
        $oAIModel = $oAI->getModelObject($aHelper['model_id']);
        return $oAIModel->getResponseText($aHelper['prompt'], $sMessage);
    }

    public static function pruning()
    {
        BxDolAIAssistant::pruning();
    }

    public static function getDefaultApiKey()
    {
        return getParam('sys_agents_api_key');
    }

    public static function getDefaultModel()
    {
        return (int)getParam('sys_agents_model');
    }

    public static function getAssistantForStudio()
    {
        return ($iId = (int)getParam('sys_agents_studio_assistant')) != 0 ? $iId : 0;
    }

    public static function getAssistantForLiveSearch()
    {
        return ($iId = (int)getParam('sys_agents_live_search_assistant')) != 0 ? $iId : 0;
    }

    public static function getAssistantForAskBlock()
    {
        return ($iId = (int)getParam('sys_agents_ask_block_assistant')) != 0 ? $iId : 0;
    }

    public function getProfileId()
    {
        return $this->_iProfileId;
    }

    public function getModels($aParams = [])
    {
        $aParamsDb = ['sample' => 'all_pairs'];
        if(isset($aParams['active']))
            $aParamsDb['active'] = $aParams['active'] === true ? 1 : 0;
        if(isset($aParams['capabilities']))
            $aParamsDb['capabilities'] = $aParams['capabilities'];

        return $aModel = $this->_oDb->getModelsBy($aParamsDb);
    }

    public function getModel($iId)
    {
        $aModel = $this->_oDb->getModelsBy(['sample' => 'id', 'id' => $iId]);
        if(!empty($aModel['params']))
            $aModel['params'] = json_decode($aModel['params'], true);

        return $aModel;
    }

    public function getModelObject($iId)
    {
        if(!$iId)
            $iId = $this->getDefaultModel();
        if(!$iId)
            return false;

        return BxDolAIModel::getObjectInstance($iId);
    }
    
    public function getProviderObject($iId)
    {
        if(!$iId)
            return false;

        return BxDolAIProvider::getObjectInstance($iId);
    }   

    public function getAssistants($aParams = [])
    {
        $aParamsDb = ['sample' => 'all_pairs'];
        if(isset($aParams['active']))
            $aParamsDb['active'] = $aParams['active'] === true ? 1 : 0;
        if(isset($aParams['hidden']))
            $aParamsDb['hidden'] = $aParams['hidden'] === true ? 1 : 0;

        return $aModel = $this->_oDb->getAssistantsBy($aParamsDb);
    }

    public function getAssistantById($iId)
    {
        return $this->_oDb->getAssistantsBy(['sample' => 'id', 'id' => $iId]);
    }

    public function getAssistantByName($sName)
    {
        return $this->_oDb->getAssistantsBy(['sample' => 'name', 'name' => $sName]);
    }

    public function getAssistantChatById($iId)
    {
        return $this->_oDb->getChatsBy(['sample' => 'id', 'id' => $iId]);
    }

    public function getAssistantChatsTransient($iLifetime = 0)
    {
        return $this->_oDb->getChatsBy(['sample' => 'type', 'type' => BX_DOL_AI_ASST_TYPE_TRANSIENT, 'lifetime' => $iLifetime]);
    }

    public function updateAssistantChatById($iId, $aSet)
    {
        return $this->_oDb->updateChats($aSet, ['id' => $iId]);
    }

    public function getAssistantChatCmts()
    {
        return $this->_sCmtsAssistantsChats;
    }

    public function getAssistantChatCmtsObject($iId, $oTemplate = false)
    {
        $oCmts = BxDolCmts::getObjectInstance($this->_sCmtsAssistantsChats, (int)$iId, true, $oTemplate);
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

        return $oCmts;
    }

    public function getHelperById($iId)
    {
        return $this->_oDb->getHelpersBy(['sample' => 'id', 'id' => $iId]);
    }
    
    public function getHelperByName($sName)
    {
        return $this->_oDb->getHelpersBy(['sample' => 'name', 'name' => $sName]);
    }

    public function getAutomator($iId, $bFullInfo = false)
    {
        $aAutomator = $this->_oDb->getAutomatorsBy(['sample' => 'id' . ($bFullInfo ? '_full' : ''), 'id' => $iId]);
        if(!empty($aAutomator['params']))
            $aAutomator['params'] = json_decode($aAutomator['params'], true);
        if($bFullInfo && !empty($aAutomator['model_params']))
            $aAutomator['model_params'] = json_decode($aAutomator['model_params'], true);

        return $aAutomator;
    }

    public function getAutomatorInstruction($sType, $mixedParams = false)
    {
        $mixedResult = '';

        switch($sType) {
            case 'profile':
                $mixedResult = "\n ProfileId for system actions = " . $mixedParams;
                break;

            case 'providers':
                $aProviders = $this->_oDb->getProvidersBy(['sample' => 'ids', 'ids' => $mixedParams]);
                if(!empty($aProviders) && is_array($aProviders)) {
                    $mixedResult = "\n Proividers list = [";
                    foreach($aProviders as $aProvider)
                        $mixedResult .= "\n {'ProviderName' => '" . $aProvider['name'] . "',  'ProviderType' => '" . $aProvider['type_name'] . "'}";
                    $mixedResult .= "\n ]";
                }
                break;

            case 'helpers':
                $aHelpers = $this->_oDb->getHelpersBy(['sample' => 'ids', 'ids' => $mixedParams]);
                if(!empty($aHelpers) && is_array($aHelpers)) {
                    $mixedResult = "\n Helpers list = [";
                    foreach($aHelpers as $aHelper)
                        $mixedResult .= "\n {'" . $aHelper['name'] . "', 'HelperDescription' => '" . $aHelper['description'] . "'}";
                    $mixedResult .= "\n ]";
                }
                break;

            case 'assistants':
                $aAssistants = $this->_oDb->getAssistantsBy(['sample' => 'ids', 'ids' => $mixedParams]);
                if(!empty($aAssistants) && is_array($aAssistants)) {
                    $mixedResult = "\n Assistants list = [";
                    foreach($aAssistants as $aAssistant)
                        $mixedResult .= "\n {'" . $aAssistant['name'] . "', 'AssistantDescription' => '" . $aAssistant['description'] . "'}";
                    $mixedResult .= "\n ]";
                }
                break;
        }

        return $mixedResult;
    }

    public function getAutomatorCmts()
    {
        return $this->_sCmtsAutomators;
    }

    public function getAutomatorCmtsObject($iId, $oTemplate = false)
    {
        $oCmts = BxDolCmts::getObjectInstance($this->_sCmtsAutomators, (int)$iId, true, $oTemplate);
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

        return $oCmts;
    }

    public function hasAutomators($sType, $bActive = null)
    {
        $aParams = [
            'sample' => 'type', 
            'type' => $sType
        ];
        if($bActive !== null)
            $aParams['active'] = $bActive;

        return ($aAutomators = $this->_oDb->getAutomatorsBy($aParams)) && is_array($aAutomators);
    }

    public function getAutomatorsEvent($sUnit, $sAction)
    {
        if(in_array($sUnit, $this->_aExcludeAlertUnits))
            return [];

        return $this->_oDb->getAutomatorsBy([
            'sample' => 'events', 
            'alert_unit' => $sUnit,
            'alert_action' => $sAction,
            'active' => true
        ]);
    }

    public function getAutomatorsScheduler()
    {
        $aAutomators = $this->_oDb->getAutomatorsBy(['sample' => 'schedulers', 'active' => true]);
        foreach($aAutomators as &$aAutomator)
            if(!empty($aAutomator['params']))
                $aAutomator['params'] = json_decode($aAutomator['params'], true);

        return $aAutomators;
    }

    public function getAutomatorsWebhook($iProviderId)
    {
        $aAutomators = $this->_oDb->getAutomatorsBy(['sample' => 'webhooks', 'provider_id' => $iProviderId, 'active' => true]);
        foreach($aAutomators as &$aAutomator)
            if(!empty($aAutomator['params']))
                $aAutomator['params'] = json_decode($aAutomator['params'], true);

        return $aAutomators;
    }

    public function callAutomator($sType, $aParams = [])
    {
        $sMethod = '_callAutomator' . bx_gen_method_name($sType);
        if(!method_exists($this, $sMethod))
            return false;

        return $this->$sMethod($aParams);
    }

    protected function _callAutomatorEvent($aParams = [])
    {
        if(!isset($aParams['automator'], $aParams['alert']) || !is_a($aParams['alert'], 'BxDolAlerts'))
            return false;
        
        $oAlert = &$aParams['alert'];

        $this->evalCode($aParams['automator'], ['alert' => $oAlert]);
    }

    protected function _callAutomatorScheduler($aParams = [])
    {
        if(!isset($aParams['automator']))
            return false;
        
        $this->evalCode($aParams['automator']);
    }

    protected function _callAutomatorWebhook($aParams = [])
    {
        if(!isset($aParams['automator']))
            return false;

        $this->evalCode($aParams['automator']);
    }

    public function evalCode($aAutomator, $aParams = [])
    {
        try {
            $this->_evalCode($aAutomator, $aParams);
        }
        catch (Exception $oException) {
            $this->log($oException->getFile() . ':' . $oException->getLine() . ' ' . $oException->getMessage());
        }
        catch (Error $oError) {
            $this->log($oError->getFile() . ':' . $oError->getLine() . ' ' . $oError->getMessage());
        }
    }

    public function emulCode($aAutomator, $aParams = [])
    {
        ob_start();

        try {
            $this->_evalCode($aAutomator, $aParams);
        }
        catch (Exception $oException) {
            return $oException->getMessage();
        }
        catch (Error $oError) {
            return $oError->getMessage();
        }
        finally {
            $sOutput = ob_get_clean();

            if(!empty($sOutput))
                return $sOutput;
        }
    }

    public function log($mixedContents, $sSection = '')
    {
        if(!$this->_bWriteLog)
            return;

        if(is_array($mixedContents))
            $mixedContents = var_export($mixedContents, true);	
        else if(is_object($mixedContents))
            $mixedContents = json_encode($mixedContents);

        if(empty($sSection))
            $sSection = "Core";

        bx_log('sys_agents', ":\n[" . $sSection . "] " . $mixedContents, BX_LOG_ERR);
    }

    protected function _evalCode($aAutomator, $aParams = [])
    {
        $sCode = '';
        switch($aAutomator['type']) {
            case BX_DOL_AI_AUTOMATOR_EVENT:
                $sCode = $aAutomator['code']. '; onAlert($aParams["alert"]->iObject , $aParams["alert"]->iSender , $aParams["alert"]->aExtras);';
                break;

            case BX_DOL_AI_AUTOMATOR_SCHEDULER:
                $sCode = $aAutomator['code'] . '; onCron();';
                break;

            case BX_DOL_AI_AUTOMATOR_WEBHOOK:
                $sCode = $aAutomator['code'] . '; onHook();';
                break;
        }

        eval($sCode);
    }
}

class BxDolAIMessage
{
    /**
     * @var string - message Type with following values: hb, ai 
     */
    protected $_sType;
    
    /**
     * @var mixed - an array of message parts (text, image_url) or a string.
     */
    protected $_mixedContent;
    
    /**
     * @var array - an array of of files attached to the message.
     */
    protected $_aAttachments;

    public function __construct($sType)
    {
        $this->_sType = $sType;
    }

    public function isAi()
    {
        return $this->_sType == 'ai';
    }

    public function getContent()
    {
        return $this->_mixedContent;
    }

    public function getAttachments()
    {
        return $this->_aAttachments;
    }
}

class BxDolAIMessageString extends BxDolAIMessage
{
    public function __construct($sType, $sContent)
    {
        parent::__construct($sType);

        $this->_mixedContent = is_string($sContent) ? $sContent : '';
    }
}

class BxDolAIMessageArray extends BxDolAIMessage
{
    public function __construct($sType, $aContent = '')
    {
        parent::__construct($sType);

        $this->_mixedContent = is_array($aContent) ? $aContent : [];
    }

    public function addText($sText)
    {
        $this->_mixedContent[] = [
            'type' => 'text',
            'text' => $sText
        ];
    }

    public function addImageUrl($sUrl, $sDetail = 'high')
    {
        $this->_mixedContent[] = [
            'type' => 'image_url',
            'image_url' => [
                'url' => $sUrl,
                'detail' => $sDetail
            ]
        ];
    }

    public function addAttachments($mixedAttachments, $mixedTools = false)
    {
        if(!is_array($mixedAttachments))
            $mixedAttachments = [$mixedAttachments];

        if(!$mixedTools)
            $mixedTools = [['type' => 'file_search']];

        foreach($mixedAttachments as $sAttachment)
            $this->_aAttachments[] = [
                'file_id' => $sAttachment,
                'tools' => $mixedTools
            ];
    }
}

class BxDolAIMessages
{
    /**
     * @var array - an array of items (messages)
     */
    protected $_aItems;

    public function __construct($aItems = [])
    {
        $this->_aItems = !empty($aItems) && is_array($aItems) ? $aItems : [];
    }

    public function add($sType, $mixedMessage)
    {
        $sClass = 'BxDolAIMessage' . (is_string($mixedMessage) ? 'String' : 'Array');
        $this->_aItems[] = new $sClass($sType, $mixedMessage);
    }

    public function getAll()
    {
        return $this->_aItems;
    }

    public function getLast()
    {
        return end($this->_aItems);
    }
}
