<?php


/**
 * General Logging container
 */
class Df_Logging_Block_Adminhtml_Container extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Curent event data storage
     *
     * @deprecated after 1.6.0.0
     * @var object
     */
    protected $_eventData = null;

    /**
     * Remove add button
     * Set block group and controller
     *
     */
    public function __construct()
    {
        $action = Mage::app()->getRequest()->getActionName();
        $this->_blockGroup = 'df_logging';
        $this->_controller = 'adminhtml_' . $action;

        parent::__construct();
        $this->_removeButton('add');
    }

    /**
     * Header text getter
     *
     * @return string
     */
    public function getHeaderText()
    {
        return Mage::helper('df_logging')->__($this->getData('header_text'));
    }

    /**
     * Get current event data
     *
     * @deprecated after 1.6.0.0
     * @return object Df_Logging_Model_Event
     */
    public function getEventData()
    {
        if (!$this->_eventData) {
            $this->_eventData = Mage::registry('current_event');
        }
        return $this->_eventData;
    }

    /**
     * Convert x_forwarded_ip to string
     *
     * @deprecated after 1.6.0.0
     * @return string
     */
    public function getEventXForwardedIp()
    {
        return long2ip($this->getEventData()->getXForwardedIp());
    }

    /**
     * Convert ip to string
     *
     * @deprecated after 1.6.0.0
     * @return string
     */
    public function getEventIp()
    {
        return long2ip($this->getEventData()->getIp());
    }

    /**
     * Replace /n => <br /> in event error_message
     *
     * @deprecated after 1.6.0.0
     * @return string
     */
    public function getEventError()
    {
        return nl2br($this->getEventData()->getErrorMessage());
    }
}
