<?php



/**
 * Reward collection
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Model_Mysql4_Reward_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Internal construcotr
     */
    protected function _construct()
    {
        $this->_init('df_reward/reward');
    }

    /**
     * Add filter by website id
     *
     * @param integer|array $websiteId
     * @return Df_Reward_Model_Mysql4_Reward_Collection
     */
    public function addWebsiteFilter($websiteId)
    {
        $this->getSelect()->where(is_array($websiteId) ? 'main_table.website_id IN (?)' : 'main_table.website_id = ?', $websiteId);
        return $this;
    }
}

