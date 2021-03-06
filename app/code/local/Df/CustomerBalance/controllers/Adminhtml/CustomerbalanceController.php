<?php


/**
 * Controller for Customer account -> Store Credit ajax tab and all its contents
 *
 */
class Df_CustomerBalance_Adminhtml_CustomerbalanceController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Check is enabled module in config
     *
     * @return Df_CatalogEvent_Adminhtml_Catalog_EventController
     */
    public function preDispatch()
    {
        parent::preDispatch();
        if (!df_helper()->customer()->balance()->isEnabled()) {
            if ($this->getRequest()->getActionName() != 'noroute') {
                $this->_forward('noroute');
            }
        }
        return $this;
    }

    /**
     * Customer balance form
     *
     */
    public function formAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Customer balance grid
     *
     */
    public function gridHistoryAction()
    {
        $this->_initCustomer();
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('df_customerbalance/adminhtml_customer_edit_tab_customerbalance_balance_history_grid')->toHtml()
        );
    }

    /**
     * Delete orphan balances
     *
     */
    public function deleteOrphanBalancesAction()
    {
        $balance = Mage::getSingleton('df_customerbalance/balance')->deleteBalancesByCustomerId(
            (int)$this->getRequest()->getParam('id')
        );
        $this->_redirect('*/customer/edit/', array('_current'=>true));
    }

    /**
     * Instantiate customer model
     *
     * @param string $idFieldName
     */
    protected function _initCustomer($idFieldName = 'id')
    {
        $customer = Mage::getModel('customer/customer')->load((int)$this->getRequest()->getParam($idFieldName));
        if (!$customer->getId()) {
            Mage::throwException(df_helper()->customer()->balance()->__('Failed to initialize customer'));
        }
        Mage::register('current_customer', $customer);
    }

    /**
     * Check is allowed customer management
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('customer/manage');
    }
}
