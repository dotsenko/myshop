<?php


class Df_PromoGift_Model_Customer_Rule_Counter extends Df_Core_Model_Abstract {


	/**
	 * @return array
	 */
	public function getGiftingQuoteItemIds () {

		$result = array ();
		/** @var array $result */

		$dataContainer = $this->getDataContainer();
		/** @var array $dataContainer */

		df_assert_array ($dataContainer);

		foreach ($dataContainer as $ruleData) {

			/** @var array $ruleData */
			df_assert_array($ruleData);

			$ruleQuoteItems= df_a ($ruleData, self::KEY_RULE_QUOTE_ITEMS, array ());

			df_assert_array ($ruleQuoteItems);


			$result = array_merge ($result, array_values ($ruleQuoteItems));

		}

		df_result_array ($result);

		return $result;
	}





	/**
	 * @return Df_PromoGift_Model_Customer_Rule_Counter
	 */
	public function reset () {
		$this->getSession ()->unsetData (self::SESSION_CONTAINER_KEY);
		return $this;
	}



	/**
	 * @param  int $ruleId
	 * @param int $quoteItemId
	 * @return Df_PromoGift_Model_Customer_Rule_Counter
	 */
	public function count ($ruleId, $quoteItemId) {
		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_integer ($ruleId, 0);
		df_param_integer ($quoteItemId, 1);
		/*************************************/


		$counterValue = $this->getCounterValue ($ruleId);
		/** @var int $counterValue */

		df_assert_integer ($counterValue);


		$counterValue++;


		$ruleData = $this->getRuleData ($ruleId);

		df_assert_array ($ruleData);


		$ruleData [self::KEY_RULE_COUNTER] = $counterValue;


		/**
		 * Учитываем подарочный товар
		 */
		$ruleQuoteItems = df_a ($ruleData, self::KEY_RULE_QUOTE_ITEMS, array ());
		$ruleQuoteItems []= $quoteItemId;
		$ruleData [self::KEY_RULE_QUOTE_ITEMS] = $ruleQuoteItems;




		$ruleData [self::KEY_RULE_COUNTER] = $counterValue;


		$this->setRuleData ($ruleId, $ruleData);

		return $this;
	}




	/**
	 * @param  int $ruleId
	 * @return int
	 */
	public function getCounterValue ($ruleId) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_integer ($ruleId, 0);
		/*************************************/


		$ruleData = $this->getRuleData ($ruleId);

		df_assert_array ($ruleData);


		$result = df_a ($ruleData, self::KEY_RULE_COUNTER, 0);
		/** @var int $result */


		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_integer ($result);
		/*************************************/

		return $result;
	}





	/**
	 * @param  int $ruleId
	 * @return array
	 */
	private function getRuleData ($ruleId) {

		$dataContainer = $this->getDataContainer();
		/** @var array $dataContainer */

		df_assert_array ($dataContainer);

		$result = df_a ($dataContainer, $ruleId);

		if (is_null ($result)) {

			$result = array ();

			$dataContainer [$ruleId] = $result;

			$this->setDataContainer ($dataContainer);
		}

		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_array ($result);
		/*************************************/

		return $result;

	}




	/**
	 * @param  int $ruleId
	 * @param array $ruleData
	 * @return Df_PromoGift_Model_Customer_Rule_Counter
	 */
	private function setRuleData ($ruleId, array $ruleData) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_integer ($ruleId, 0);
		df_param_array ($ruleData, 1);
		/*************************************/


		$dataContainer = $this->getDataContainer();
		/** @var array $dataContainer */

		df_assert_array ($dataContainer);

		$dataContainer [$ruleId] = $ruleData;

		$this->setDataContainer ($dataContainer);


		return $this;
	}







	/**
	 * @return array
	 */
	private function getDataContainer () {

		$result = $this->getSession ()->getData (self::SESSION_CONTAINER_KEY);

		if (is_null ($result)) {

			$result = array ();

			$this->setDataContainer ($result);
		}

		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_array ($result);
		/*************************************/

		return $result;
	}





	/**
	 * @param array $dataContainer
	 * @return Df_PromoGift_Model_Customer_Rule_Counter
	 */
	private function setDataContainer (array $dataContainer) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_array ($dataContainer, 0);
		/*************************************/

		$this->getSession ()->setData (self::SESSION_CONTAINER_KEY, $dataContainer);

		return $this;
	}






	/**
	 * @return Mage_Customer_Model_Session
	 */
	private function getSession () {
		return Mage::getSingleton (Df_Customer_Const::SESSION_CLASS_MF);
	}





	const SESSION_CONTAINER_KEY = 'promo_gift_counter';

	const KEY_RULE_COUNTER = 'counter';
	const KEY_RULE_QUOTE_ITEMS = 'quote_items';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Customer_Rule_Counter';
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


