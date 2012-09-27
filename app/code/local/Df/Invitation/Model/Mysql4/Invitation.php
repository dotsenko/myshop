<?php


/**
 * Invitation data resource model
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Model_Mysql4_Invitation extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Intialize resource model
     *
     * @return void
     */
    protected function _construct ()
    {
        $this->_init('df_invitation/invitation', 'invitation_id');
    }

    /**
     * Save invitation tracking info
     *
     * @param int $inviterId
     * @param int $referralId
     */
    public function trackReferral($inviterId, $referralId)
    {
        $inviterId  = (int)$inviterId;
        $referralId = (int)$referralId;
        $this->_getWriteAdapter()->query("REPLACE INTO {$this->getTable('df_invitation/invitation_track')}
            (inviter_id, referral_id) VALUES ({$inviterId}, {$referralId})");
    }
}
