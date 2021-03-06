<?php


/**
 * Reward action for converting spent money to points
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Model_Action_OrderExtra extends Df_Reward_Model_Action_Abstract
{
    /**
     * Quote instance, required for estimating checkout reward (order subtotal - discount)
     *
     * @var Mage_Sales_Model_Quote
     */
    protected $_quote = null;

    /**
     * Return action message for history log
     *
     * @param array $args Additional history data
     * @return string
     */
    public function getHistoryMessage($args = array())
    {
        $incrementId = isset($args['increment_id']) ? $args['increment_id'] : '';
        return df_helper()->reward()->__('Earned points for order #%s.', $incrementId);
    }

    /**
     * Setter for $_entity and add some extra data to history
     *
     * @param Varien_Object $entity
     * @return Df_Reward_Model_Action_Abstract
     */
    public function setEntity($entity)
    {
        parent::setEntity($entity);
        $this->getHistory()->addAdditionalData(array(
            'increment_id' => $this->getEntity()->getIncrementId()
        ));
        return $this;
    }

    /**
     * Quote setter
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return Df_Reward_Model_Action_OrderExtra
     */
    public function setQuote(Mage_Sales_Model_Quote $quote)
    {
        $this->_quote = $quote;
        return $this;
    }

    /**
     * Retrieve points delta for action
     *
     * @param int $websiteId
     * @return int
     */
    public function getPoints($websiteId)
    {
        if ($this->_quote) {
            $quote = $this->_quote;
            // known issue: no support for multishipping quote
            $address = $quote->getIsVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
            $monetaryAmount = $quote->getBaseSubtotal() - abs(1 * $address->getBaseDiscountAmount());
        } else {
            $monetaryAmount = $this->getEntity()->getBaseTotalPaid() - $this->getEntity()->getBaseShippingAmount() - $this->getEntity()->getBaseTaxAmount();
        }
        $pointsDelta = $this->getReward()->getRateToPoints()->calculateToPoints((float)$monetaryAmount);

		/**
		 * Вот здесь, если мы находимся на странице корзины, нелпохо бы ещё учесть ценовые правила
		 */

		if ($this->_quote) {

			/**
			 * Видимо, наличие $this->_quote указывает, что мы находимся на странице корзины.
			 * Смотрим, какие ценовые правила применимы к корзине.
			 */


			/** @var array $ruleIds  */
			$ruleIds = array ();

			foreach (df_helper ()->reward()->getSalesRuleApplications() as $salesRuleApplication) {

				/** @var Varien_Object $salesRuleApplication */
				df_assert ($salesRuleApplication instanceof Varien_Object);

				/** @var Mage_SalesRule_Model_Rule $rule */
				$rule = $salesRuleApplication->getData ('rule');

				df_assert ($rule instanceof Mage_SalesRule_Model_Rule);

				$ruleIds []= $rule->getId ();
			}


			$ruleIds = array_unique ($ruleIds);


			/** @var Df_Reward_Model_Mysql4_Reward $rewardResource  */
			$rewardResource = Mage::getResourceModel ('df_reward/reward');

			df_assert ($rewardResource instanceof Df_Reward_Model_Mysql4_Reward);



			/** @var array $rewardRules  */
			$rewardRules = $rewardResource->getRewardSalesrule (array_unique ($ruleIds));

			/** @var array $rulesPoints  */
			$rulesPoints= array ();

			foreach ($rewardRules as $rewardRule) {

				/** @var array|object $rewardRule */

				/** @var int $ruleId  */
				$ruleId = intval (df_a ($rewardRule, 'rule_id'));


				$rulesPoints [$ruleId] = df_a ($rewardRule, 'points_delta', 0);
			}


			foreach (df_helper ()->reward()->getSalesRuleApplications() as $salesRuleApplication) {

				/** @var Varien_Object $salesRuleApplication */
				df_assert ($salesRuleApplication instanceof Varien_Object);

				/** @var Mage_SalesRule_Model_Rule $rule */
				$rule = $salesRuleApplication->getData ('rule');

				df_assert ($rule instanceof Mage_SalesRule_Model_Rule);


				/** @var int $qty  */
				$qty = intval ($salesRuleApplication->getData ('qty'));


				/** @var int $maxQty  */
				$maxQty = intval ($rule->getDiscountQty());

				if (0 < $maxQty) {

					/** @var int $usedQty  */
					$usedQty = intval ($rule->getData ('used_qty'));

					$qty = min ($qty, $maxQty - $usedQty);

					/**
					 * Обратите внимание, что, в отличие от других типов правил,
					 * для накопительного правила мы трактуем параметр
					 * «Наибольшее количество товарных единиц, к которым применяется скидка»
					 * с учётом ВСЕХ товаров в корзине, а не толькоединиц конкретного товара.
					 */

					$rule->setData ('used_qty', $qty);

				}


				$pointsDelta += $qty * df_a ($rulesPoints, $rule->getId ());

				$ruleIds []= $rule->getId ();
			}

		}


        return $pointsDelta;
    }
}
