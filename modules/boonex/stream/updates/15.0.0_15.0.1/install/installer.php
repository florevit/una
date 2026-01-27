<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxStrmUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if($this->oDb->isFieldExists('bx_stream_streams', 'published'))
                $this->oDb->query("ALTER TABLE `bx_stream_streams` DROP COLUMN `published`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
