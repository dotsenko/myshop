<?php


/**
 * Customerbalance history collection
 *
 */
class Df_CustomerBalance_Model_Mysql4_Balance_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Initialize resource
     *
     */
    protected function _construct()
    {
        $this->_init('df_customerbalance/balance');
    }

    /**
     * Filter collection by specified websites
     *
     * @param array|int $websiteIds
     * @return Df_CustomerBalance_Model_Mysql4_Balance_Collection
     */
    public function addWebsitesFilter($websiteIds)
    {
        $this->getSelect()->where(
            $this->getConnection()->quoteInto('main_table.website_id IN (?)', $websiteIds)
        );
        return $this;
    }

    /**
     * Implement after load logic for each collection item
     *
     * @return Df_CustomerBalance_Model_Mysql4_Balance_Collection
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        $this->walk('afterLoad');
        return $this;
    }
}
