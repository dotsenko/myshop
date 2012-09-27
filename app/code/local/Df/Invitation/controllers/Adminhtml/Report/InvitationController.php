<?php


/**
 * Invitation reports controller
 *
 * @category   Df
 * @package    Df_Invitation
 */

class Df_Invitation_Adminhtml_Report_InvitationController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init action breadcrumbs
     *
     * @return Df_Invitation_Adminhtml_Report_InvitationController
     */
    public function _initAction()
    {
        $this->loadLayout()
            ->_addBreadcrumb(Mage::helper('reports')->__('Reports'), Mage::helper('reports')->__('Reports'))
            ->_addBreadcrumb(df_helper()->invitation()->__('Invitations'), df_helper()->invitation()->__('Invitations'));
        return $this;
    }

    /**
     * General report action
     */
    public function indexAction()
    {
        $this->_title($this->__('Reports'))
             ->_title($this->__('Invitations'))
             ->_title($this->__('General'));

        $this->_initAction()
            ->_setActiveMenu('report/df_invitation/general')
            ->_addBreadcrumb(df_helper()->invitation()->__('General Report'), df_helper()->invitation()->__('General Report'))
            ->_addContent($this->getLayout()->createBlock('df_invitation/adminhtml_report_invitation_general'))
            ->renderLayout();
    }

    /**
     * Export invitation general report grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'invitation_general.csv';
        $content    = $this->getLayout()->createBlock('df_invitation/adminhtml_report_invitation_general_grid')
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export invitation general report grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'invitation_general.xml';
        $content    = $this->getLayout()->createBlock('df_invitation/adminhtml_report_invitation_general_grid')
            ->getExcel($fileName);

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Report by customers action
     */
    public function customerAction()
    {
        $this->_title($this->__('Reports'))
             ->_title($this->__('Invitations'))
             ->_title($this->__('Customers'));

        $this->_initAction()
            ->_setActiveMenu('report/df_invitation/customer')
            ->_addBreadcrumb(df_helper()->invitation()->__('Invitation Report by Customers'), df_helper()->invitation()->__('Invitation Report by Customers'))
            ->_addContent($this->getLayout()->createBlock('df_invitation/adminhtml_report_invitation_customer'))
            ->renderLayout();
    }

    /**
     * Export invitation customer report grid to CSV format
     */
    public function exportCustomerCsvAction()
    {
        $fileName   = 'invitation_customer.csv';
        $content    = $this->getLayout()->createBlock('df_invitation/adminhtml_report_invitation_customer_grid')
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export invitation customer report grid to Excel XML format
     */
    public function exportCustomerExcelAction()
    {
        $fileName   = 'invitation_customer.xml';
        $content    = $this->getLayout()->createBlock('df_invitation/adminhtml_report_invitation_customer_grid')
            ->getExcel($fileName);

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Report by order action
     */
    public function orderAction()
    {
        $this->_title($this->__('Reports'))
             ->_title($this->__('Invitations'))
             ->_title($this->__('Order Conversion Rate'));

        $this->_initAction()
            ->_setActiveMenu('report/df_invitation/order')
            ->_addBreadcrumb(df_helper()->invitation()->__('Invitation Report by Customers'), df_helper()->invitation()->__('Invitation Report by Order Conversion Rate'))
            ->_addContent($this->getLayout()->createBlock('df_invitation/adminhtml_report_invitation_order'))
            ->renderLayout();
    }

    /**
     * Export invitation order report grid to CSV format
     */
    public function exportOrderCsvAction()
    {
        $fileName   = 'invitation_order.csv';
        $content    = $this->getLayout()->createBlock('df_invitation/adminhtml_report_invitation_order_grid')
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export invitation order report grid to Excel XML format
     */
    public function exportOrderExcelAction()
    {
        $fileName   = 'invitation_order.xml';
        $content    = $this->getLayout()->createBlock('df_invitation/adminhtml_report_invitation_order_grid')
            ->getExcel($fileName);

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Acl admin user check
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return df_helper()->invitation()->config()->isEnabled() &&
               Mage::getSingleton('admin/session')->isAllowed('report/df_invitation');
    }
}
