<?php

class Df_Banner_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => df_helper()->banner()->__('Enabled'),
            self::STATUS_DISABLED   => df_helper()->banner()->__('Disabled')
        );
    }
}