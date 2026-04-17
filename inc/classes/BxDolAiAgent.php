<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

use NeuronAI\RAG\RAG;

class BxDolAiAgent extends RAG
{
    public function __construct(protected array $aAgent, protected array $aParams = [])
    {
        parent::__construct();
    }

    protected function provider(): NeuronAI\Providers\AIProviderInterface
    {
        return BxDolAIModelFactory::getModelInstance($this->aAgent['model_id']);
    }

    protected function instructions(): string
    {
        $aToolsUsage = !empty($this->aAgent['prompt_tools']) ? [$this->aAgent['prompt_tools']] : [];
        $aToolsUsage[] = "For any tool that requires `profile_id`:
1. Check if the user request contains an explicit profile_id.
2. If yes — use the user-provided profile_id.
3. If no — use {$this->aAgent['profile_id']}.
Never omit profile_id when the tool supports it. Never use current profile or 0, unless it\'s explicitly requested.";

        $oPrompt = new NeuronAI\Agent\SystemPrompt(
            background: [$this->aAgent['prompt_system']],
            steps: !empty($this->aAgent['prompt_steps']) ? [$this->aAgent['prompt_steps']] : [],
            output: !empty($this->aAgent['prompt_output']) ? [$this->aAgent['prompt_output']] : [],
            toolsUsage: $aToolsUsage
        );
        return (string) $oPrompt;
    }

    /**
     * @return \NeuronAI\Tools\ToolInterface[]
     */
    protected function tools(): array
    {
        if ($this->aAgent['tools']) {
            $aTools = explode(',', $this->aAgent['tools']);
            $aToolInstances = [];
            foreach ($aTools as $iToolId) {
                $oTool = BxDolAIToolFactory::getToolInstance($iToolId);
                $aToolInstances[] = $oTool;                
            }
            return $aToolInstances;
        }
        else {
            return [];
        }
    }
    
    protected function vectorStore(): NeuronAI\RAG\VectorStore\VectorStoreInterface
    {
        if ($this->aAgent['vector_store_id'])
            return BxDolAIVectorStoreFactory::getVectorStoreInstance($this->aAgent['vector_store_id']);
        else
            return new NeuronAI\RAG\VectorStore\MemoryVectorStore();
    }

    protected function chatHistory(): NeuronAI\Chat\History\ChatHistoryInterface
    {
        if ($this->aAgent['chat_history_context']) {
            return new NeuronAI\Chat\History\SQLChatHistory(
                thread_id: $this->getСhatHistoryThreadId(),
                pdo: BxDolDb::getInstance()->getLink(),
                table: 'sys_agents_chat_history',
                contextWindow: $this->aAgent['chat_history_context']
            );
        }
        else {
            return new NeuronAI\Chat\History\InMemoryChatHistory(
                contextWindow: 50000
            );
        }
    }

    protected function getСhatHistoryThreadId(): string
    {
        echoDbgLog($this->aParams);
        $s = $this->aAgent['trigger'] . ':' . $this->aAgent['id'];
        if (isset($this->aParams['chat_history_subindex']))
            $s .=  ':' . $this->aParams['chat_history_subindex'];
        return $s;
    }
}