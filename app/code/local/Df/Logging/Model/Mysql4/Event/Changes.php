<?php


/**
 * Logging event changes model
 */
class Df_Logging_Model_Mysql4_Event_Changes extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize resource
     */
    public function _construct()
    {
        $this->_init('df_logging/event_changes', 'id');
    }
}
