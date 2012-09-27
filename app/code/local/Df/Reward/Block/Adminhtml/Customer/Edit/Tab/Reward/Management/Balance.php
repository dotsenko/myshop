<?php



/**
 * Reward points balance container
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Adminhtml_Customer_Edit_Tab_Reward_Management_Balance
    extends Mage_Adminhtml_Block_Template
{
    /**
     * Internal constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('df/reward/customer/edit/management/balance.phtml');
    }

    /**
     * Prepare layout.
     * Create balance grid block
     *
     * @return Df_Reward_Block_Adminhtml_Customer_Edit_Tab_Reward_Management_Balance
     */
    protected function _prepareLayout()
    {
        if (!Mage::getSingleton('admin/session')->isAllowed('df_reward/balance')) {
            // unset template to get empty output
            $this->setTemplate(null);
        } else {
            $grid = $this->getLayout()
                ->createBlock('df_reward/adminhtml_customer_edit_tab_reward_management_balance_grid');
            $this->setChild('grid', $grid);
        }
        return parent::_prepareLayout();
    }
}
