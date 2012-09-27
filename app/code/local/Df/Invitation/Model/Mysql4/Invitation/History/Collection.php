<?php


/**
 * Invitation status history collection
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Model_Mysql4_Invitation_History_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Intialize collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('df_invitation/invitation_history');
    }
}
