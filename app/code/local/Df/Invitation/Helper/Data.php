<?php


/**
 * Invitation data helper
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_isRegistrationAllowed = null;


	/**
	 * @return Df_Invitation_Model_Config
	 */
	public function config () {

		/** @var Df_Invitation_Model_Config $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Invitation_Model_Config $result  */
			$result = Mage::getSingleton ('df_invitation/config');

			df_assert ($result instanceof Df_Invitation_Model_Config);

		}

		return $result;

	}



    /**
     * Return max Invitation amount per send by config.
     * Deprecated. Config model 'df_invitation/config' should be used directly.
     *
     * @return int
     */
    public function getMaxInvitationsPerSend()
    {
        return $this->config()->getMaxInvitationsPerSend();
    }

    /**
     * Return config value for required cutomer registration by invitation
     * Deprecated. Config model 'df_invitation/config' should be used directly.
     *
     * @return boolean
     */
    public function getInvitationRequired()
    {
        return $this->config()->getInvitationRequired();
    }


    /**
     * Return config value for use same group as inviter
     * Deprecated. Config model 'df_invitation/config' should be used directly.
     *
     * @return boolean
     */
    public function getUseInviterGroup()
    {
        return $this->config()->getUseInviterGroup();
    }

    /**
     * Check whether invitations allow to set custom message
     * Deprecated. Config model 'df_invitation/config' should be used directly.
     *
     * @return bool
     */
    public function isInvitationMessageAllowed()
    {
        return $this->config()->isInvitationMessageAllowed();
    }

    /**
     * Return text for invetation status
     *
     * @return Df_Invitation_Model_Invitation $invitation
     * @return string
     */
    public function getInvitationStatusText($invitation)
    {
        return Mage::getSingleton('df_invitation/source_invitation_status')->getOptionText($invitation->getStatus());
    }

    /**
     * Return invitation url
     *
     * @param Df_Invitation_Model_Invitation $invitation
     * @return string
     */
    public function getInvitationUrl($invitation)
    {
        return Mage::getModel('core/url')->setStore($invitation->getStoreId())
            ->getUrl('df_invitation/customer_account/create', array(
                'invitation' => df_mage()->coreHelper()->urlEncode($invitation->getInvitationCode()),
                '_store_to_url' => true
            ));
    }

    /**
     * Return account dashboard invitation url
     *
     * @return string
     */
    public function getCustomerInvitationUrl()
    {
        return $this->_getUrl('df_invitation/');
    }

    /**
     * Return invitation send form url
     *
     * @return string
     */
    public function getCustomerInvitationFormUrl()
    {
        return $this->_getUrl('df_invitation/index/send');
    }

    /**
     * Checks is allowed registration in invitation controller
     *
     * @param boolean $isAllowed
     * @return boolean
     */
    public function isRegistrationAllowed($isAllowed = null)
    {
        if ($isAllowed === null && $this->_isRegistrationAllowed === null) {
            $result = Mage::helper('customer')->isRegistrationAllowed();
            if ($this->_isRegistrationAllowed === null) {
                $this->_isRegistrationAllowed = $result;
            }
        } elseif ($isAllowed !== null) {
            $this->_isRegistrationAllowed = $isAllowed;
        }

        return $this->_isRegistrationAllowed;
    }

    /**
     * Retrieve configuration for availability of invitations
     * Deprecated. Config model 'df_invitation/config' should be used directly.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->config()->isEnabled();
    }






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Invitation_Helper_Data';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {

		/** @var string $result */
		static $result;

		if (!isset ($result)) {
			$result = df()->reflection()->getModelNameInMagentoFormat (self::getClass());
		}

		return $result;
	}

}
