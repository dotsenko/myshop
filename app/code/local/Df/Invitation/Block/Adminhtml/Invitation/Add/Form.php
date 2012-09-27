<?php


/**
 * Invintation create form
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Block_Adminhtml_Invitation_Add_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Return invitation form action url
     *
     * @return string
     */
    public function getActionUrl()
    {
        return $this->getUrl('*/*/save', array('_current' => true));
    }

    /**
     * Prepare invitation form
     *
     * @return Df_Invitation_Block_Adminhtml_Invitation_Add_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getActionUrl(),
                'method' => 'post'
            )
        );

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => df_helper()->invitation()->__('Invitations Information'),
            'class' => 'fieldset-wide'
        ));

        $fieldset->addField('email', 'textarea', array(
            'label' => df_helper()->invitation()->__('Enter each Email on New Line'),
            'required' => true,
            'class' => 'validate-emails',
            'name' => 'email'
        ));

        $fieldset->addField('message', 'textarea', array(
            'label' => df_helper()->invitation()->__('Message'),
            'name' => 'message'
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'select', array(
                'label' => df_helper()->invitation()->__('Send From'),
                'required' => true,
                'name' => 'store_id',
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()
            ));
        }

        $groups = Mage::getModel('customer/group')->getCollection()
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();

        $fieldset->addField('group_id', 'select', array(
            'label' => df_helper()->invitation()->__('Invitee Group'),
            'required' => true,
            'name' => 'group_id',
            'values' => $groups
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        $form->setValues($this->_getSession()->getInvitationFormData());

        return parent::_prepareForm();
    }

    /**
     * Return adminhtml session
     *
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }

}
