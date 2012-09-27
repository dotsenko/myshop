<?php



/**
 * Reward admin customer controller
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Adminhtml_Customer_RewardController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Check if module functionality enabled
     *
     * @return Df_Reward_Adminhtml_Reward_RateController
     */
    public function preDispatch()
    {
        parent::preDispatch();
        if (!df_helper()->reward()->isEnabled() && $this->getRequest()->getActionName() != 'noroute') {
            $this->_forward('noroute');
        }
        return $this;
    }

    /**
     * History Ajax Action
     */
    public function historyAction()
    {
        $customerId = $this->getRequest()->getParam('id', 0);
        $history = $this->getLayout()
            ->createBlock('df_reward/adminhtml_customer_edit_tab_reward_history', '',
                array('customer_id' => $customerId));
        $this->getResponse()->setBody($history->toHtml());
    }

    /**
     * History Grid Ajax Action
     *
     */
    public function historyGridAction()
    {
        $customerId = $this->getRequest()->getParam('id', 0);
        $history = $this->getLayout()
            ->createBlock('df_reward/adminhtml_customer_edit_tab_reward_history_grid', '',
                array('customer_id' => $customerId));
        $this->getResponse()->setBody($history->toHtml());
    }

    /**
     *  Delete orphan points Action
     */
    public function deleteOrphanPointsAction()
    {
        $customerId = $this->getRequest()->getParam('id', 0);
        if ($customerId) {
            try {
                Mage::getModel('df_reward/reward')
                    ->deleteOrphanPointsByCustomer($customerId);
                $this->_getSession()
                    ->addSuccess(df_helper()->reward()->__('Orphan points removed successfully.'));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/customer/edit', array('_current' => true));
    }

    /**
     * Acl check for admin
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('df_reward/balance');
    }
}

