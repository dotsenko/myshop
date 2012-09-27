<?php


/**
 * Customer invitation list block
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Block_Customer_List extends Mage_Customer_Block_Account_Dashboard
{
    /**
     * Return list of invitations
     *
     * @return Df_Invitation_Model_Mysql4_Invitation_Collection
     */
    public function getInvitationCollection()
    {
        if (!$this->hasInvitationCollection()) {
            $this->setData('invitation_collection', Mage::getModel('df_invitation/invitation')->getCollection()
                ->addOrder('invitation_id', Varien_Data_Collection::SORT_ORDER_DESC)
                ->loadByCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
            );
        }
        return $this->_getData('invitation_collection');
    }

    /**
     * Return status text for invitation
     *
     * @param Df_Invitation_Model_Invitation $invitation
     * @return string
     */
    public function getStatusText($invitation)
    {
        return Mage::getSingleton('df_invitation/source_invitation_status')
            ->getOptionText($invitation->getStatus());
    }
}
