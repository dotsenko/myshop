<?php



/**
 * Checkout reward payment block
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Block_Checkout_Payment_Additional extends Mage_Core_Block_Template
{
    /**
     * Getter
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    /**
     * Getter
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return Mage::getSingleton('checkout/session')->getQuote();
    }

    /**
     * Getter
     *
     * @return Df_Reward_Model_Reward
     */
    public function getReward()
    {
        if (!$this->getData('reward')) {
            $reward = Mage::getModel('df_reward/reward')
                ->setCustomer($this->getCustomer())
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByCustomer();
            $this->setData('reward', $reward);
        }
        return $this->getData('reward');
    }

    /**
     * Return flag from quote to use reward points or not
     *
     * @return boolean
     */
    public function useRewardPoints()
    {
        return (bool)$this->getQuote()->getUseRewardPoints();
    }

    /**
     * Return true if customer can use his reward points.
     * In case if currency amount of his points more then 0 and has minimum limit of points
     *
     * @return boolean
     */
    public function getCanUseRewardPoints()
    {
        if (!df_helper()->reward()->getHasRates()
            || !df_helper()->reward()->isEnabledOnFront()) {
            return false;
        }
        $minPointsToUse = df_helper()->reward()
            ->getGeneralConfig('min_points_balance', (int)Mage::app()->getWebsite()->getId());
        $canUseRewadPoints = ($this->getPointsBalance() >= $minPointsToUse) ? true : false;
        return (boolean)(((float)$this->getCurrencyAmount() > 0) && $canUseRewadPoints);
    }

    /**
     * Getter
     *
     * @return integer
     */
    public function getPointsBalance()
    {
        return $this->getReward()->getPointsBalance();
    }

    /**
     * Getter
     *
     * @return float
     */
    public function getCurrencyAmount()
    {
        return $this->getReward()->getCurrencyAmount();
    }

    /**
     * Check if customer has enough points to cover total
     *
     * @return boolean
     */
    public function isEnoughPoints()
    {
        $baseGrandTotal = $this->getQuote()->getBaseGrandTotal()+$this->getQuote()->getBaseRewardCurrencyAmount();
        return $this->getReward()->isEnoughPointsToCoverAmount($baseGrandTotal);
    }



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reward_Block_Checkout_Payment_Additional';
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
