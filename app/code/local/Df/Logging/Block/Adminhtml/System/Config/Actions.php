<?php


/**
 * Action group checkboxes renderer for system configuration
 */
class Df_Logging_Block_Adminhtml_System_Config_Actions
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Set template
     */
    protected function _construct()
    {
        $this->setTemplate('df/logging/system/config/actions.phtml');
        return parent::_construct();
    }

    /**
     * Action group labels getter
     *
     * @return array
     */
    public function getLabels()
    {
        return Mage::getSingleton('df_logging/config')->getLabels();
    }

    /**
     * Check whether specified group is active
     *
     * @param string $key
     * @return bool
     */
    public function getIsChecked($key)
    {
        return Mage::getSingleton('df_logging/config')->isActive($key, true);
    }

    /**
     * Render element html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setNamePrefix($element->getName())
            ->setHtmlId($element->getHtmlId());
        return $this->_toHtml();
    }
}
