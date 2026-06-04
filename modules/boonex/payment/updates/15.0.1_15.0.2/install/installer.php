<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPaymentUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_payment_providers_options', 'value'))
                $this->oDb->query("ALTER TABLE `bx_payment_providers_options` ADD `value` varchar(255) NOT NULL default '' AFTER `type`");
            if(!$this->oDb->isFieldExists('bx_payment_providers_options', 'extended'))
                $this->oDb->query("ALTER TABLE `bx_payment_providers_options` ADD `extended` tinyint(4) NOT NULL default '0' AFTER `check_error`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
