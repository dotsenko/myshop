<?php


/**
 * Invitation data model
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Model_Observer
{
    /**
     * Flag that indicates customer registration page
     *
     * @var boolean
     */
    protected $_flagInCustomerRegistration = false;

    protected $_config;

    public function __construct()
    {
        $this->_config = df_helper()->invitation()->config();
    }

    /**
     * Observe customer registration for invitations
     *
     * @return void
     */
    public function restrictCustomerRegistration(Varien_Event_Observer $observer)
    {
        if (!$this->_config->isEnabledOnFront()) {
            return;
        }

        $result = $observer->getEvent()->getResult();

        if (!$result->getIsAllowed()) {
            df_helper()->invitation()->isRegistrationAllowed(false);
        } else {
            df_helper()->invitation()->isRegistrationAllowed(true);
            $result->setIsAllowed(!$this->_config->getInvitationRequired());
        }
    }

    /**
     * Custom log invitation log action
     *
     * @deprecated after 1.6.0.0
     *
     * @param Interprise_Invitation_Model_Invitation $model
     * @param Df_Logging_Model_Processor $processor
     * @return Df_Logging_Model_Event_Changes
     */
    public function logInvitationSave($model, $processor)
    {
        $processor->collectId($model);
        return Mage::getModel('df_logging/event_changes')
            ->setOrigibalData(array())
            ->setResultData($model->getData());
    }
}
