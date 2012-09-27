<?php


/**
 * Customer My Account -> Reward Points container
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Customer_Reward extends Mage_Core_Block_Template
{
    /**
     * Set template variables
     *
     * @return string
     */
    protected function _toHtml()
    {
        $this->setBackUrl($this->getUrl('customer/account/'));
        return parent::_toHtml();
    }
}
