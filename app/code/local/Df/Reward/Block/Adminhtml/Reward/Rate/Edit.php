<?php



/**
 * Reward rate edit container
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Adminhtml_Reward_Rate_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Internal constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_objectId = 'rate_id';
        $this->_blockGroup = 'df_reward';
        $this->_controller = 'adminhtml_reward_rate';
    }

    /**
     * Getter.
     * Return header text in order to create or edit rate
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_reward_rate')->getId()) {
            return df_helper()->reward()->__('Edit Reward Exchange Rate');
        } else {
            return df_helper()->reward()->__('New Reward Exchange Rate');
        }
    }

    /**
     * rate validation URL getter
     *
     */
    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }
}
