<?php



/**
 * Reward rate resource model
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Model_Mysql4_Reward_Rate extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Internal constructor
     */
    protected function _construct()
    {
        $this->_init('df_reward/reward_rate', 'rate_id');
    }

    /**
     * Fetch rate customer group and website
     *
     * @param Df_Reward_Model_Reward_Rate $rate
     * @param integer $customerId
     * @param integer $websiteId
     * @return Df_Reward_Model_Mysql4_Reward_Rate
     */
    public function fetch(Df_Reward_Model_Reward_Rate $rate, $customerGroupId, $websiteId, $direction)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable())
            ->where('website_id IN (?, 0)', (int)$websiteId)
            ->where('customer_group_id IN (?, 0)', $customerGroupId)
            ->where('direction = ?', $direction)
            ->order('website_id DESC')
            ->order('customer_group_id DESC')
            ->limit(1);

        if ($row = $this->_getReadAdapter()->fetchRow($select)) {
            $rate->addData($row);
        }

        $this->_afterLoad($rate);
        return $this;
    }

    /**
     * Retrieve rate data bu given params or empty array if rate with such params does not exists
     *
     * @param integer $websiteId
     * @param integer $customerGroupId
     * @param integer $direction
     * @return array
     */
    public function getRateData($websiteId, $customerGroupId, $direction)
    {
        $result = true;
        $select = $this->_getWriteAdapter()->select()
            ->from($this->getMainTable())
            ->where('website_id = ?', $websiteId)
            ->where('customer_group_id = ?', $customerGroupId)
            ->where('direction = ?', $direction);
        if ($data = $this->_getWriteAdapter()->fetchRow($select)) {
            return $data;
        }
        return array();
    }
}
