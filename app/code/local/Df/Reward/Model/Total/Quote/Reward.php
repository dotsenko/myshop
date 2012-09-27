<?php



/**
 * Reward sales quote total model
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Model_Total_Quote_Reward extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function __construct()
    {
        $this->setCode('reward');
    }

    /**
     * Collect reward totals
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Df_Reward_Model_Total_Quote_Reward
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        /* @var $quote Mage_Sales_Model_Quote */
        $quote = $address->getQuote();
        if (!df_helper()->reward()->isEnabledOnFront($quote->getStore()->getWebsiteId())) {
            return $this;
        }

        if (!$quote->getRewardPointsTotalReseted() && $address->getBaseGrandTotal() > 0) {
            $quote->setRewardPointsBalance(0)
                ->setRewardCurrencyAmount(0)
                ->setBaseRewardCurrencyAmount(0);
            $address->setRewardPointsBalance(0)
                ->setRewardCurrencyAmount(0)
                ->setBaseRewardCurrencyAmount(0);
            $quote->setRewardPointsTotalReseted(true);
        }

        if ($address->getBaseGrandTotal() && $quote->getCustomer()->getId() && $quote->getUseRewardPoints()) {
            /* @var $reward Df_Reward_Model_Reward */
            $reward = $quote->getRewardInstance();
            if (!$reward || !$reward->getId()) {
                $reward = Mage::getModel('df_reward/reward')
                    ->setCustomer($quote->getCustomer())
                    ->setWebsite($quote->getStore()->getWebsiteId())
                    ->loadByCustomer();
            }
            $pointsLeft = $reward->getPointsBalance() - $quote->getRewardPointsBalance();
            $rewardCurrencyAmountLeft = ($quote->getStore()->convertPrice($reward->getCurrencyAmount())) - $quote->getRewardCurrencyAmount();
            $baseRewardCurrencyAmountLeft = $reward->getCurrencyAmount() - $quote->getBaseRewardCurrencyAmount();
            if ($baseRewardCurrencyAmountLeft >= $address->getBaseGrandTotal()) {
                $pointsBalanceUsed = $reward->getPointsEquivalent($address->getBaseGrandTotal());
                $pointsCurrencyAmountUsed = $address->getGrandTotal();
                $basePointsCurrencyAmountUsed = $address->getBaseGrandTotal();

                $address->setGrandTotal(0);
                $address->setBaseGrandTotal(0);
            } else {
                $pointsBalanceUsed = $pointsLeft;
                $pointsCurrencyAmountUsed = $rewardCurrencyAmountLeft;
                $basePointsCurrencyAmountUsed = $baseRewardCurrencyAmountLeft;

                $address->setGrandTotal($address->getGrandTotal() - $pointsCurrencyAmountUsed);
                $address->setBaseGrandTotal($address->getBaseGrandTotal() - $basePointsCurrencyAmountUsed);
            }
            $quote->setRewardPointsBalance($quote->getRewardPointsBalance() + $pointsBalanceUsed);
            $quote->setRewardCurrencyAmount($quote->getRewardCurrencyAmount() + $pointsCurrencyAmountUsed);
            $quote->setBaseRewardCurrencyAmount($quote->getBaseRewardCurrencyAmount() + $basePointsCurrencyAmountUsed);

            $address->setRewardPointsBalance($pointsBalanceUsed);
            $address->setRewardCurrencyAmount($pointsCurrencyAmountUsed);
            $address->setBaseRewardCurrencyAmount($basePointsCurrencyAmountUsed);
        }
        return $this;
    }

    /**
     * Retrieve reward total data and set it to quote address
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Df_Reward_Model_Total_Quote_Reward
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $websiteId = $address->getQuote()->getStore()->getWebsiteId();
        if (!df_helper()->reward()->isEnabledOnFront($websiteId)) {
            return $this;
        }
        if ($address->getRewardCurrencyAmount()) {
            $address->addTotal(array(
                'code'  => $this->getCode(),
                'title' => df_helper()->reward()->formatReward($address->getRewardPointsBalance()),
                'value' => -$address->getRewardCurrencyAmount()
            ));
        }
        return $this;
    }
}
