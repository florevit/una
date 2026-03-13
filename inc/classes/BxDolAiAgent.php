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
    {}

    protected function provider(): AIProviderInterface
    {
        return BxDolAi::getModelInstance($this->aAgent['model_id']);
    }

    protected function instructions(): string
    {
        $oPrompt = new NeuronAI\Agent\SystemPrompt(
            background: [$aAgent['prompt_system']],
            steps: !empty($aAgent['prompt_steps']) ? [$aAgent['prompt_steps']] : [],
            output: !empty($aAgent['prompt_output']) ? [$aAgent['prompt_output']] : [],
            toolsUsage: !empty($aAgent['prompt_tools']) ? [$aAgent['prompt_tools']] : []
        );
        return (string) $oPrompt;
    }

    /**
     * @return \NeuronAI\Tools\ToolInterface[]
     */
    protected function tools(): array
    {
        // TODO:
        return [];
    }
    
    protected function vectorStore(): VectorStoreInterface
    {
        if ($this->aAgent['vector_store_id'])
            return BxDolAi::getVectorStoreInstance($this->aAgent['vector_store_id']);
        else
            return new NeuronAI\RAG\VectorStore\MemoryVectorStore();
    }
}