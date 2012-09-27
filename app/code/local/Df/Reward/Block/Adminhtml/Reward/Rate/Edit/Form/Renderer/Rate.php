<?php



/**
 * Reward rate form field (element) renderer
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Adminhtml_Reward_Rate_Edit_Form_Renderer_Rate
    extends Mage_Adminhtml_Block_Template
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Internal constructor
     */
    protected function _construct()
    {
        $this->setTemplate('df/reward/rate/form/renderer/rate.phtml');
    }

    /**
     * Return HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    /**
     * Getter
     * Return value index in element object
     *
     * @return string
     */
    public function getValueIndex()
    {
        return $this->getElement()->getValueIndex();
    }

    /**
     * Getter
     * Return value by given value index in element object
     *
     * @return float | integer
     */
    public function getValue()
    {
        return $this->getRate()->getData($this->getValueIndex());
    }

    /**
     * Getter
     * Return equal value index in element object
     *
     * @return string
     */
    public function getEqualValueIndex()
    {
        return $this->getElement()->getEqualValueIndex();
    }

    /**
     * Return value by given equal value index in element object
     *
     * @return float | integer
     */
    public function getEqualValue()
    {
        return $this->getRate()->getData($this->getEqualValueIndex());
    }
}
