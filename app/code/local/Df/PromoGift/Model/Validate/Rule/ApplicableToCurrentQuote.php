<?php


class Df_PromoGift_Model_Validate_Rule_ApplicableToCurrentQuote
	extends Df_Core_Model_Abstract
	implements Zend_Validate_Interface {



    /**
     * Returns an array of message codes that explain why a previous isValid() call
     * returned false.
     *
     * If isValid() was never called or if the most recent isValid() call
     * returned true, then this method returns an empty array.
     *
     * This is now the same as calling array_keys() on the return value from getMessages().
     *
     * @return array
     * @deprecated Since 1.5.0
     */
    public function getErrors() {
		return array ();
	}


    /**
     * @param  mixed $value
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible
     */
    public function isValid ($value) {

		$result =
				($value instanceof Mage_SalesRule_Model_Rule)
			&&
				(
					/** @var Mage_SalesRule_Model_Rule $value */

					$this->isApplicableToQuote ($value)
				)
		;

		df_result_boolean ($result);

		return $result;
	}



    /**
     * @return array
     */
    public function getMessages() {
		return array ();
	}





	/**
	 * @param Mage_SalesRule_Model_Rule $rule
	 * @return bool
	 */
	private function isTheCustomerAlreadyGotMaxGiftsByThisRuleDuringPrevoiusCheckouts (
		Mage_SalesRule_Model_Rule $rule
	) {
		$result = false;


		/**
		 * Не исчерпал ли покупатель возможность получать подарки по данному правилу
		 */
		$ruleId = $rule->getId();
		/** @var int $ruleId */

		if ($ruleId) {

			df_assert_integer ($ruleId);


			$usesPerCustomer = $rule [Df_SalesRule_Const::DB__SALES_RULE__USES_PER_CUSTOMER];
			/** @var int $usesPerCustomer */


			if ($usesPerCustomer) {

				df_assert_integer ($usesPerCustomer);


				$customerId = $this->getQuote()->getData ('customer_id');
				/** @var int $customerId */

				df_assert_integer ($customerId);


				/**
				 * Смотрим, сколько раз данный покупатель уже получал
				 * (не просто кладя в корзину, успешно завершая оформление заказа!)
				 * подарки по данному правилу
				 */

				$ruleCustomer = Mage::getModel('salesrule/rule_customer');
				/** @var Mage_SalesRule_Model_Rule_Customer $ruleCustomer */


				df_assert ($ruleCustomer instanceof Mage_SalesRule_Model_Rule_Customer);


				$ruleCustomer->loadByCustomerRule($customerId, $ruleId);


				if ($ruleCustomer->getId()) {

					if (
							$usesPerCustomer
						<=
							$ruleCustomer [Df_SalesRule_Const::DB__SALES_RULE_CUSTOMER__TIMES_USED]
					) {
						$result = true;
					}
				}
			}
		}

		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_boolean ($result);
		/*************************************/

		return $result;
	}







	/**
	 * @param Mage_SalesRule_Model_Rule $rule
	 * @return bool
	 */
	private function isApplicableToQuote (Mage_SalesRule_Model_Rule $rule) {

		$result = true;
		/** @var bool $result */


		/**
		 * Здесь надо отбраковать подарочное правило,
		 * если подарок по этому правилу уже находится в корзине покупателя.
		 *
		 * Такая отбраковка нужна не калькулятору ценовых правил
		 * (он-то сам прекрасно проводит такую отбраковку!),
		 * а нам: чтобы определить, стоит ли показывать наш блок с подарками.
		 *
		 *
		 * Я вижу пока 2 способа отбраковки.
		 * В обоих случаях требуется, чтобы калькулятор работал до данного метода
		 * (а так оно и есть — проверил на практике).
		 *
		 * 		[1]	У объекта Mage_SalesRule_Model_Rule в процессе работы калькулятора
		 * 			ведётся учёт характеристики «times_used».
		 *
		 * 		[2]	Мы можем самостоятельно вести такой учёт,
		 * 			перехватывая сообщение «salesrule_validator_process».
		 *
		 * Второй способ надёжнее — его и применим.
		 *
		 * Обратите внимание, что стандартный калькулятор ценовых правил
		 * работает (видимо) только на странице корзины.
		 *
		 * Поэтому, чтобы управлять видимостью нашего блока на других страницах,
		 * надо сохранять результаты вычислений в сессии.
		 *
		 *
		 * Результаты вычислений будем обнулять
		 * при получении сообщения «sales_quote_collect_totals_before»:
		 * как я посмотрел, это — идеальное сообщение для данного действия.
		 *
		 *
		 * @todo: Обратите внимание, что в некоторых магазинах
		 * покупатель способен класть товары в корзину, не переходя на страницу корзины.
		 *
		 * Видимо, в этом случае калькулятор не сработает, и мы будем не в состоянии
		 * правильно управлять видимостью нашего блока.
		 *
		 */


		$maxUsagesPerQuote =
			(int)
				(
					$rule
						->getData (
							Df_PromoGift_Const::DB__SALES_RULE__MAX_USAGES_PER_QUOTE
						)
				)
		;
		/** @var int $ruleUsageCountForCurrentQuote */


		$ruleUsageCountForCurrentQuote =
			df_helper ()->promoGift()->getCustomerRuleCounter()
				->getCounterValue ($rule->getId ())
		;
		/** @var int $ruleUsageCountForCurrentQuote */



		if (
				($ruleUsageCountForCurrentQuote >= $maxUsagesPerQuote)
			||
				$this->isTheCustomerAlreadyGotMaxGiftsByThisRuleDuringPrevoiusCheckouts ($rule)
		) {
			$result = false;
		}



		if ($result) {


			/**
			 * Распаковываем содержимое свойств conditions и actions,
			 * которое в БД хранится в запакованном (serialized) виде
			 */
			$rule->afterLoad ();


			$conditions = $rule->getConditions();
			/** @var Mage_SalesRule_Model_Rule_Condition_Combine $conditions */


			$container =
				new Varien_Object (
					array (
						'quote' => $this->getQuote()


						/**
						 * Для Mage_SalesRule_Model_Rule_Condition_Product_Found
						 */,
						'all_items' => $this->getQuote()->getAllItems()
					)
				)
			;

			$result =
				$conditions->validate (
					$container
				)
			;

		}



		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_boolean ($result);
		/*************************************/

		return $result;
	}






	/**
	 * @return Mage_Sales_Model_Quote
	 */
	private function getQuote () {


		if (!isset ($this->_quote)) {


			$checkoutSession = Mage::getSingleton(Df_Checkout_Const::SESSION_CLASS_MF);
			/** @var Mage_Checkout_Model_Session $checkoutSession */

			df_assert ($checkoutSession instanceof Mage_Checkout_Model_Session);

			$result = $checkoutSession->getQuote ();
			/** @var Mage_Sales_Model_Quote $quote */

			df_assert ($result instanceof Mage_Sales_Model_Quote);

			$this->_quote = $result;

		}

		return $this->_quote;

	}


	/**
	 * @var Mage_Sales_Model_Quote
	 */
	private $_quote;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Validate_Rule_ApplicableToCurrentQuote';
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


