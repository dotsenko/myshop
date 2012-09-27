<?php


/**
 * Balance history collection
 *
 */
class Df_CustomerBalance_Model_Mysql4_Balance_History_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Initialize resource
     *
     */
    protected function _construct()
    {
        $this->_init('df_customerbalance/balance_history');
    }

    /**
     * Instantiate select joined to balance
     *
     * @return Df_CustomerBalance_Model_Mysql4_Balance_History_Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
            ->joinInner(array('b' => $this->getTable('df_customerbalance/balance')),
                'main_table.balance_id = b.balance_id', array('customer_id'         => 'b.customer_id',
                                                              'website_id'          => 'b.website_id',
                                                              'base_currency_code'  => 'b.base_currency_code'))
        ;
        return $this;
    }

    /**
     * Filter collection by specified websites
     *
     * @param array|int $websiteIds
     * @return Df_CustomerBalance_Model_Mysql4_Balance_History_Collection
     */
    public function addWebsitesFilter($websiteIds)
    {
        $this->getSelect()->where(
            $this->getConnection()->quoteInto('b.website_id IN (?)', $websiteIds)
        );
        return $this;
    }
}
