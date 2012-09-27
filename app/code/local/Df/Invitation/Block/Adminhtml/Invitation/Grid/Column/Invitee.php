<?php


/**
 * Column renderer for Invitee in invitations grid
 *
 */
class Df_Invitation_Block_Adminhtml_Invitation_Grid_Column_Invitee
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Render invitee email linked to its account edit page
     *
     * @param   Varien_Object $row
     * @return  string
     */
    protected function _getValue(Varien_Object $row)
    {
        if (!$row->getReferralId()) {
            return '';
        }

		$customer = Mage::getModel ('customer/customer');

		/** @var Mage_Customer_Model_Customer $customer */
		$customer->load ($row->getReferralId());

        return '<a href="' . Mage::getSingleton('adminhtml/url')->getUrl('*/customer/edit', array('id' => $row->getReferralId())) . '">'
            . $this->htmlEscape($customer->getName()/*$row->getData($this->getColumn()->getIndex())*/) . '</a>';
    }
}
