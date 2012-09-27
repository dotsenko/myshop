<?php



/**
 * Reward rate edit form
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Adminhtml_Reward_Rate_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Getter
     *
     * @return Df_Reward_Model_Reward_Rate
     */
    public function getRate()
    {
        return Mage::registry('current_reward_rate');
    }

    /**
     * Prepare form
     *
     * @return Df_Reward_Block_Adminhtml_Reward_Rate_Edit_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('_current' => true)),
            'method' => 'post'
        ));
        $form->setFieldNameSuffix('rate');
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => df_helper()->reward()->__('Reward Exchange Rate Information')
        ));

        $fieldset->addField('website_id', 'select', array(
            'name'   => 'website_id',
            'title'  => df_helper()->reward()->__('Website'),
            'label'  => df_helper()->reward()->__('Website'),
            'values' => Mage::getModel('df_reward/source_website')->toOptionArray()
        ));

        $fieldset->addField('customer_group_id', 'select', array(
            'name'   => 'customer_group_id',
            'title'  => df_helper()->reward()->__('Customer Group'),
            'label'  => df_helper()->reward()->__('Customer Group'),
            'values' => Mage::getModel('df_reward/source_customer_groups')->toOptionArray()
        ));

        $fieldset->addField('direction', 'select', array(
            'name'   => 'direction',
            'title'  => df_helper()->reward()->__('Direction'),
            'label'  => df_helper()->reward()->__('Direction'),
            'values' => $this->getRate()->getDirectionsOptionArray()
        ));

        $rateRenderer = $this->getLayout()
            ->createBlock('df_reward/adminhtml_reward_rate_edit_form_renderer_rate')
            ->setRate($this->getRate());
        $fromIndex = $this->getRate()->getDirection() == Df_Reward_Model_Reward_Rate::RATE_EXCHANGE_DIRECTION_TO_CURRENCY
                   ? 'points' : 'currency_amount';
        $toIndex = $this->getRate()->getDirection() == Df_Reward_Model_Reward_Rate::RATE_EXCHANGE_DIRECTION_TO_CURRENCY
                 ? 'currency_amount' : 'points';
        $fieldset->addField('rate_to_currency', 'note', array(
            'title'             => df_helper()->reward()->__('Rate'),
            'label'             => df_helper()->reward()->__('Rate'),
            'value_index'       => $fromIndex,
            'equal_value_index' => $toIndex
        ))->setRenderer($rateRenderer);

        $form->setUseContainer(true);
        $form->setValues($this->getRate()->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
