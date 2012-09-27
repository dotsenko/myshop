<?php



/**
 * Reward points refund block in creditmemo
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Adminhtml_Sales_Order_Creditmemo_Reward
    extends Mage_Adminhtml_Block_Template
{
    /**
     * Getter
     *
     * @return Mage_Sales_Model_Order_Creditmemo
     */
    public function getCreditmemo()
    {
        return Mage::registry('current_creditmemo');
    }

    /**
     * Check whether can refund reward points to customer
     *
     * @return boolean
     */
    public function canRefundRewardPoints()
    {
        if ($this->getCreditmemo()->getOrder()->getCustomerIsGuest()) {
            return false;
        }
        if ($this->getCreditmemo()->getRewardPointsBalance() <= 0) {
            return false;
        }
        return true;
    }

    /**
     * Return maximum points balance to refund
     *
     * @return integer
     */
    public function getRefundRewardPointsBalance()
    {
        return (int)$this->getCreditmemo()->getRewardPointsBalance();
    }
}
