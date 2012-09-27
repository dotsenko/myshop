<?php


/**
 * Customerbalance resource model
 *
 */
class Df_CustomerBalance_Model_Mysql4_Balance extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize table name and primary key name
     *
     */
    protected function _construct()
    {
        $this->_init('df_customerbalance/balance', 'balance_id');
    }

    /**
     * Load customer balance data by specified customer id and website id
     *
     * @param Df_CustomerBalance_Model_Balance $object
     * @param int $customerId
     * @param int $websiteId
     */
    public function loadByCustomerAndWebsiteIds($object, $customerId, $websiteId)
    {
        if ($data = $this->getReadConnection()->fetchRow($this->getReadConnection()->select()
            ->from($this->getMainTable())
            ->where('customer_id = ?', $customerId)
            ->where('website_id = ?', $websiteId)
            ->limit(1))) {
            $object->addData($data);
        }
    }

     /**
     * Update customers balance currency code per website id
     *
     * @param int $websiteId
     * @param string $currencyCode
     * @return Df_CustomerBalance_Model_Mysql4_Balance
     */
    public function setCustomersBalanceCurrencyTo($websiteId, $currencyCode)
    {
        $bind = array('base_currency_code' => $currencyCode);
        $this->_getWriteAdapter()->update(
            $this->getMainTable(), $bind,
            array('website_id=?' => $websiteId, 'base_currency_code IS NULL')
        );
        return $this;
    }

    /**
     * Delete customer orphan balances
     *
     * @param int $customerId
     * @return Df_CustomerBalance_Model_Mysql4_Balance
     */
    public function deleteBalancesByCustomerId($customerId)
    {
        $adapter = $this->_getWriteAdapter();

        $adapter->delete(
            $this->getMainTable(), $adapter->quoteInto('customer_id = ? AND website_id IS NULL', $customerId)
        );
        return $this;
    }

    /**
     * Get customer orphan balances count
     *
     * @param int $customerId
     * @return Df_CustomerBalance_Model_Mysql4_Balance
     */
    public function getOrphanBalancesCount($customerId)
    {
        $adapter = $this->_getReadAdapter();
        return $adapter->fetchOne($adapter->select()
            ->from($this->getMainTable(), 'count(*)')
            ->where('customer_id = ?', $customerId)
            ->where('website_id IS NULL'));
    }
}
