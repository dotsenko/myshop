<?php


/**
 * Adminhtml invitation customer report grid block
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Block_Adminhtml_Report_Invitation_Customer_Grid
    extends Mage_Adminhtml_Block_Report_Grid
{


    /**
     * Prepare report collection
     *
     * @return Df_Invitation_Block_Adminhtml_Report_Invitation_Customer_Grid
     */
    protected function _prepareCollection()
    {
        parent::_prepareCollection();
        $this->getCollection()->initReport('df_invitation/report_invitation_customer_collection');
        return $this;
    }

    /**
     * Prepare report grid columns
     *
     * @return Df_Invitation_Block_Adminhtml_Report_Invitation_Customer_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    =>df_helper()->invitation()->__('ID'),
            'index'     => 'entity_id'
        ));

        $this->addColumn('name', array(
            'header'    =>df_helper()->invitation()->__('Name'),
            'index'     => 'name'
        ));

        $this->addColumn('email', array(
            'header'    =>df_helper()->invitation()->__('Email'),
            'index'     => 'email'
        ));

        $this->addColumn('group', array(
            'header'    =>df_helper()->invitation()->__('Group'),
            'index'     => 'group_name'
        ));

        $this->addColumn('sent', array(
            'header'    =>df_helper()->invitation()->__('Invitations Sent'),
            'type'      =>'number',
            'index'     => 'sent'
        ));


        $this->addColumn('accepted', array(
            'header'    =>df_helper()->invitation()->__('Invitations Accepted'),
            'type'      =>'number',
            'index'     => 'accepted'
        ));

        $this->addExportType('*/*/exportCustomerCsv', df_helper()->invitation()->__('CSV'));
        $this->addExportType('*/*/exportCustomerExcel', df_helper()->invitation()->__('Excel'));

        return parent::_prepareColumns();
    }


}
