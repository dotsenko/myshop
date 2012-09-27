<?php


/**
 * Checkout Tooltip block to show checkout cart message for gaining reward points
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Tooltip_Checkout extends Df_Reward_Block_Tooltip
{
    /**
     * Set quote to the reward action instance
     *
     * @param int|string $action
     */
    public function initRewardType($action)
    {
        parent::initRewardType($action);
        if ($this->_actionInstance) {
            $this->_actionInstance->setQuote(Mage::getSingleton('checkout/session')->getQuote());
        }
    }
}
