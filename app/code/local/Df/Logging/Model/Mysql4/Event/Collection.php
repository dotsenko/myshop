<?php


/**
 * Log items collection
 */
class Df_Logging_Model_Mysql4_Event_Collection extends  Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->_init('df_logging/event');
    }

    /**
     * Minimize usual count select
     *
     * @return Varien_Db_Select
     */
    public function getSelectCountSql()
    {
        return parent::getSelectCountSql()->resetJoinLeft();
    }
}
