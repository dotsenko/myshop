<?php


/**
 * Backend model for max_invitation_amount_per_send to set it's pervious value
 * in case admin user will enter invalid data (for example zero) bc this value can't be unlimited.
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Model_Adminhtml_System_Config_Backend_Limited
    extends Mage_Core_Model_Config_Data
{

    /**
     * Validating entered value if it will be 0 (unlimited)
     * throw notice and change it to old one
     *
     * @return Df_Invitation_Model_Adminhtml_System_Config_Backend_Limited
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        if ((int)$this->getValue() <= 0) {
            $parameter = df_helper()->invitation()->__('Max Invitations Allowed to be Sent at One Time');

            //if even old value is not valid we will have to you '1'
            $value = (int)$this->getOldValue();
            if ($value < 1) {
                $value = 1;

            }
            $this->setValue($value);
            Mage::getSingleton('adminhtml/session')->addNotice(
                df_helper()->invitation()->__('Invalid value used for "%s" parameter. Previous value saved.', $parameter)
            );
        }
        return $this;
    }
}
