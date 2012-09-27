<?php


/**
 * Log grid container
 */
class Df_Logging_Block_Adminhtml_Details extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Store curent event
     *
     * @var Df_Logging_Model_Event
     */
    protected $_currentEevent = null;

    /**
     * Store current event user
     *
     * @var Mage_Admin_Model_User
     */
    protected $_eventUser = null;

    /**
     * Add back button
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_addButton('back', array(
            'label'   => Mage::helper('df_logging')->__('Back'),
            'onclick' => "setLocation('" . Mage::getSingleton('adminhtml/url')->getUrl('*/*/'). "')",
            'class'   => 'back'
        ));
    }

    /**
     * Header text getter
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->getCurrentEvent()) {
            return Mage::helper('df_logging')->__('Log Entry #%d', $this->getCurrentEvent()->getId());
        }
        return Mage::helper('df_logging')->__('Log Entry Details');
    }

    /**
     * Get current event
     *
     * @return Df_Logging_Model_Event|null
     */
    public function getCurrentEvent()
    {
        if (null === $this->_currentEevent) {
            $this->_currentEevent = Mage::registry('current_event');
        }
        return $this->_currentEevent;
    }

    /**
     * Convert x_forwarded_ip to string
     *
     * @return string|bool
     */
    public function getEventXForwardedIp()
    {
        if ($this->getCurrentEvent()) {
            $xForwarderFor = long2ip($this->getCurrentEvent()->getXForwardedIp());
            if ($xForwarderFor && $xForwarderFor != '0.0.0.0') {
                return $xForwarderFor;
            }
        }
        return false;
    }

    /**
     * Convert ip to string
     *
     * @return string|bool
     */
    public function getEventIp()
    {
        if ($this->getCurrentEvent()) {
            return long2ip($this->getCurrentEvent()->getIp());
        }
        return false;
    }

    /**
     * Replace /n => <br /> in event error_message
     *
     * @return string|bool
     */
    public function getEventError()
    {
        if ($this->getCurrentEvent()) {
            return nl2br($this->getCurrentEvent()->getErrorMessage());
        }
        return false;
    }

    /**
     * Get current event user
     *
     * @return Mage_Admin_Model_User|null
     */
    public function getEventUser()
    {
        if(null === $this->_eventUser){
            $this->_eventUser = Mage::getModel('admin/user')->load($this->getUserId());
        }
        return $this->_eventUser;
    }
}
