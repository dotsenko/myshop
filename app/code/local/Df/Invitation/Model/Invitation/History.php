<?php


/**
 * Invitation status history model
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Model_Invitation_History extends Mage_Core_Model_Abstract
{
    /**
     * Initialize model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('df_invitation/invitation_history');
    }

    /**
     * Return status text
     *
     * @return string
     */
    public function getStatusText()
    {
        return Mage::getSingleton('df_invitation/source_invitation_status')->getOptionText(
            $this->getStatus()
        );
    }

    /**
     * Set additional data before saving
     *
     * @return Df_Invitation_Model_Invitation_History
     */
    protected function _beforeSave()
    {
        $this->setDate($this->getResource()->formatDate(time()));
        return parent::_beforeSave();
    }
}
