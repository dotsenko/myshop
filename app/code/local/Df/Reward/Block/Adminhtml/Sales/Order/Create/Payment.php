<?php



/**
 * Reward Points Payment block in admin order creating process
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Adminhtml_Sales_Order_Create_Payment extends Mage_Core_Block_Template
{
    /**
     * Getter
     *
     * @return Mage_Adminhtml_Model_Sales_Order_Create
     */
    protected function _getOrderCreateModel()
    {
        return Mage::getSingleton('adminhtml/sales_order_create');
    }

    /**
     * Getter
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->_getOrderCreateModel()->getQuote();
    }
    
    /**
     * Check whether can use customer reward points
     *
     * @return boolean
     */
    public function canUseRewardPoints()
    {
        $websiteId = Mage::app()->getStore($this->getQuote()->getStoreId())->getWebsiteId();
        return (float)$this->getCurrencyAmount() && df_helper()->reward()->isEnabledOnFront($websiteId);
    }

    /**
     * Getter.
     * Retrieve reward points model
     *
     * @return Df_Reward_Model_Reward
     */
    public function getReward()
    {
        if (!$this->_getData('reward')) {
            /* @var $reward Df_Reward_Model_Reward */
            $reward = Mage::getModel('df_reward/reward')
                ->setCustomer($this->getQuote()->getCustomer())
                ->setStore($this->getQuote()->getStore())
                ->loadByCustomer();
            $this->setData('reward', $reward);
        }
        return $this->_getData('reward');
    }

    /**
     * Prepare some template data
     *
     * @return string
     */
    protected function _toHtml()
    {
        $points = $this->getReward()->getPointsBalance();
        $amount = $this->getReward()->getCurrencyAmount();
        $rewardFormatted = df_helper()->reward()
            ->formatReward($points, $amount, $this->getQuote()->getStore()->getId());
        $this->setPointsBalance($points)->setCurrencyAmount($amount)
            ->setUseLabel($this->__('Use my reward points, %s available', $rewardFormatted))
        ;
        return parent::_toHtml();
    }

    /**
     * Check if reward points applied in quote
     *
     * @return boolean
     */
    public function useRewardPoints()
    {
        return (bool)$this->_getOrderCreateModel()->getQuote()->getUseRewardPoints();
    }
}
