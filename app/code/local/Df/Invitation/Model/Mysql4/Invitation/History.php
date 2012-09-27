<?php


/**
 * Invitation status history resource model
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Model_Mysql4_Invitation_History extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Intialize resource model
     *
     * @return void
     */
    protected function _construct ()
    {
        $this->_init('df_invitation/invitation_history', 'history_id');
    }
}
