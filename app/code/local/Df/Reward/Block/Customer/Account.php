<?php


/**
 * Customer Account empty block (using only just for adding RP link to tab)
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Customer_Account extends Df_Core_Block_Abstract
{
    /**
     * Add RP link to tab if we have all rates
     *
     * @return Df_Reward_Block_Customer_Account
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');

		if (
				$navigationBlock
			&&
				df_helper()->reward()->isEnabledOnFront()
            &&
				df_helper()->reward()->getHasRates()
		) {
            $navigationBlock->addLink('df_reward', 'df_reward/customer/info/',
                df_helper()->reward()->__('Reward Points'));
        }
        return $this;
    }
}
