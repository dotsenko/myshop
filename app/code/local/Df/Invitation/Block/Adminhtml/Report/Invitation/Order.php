<?php


/**
 * Adminhtml invitation order report page content block
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Block_Adminhtml_Report_Invitation_Order extends
    Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_report_invitation_order';
        $this->_blockGroup = 'df_invitation';
        $this->_headerText = df_helper()->invitation()->__('Order Conversion Rate');
        parent::__construct();
        $this->_removeButton('add');
    }
}
