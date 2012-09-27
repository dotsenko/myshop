<?php


/**
 * Source model for websites, including "All" option
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Model_Source_Website
{
    /**
     * Prepare and return array of website ids and their names
     *
     * @param bool $withAll Whether to prepend "All websites" option on not
     * @return array
     */
    public function toOptionArray($withAll = true)
    {
        $websites = Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash();
        if ($withAll) {
            $websites = array(0 => df_helper()->reward()->__('All Websites'))
                      + $websites;
        }
        return $websites;
    }
}
