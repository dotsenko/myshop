<?php



/**
 * Reward management container
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Adminhtml_Customer_Edit_Tab_Reward_Management
    extends Mage_Adminhtml_Block_Template
{
    /**
     * Internal constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('df/reward/customer/edit/management.phtml');
    }

    /**
     * Prepare layout
     *
     * @return Df_Reward_Block_Adminhtml_Customer_Edit_Tab_Reward_Management
     */
    protected function _prepareLayout()
    {
        $total = $this->getLayout()
            ->createBlock('df_reward/adminhtml_customer_edit_tab_reward_management_balance');

        $this->setChild('balance', $total);

        $update = $this->getLayout()
            ->createBlock('df_reward/adminhtml_customer_edit_tab_reward_management_update');

        $this->setChild('update', $update);

        return parent::_prepareLayout();
    }
}
