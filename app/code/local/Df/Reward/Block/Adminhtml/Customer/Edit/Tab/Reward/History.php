<?php



/**
 * Reward history container
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Adminhtml_Customer_Edit_Tab_Reward_History
    extends Mage_Adminhtml_Block_Template
{
    /**
     * Internal constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('df/reward/customer/edit/history.phtml');
    }

    /**
     * Prepare layout.
     * Create history grid block
     *
     * @return Df_Reward_Block_Adminhtml_Customer_Edit_Tab_Reward_History
     */
    protected function _prepareLayout()
    {
        $grid = $this->getLayout()
            ->createBlock('df_reward/adminhtml_customer_edit_tab_reward_history_grid')
            ->setCustomerId($this->getCustomerId());
        $this->setChild('grid', $grid);
        return parent::_prepareLayout();
    }
}
