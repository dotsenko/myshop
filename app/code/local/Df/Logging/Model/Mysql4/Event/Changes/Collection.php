<?php


/**
 * Log items collection
 */
class Df_Logging_Model_Mysql4_Event_Changes_Collection extends  Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->_init('df_logging/event_changes');
    }
}
