<?php


/**
 * Invitations grid
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Block_Adminhtml_Invitation_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set defaults
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('invitationGrid');
        $this->setDefaultSort('date');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection
     *
     * @return Df_Invitation_Block_Adminhtml_Invitation_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('df_invitation/invitation')->getCollection()
            ->addWebsiteInformation()->addInviteeInformation();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return Df_Invitation_Block_Adminhtml_Invitation_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('df_invitation_id', array(
            'header'=> df_helper()->invitation()->__('ID'),
            'width' => 80,
            'align' => 'right',
            'type'  => 'text',
            'index' => 'invitation_id'
        ));

        $this->addColumn('email', array(
            'header' => df_helper()->invitation()->__('Invitee Email'),
            'index' => 'invitation_email',
            'type'  => 'text'
        ));

        $renderer = (Mage::getSingleton('admin/session')->isAllowed('customer/manage'))
            ? 'df_invitation/adminhtml_invitation_grid_column_invitee' : false;

        $this->addColumn('invitee', array(
            'header' => df_helper()->invitation()->__('Invitee Name'),
            'index'  => 'invitee_email',
            'type'   => 'text',
            'renderer' => $renderer,
        ));

        $this->addColumn('date', array(
            'header' => df_helper()->invitation()->__('Date Sent'),
            'index' => 'date',
            'type' => 'datetime',
            'gmtoffset' => true,
            'width' => 170
        ));

        $this->addColumn('signup_date', array(
            'header' => df_helper()->invitation()->__('Registered'),
            'index' => 'signup_date',
            'type' => 'datetime',
            'gmtoffset' => true,
            'width' => 150
        ));

        $this->addColumn('status', array(
            'header' => df_helper()->invitation()->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('df_invitation/source_invitation_status')->getOptions(),
            'width' => 140
        ));

        $this->addColumn('website_id', array(
            'header'  => df_helper()->invitation()->__('Valid on Website'),
            'index'   => 'website_id',
            'type'    => 'options',
            'options' => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(),
            'width'   => 150,
        ));

        $groups = Mage::getModel('customer/group')->getCollection()
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();

        $this->addColumn('group_id', array(
            'header' => df_helper()->invitation()->__('Invitee Customer Group'),
            'index' => 'group_id',
            'filter_index' => 'invitee_group_id',
            'type'  => 'options',
            'options' => $groups,
            'width' => 140
        ));

        return parent::_prepareColumns();
    }

    /**
     * Prepare mass-actions
     *
     * @return Df_Invitation_Block_Adminhtml_Invitation_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('invitation_id');
        $this->getMassactionBlock()->setFormFieldName('invitations');
        $this->getMassactionBlock()->addItem('cancel', array(
                'label' => df_helper()->invitation()->__('Discard selected'),
                'url' => $this->getUrl('*/*/massCancel'),
                'confirm' => df_helper()->invitation()->__('Are you sure you want to do this?')
        ));

        $this->getMassactionBlock()->addItem('resend', array(
                'label' => df_helper()->invitation()->__('Send selected'),
                'url' => $this->getUrl('*/*/massResend')
        ));

        return parent::_prepareMassaction();
    }

    /**
     * Row clock callback
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id' => $row->getId()));
    }
}
