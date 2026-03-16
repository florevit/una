<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDolAiAlertResponse extends BxDolAlertsResponse
{
    public function response($oAlert)
    {        
        if ('bx_messenger' == $oAlert->sUnit && 'got_jot' == $oAlert->sAction) {
            $iProfileIdSender = $oAlert->iSender;
            $this->processMesssage ($oAlert->iSender, $oAlert->aExtras['recipient_id'], $oAlert->iObject, $oAlert->aExtras['subobject_id'], $oAlert->aExtras['subobject_info']);
        }
    }

    protected function processMesssage ($iSender, $iRecipient, $iLotId, $iJotId, $aJotInfo) 
    {
        if ($iSender == $iRecipient)
            return;

        // call agents
        $oAi = BxDolAI::getInstance();
        if($aAgents = $oAi->getAgentsByProfileId($iRecipient)) {            
            foreach($aAgents as $a) {
                if (!$a['message_profile_id'] || $iSender == $a['message_profile_id']) {
                    $sMessage = $oAi->callAgent('message', $a, [
                        'trigger' => 'message',
                        'sender_profile_id' => $iSender,
                        'recipient_profile_id' => $iRecipient,
                        'message_lot_id' => $iLotId, 
                        'message_id' => $iJotId,
                        'message_text' => $aJotInfo['message'],
                        'message_info' => $aJotInfo,
                    ]);
                    $this->sendAutoMessage($a['profile_id'], $iSender, $sMessage);
                }
            }
        }
    }

    function sendAutoMessage ($iSender, $iRecipient, $sMsg) 
    {        
        $oMessengerModule = BxDolModule::getInstance('bx_messenger');

        $aAutoReplyData = [
            'message' => $sMsg,
            'participants' => [$iSender, $iRecipient],
        ];

        $iSaveProfileId = $oMessengerModule->setProfileId($iSender);
        $a = $oMessengerModule->sendMessage($aAutoReplyData, $iRecipient, $iSender);
        $oMessengerModule->setProfileId($iSaveProfileId);

        return $a;
    }
}

/** @} */
