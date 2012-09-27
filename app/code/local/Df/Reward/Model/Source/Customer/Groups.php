<?php



/**
 * Reward Customer Groups source model
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Model_Source_Customer_Groups
{
    /**
     * Retrieve option array of customer groups
     *
     * @return array
     */
    public function toOptionArray()
    {
        $groups = Mage::getResourceModel('customer/group_collection')
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();
        $groups = array(0 => df_helper()->reward()->__('All Customer Groups'))
                + $groups;
        return $groups;
    }
}
