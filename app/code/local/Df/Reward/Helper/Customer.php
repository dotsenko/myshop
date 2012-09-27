<?php



/**
 * Reward Helper for operations with customer
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Helper_Customer extends Mage_Core_Helper_Abstract
{
    /**
     * Return Unsubscribe notification URL
     *
     * @param string $notification Notification type
     * @return string
     */
    public function getUnsubscribeUrl($notification = false)
    {
        $params = array();
        if ($notification) {
            $params = array('notification' => $notification);
        }
        return Mage::getUrl('df_reward/customer/unsubscribe/', array('notification' => $notification));
    }
}
