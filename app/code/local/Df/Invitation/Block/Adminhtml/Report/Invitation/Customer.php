<?php


/**
 * Adminhtml invitation customer report page content block
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Block_Adminhtml_Report_Invitation_Customer extends
    Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_report_invitation_customer';
        $this->_blockGroup = 'df_invitation';
        $this->_headerText = df_helper()->invitation()->__('Customers');
        parent::__construct();
        $this->_removeButton('add');
    }
}
