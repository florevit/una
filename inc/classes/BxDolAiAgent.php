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
    public function __construct(protected array $aAgent)
    {
        parent::__construct();
    }

    protected function provider(): NeuronAI\Providers\AIProviderInterface
    {
        return BxDolAi::getModelInstance($this->aAgent['model_id']);
    }

    protected function instructions(): string
    {
        $oPrompt = new NeuronAI\Agent\SystemPrompt(
            background: [$this->aAgent['prompt_system']],
            steps: !empty($this->aAgent['prompt_steps']) ? [$this->aAgent['prompt_steps']] : [],
            output: !empty($this->aAgent['prompt_output']) ? [$this->aAgent['prompt_output']] : [],
            toolsUsage: !empty($this->aAgent['prompt_tools']) ? [$this->aAgent['prompt_tools']] : []
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
                $oTool = BxDolAi::getToolInstance($iToolId);
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
            return BxDolAi::getVectorStoreInstance($this->aAgent['vector_store_id']);
        else
            return new NeuronAI\RAG\VectorStore\MemoryVectorStore();
    }
}