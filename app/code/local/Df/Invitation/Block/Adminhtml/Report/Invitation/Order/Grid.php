<?php


/**
 * Adminhtml invitation orders report grid block
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Block_Adminhtml_Report_Invitation_Order_Grid
    extends Mage_Adminhtml_Block_Report_Grid
{

    /**
     * Prepare report collection
     *
     * @return Df_Invitation_Block_Adminhtml_Report_Invitation_Order_Grid
     */
    protected function _prepareCollection()
    {
        parent::_prepareCollection();
        $this->getCollection()->initReport('df_invitation/report_invitation_order_collection');
        return $this;
    }

    /**
     * Prepare report grid columns
     *
     * @return Df_Invitation_Block_Adminhtml_Report_Invitation_Order_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('sent', array(
            'header'    =>df_helper()->invitation()->__('Invitations Sent'),
            'type'      =>'number',
            'index'     => 'sent',
            'width'     =>'200'
        ));

        $this->addColumn('accepted', array(
            'header'    =>df_helper()->invitation()->__('Invitations Accepted'),
            'type'      =>'number',
            'index'     => 'accepted',
            'width'     =>'200'
        ));

        $this->addColumn('purchased', array(
            'header'    =>df_helper()->invitation()->__('Accepted and Purchased'),
            'type'      =>'number',
            'index'     => 'purchased',
            'width'     =>'220'
        ));

        $this->addColumn('purchased_rate', array(
            'header'    =>df_helper()->invitation()->__('Conversion Rate'),
            'index'     =>'purchased_rate',
            'renderer'  => 'df_invitation/adminhtml_grid_column_renderer_percent',
            'type'      =>'string',
            'width'     =>'100'
        ));

        $this->addExportType('*/*/exportOrderCsv', df_helper()->invitation()->__('CSV'));
        $this->addExportType('*/*/exportOrderExcel', df_helper()->invitation()->__('Excel'));

        return parent::_prepareColumns();
    }


}
