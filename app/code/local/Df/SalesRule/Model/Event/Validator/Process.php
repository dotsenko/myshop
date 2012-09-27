<?php

/**
 * Cообщение:		«salesrule_validator_process»
 * Источник:		Mage_SalesRule_Model_Validator::process()
 * [code]
		Mage::dispatchEvent('salesrule_validator_process', array(
			'rule'    => $rule,
			'item'    => $item,
			'address' => $address,
			'quote'   => $quote,
			'qty'     => $qty,
			'result'  => $result,
		));
 * [/code]
 *
 * Назначение:		Обработчик может изменить результат работы ценового правила
 */
class Df_SalesRule_Model_Event_Validator_Process extends Df_Core_Model_Event {




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_SalesRule_Model_Event_Validator_Process';
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








	/**
	 * Возвращает результат стандартной работы ценового правила.
	 * Меняя характеристики данного объекта — мы меняем результат работы ценового правила.
	 *
	 * @return Varien_Object
	 */
	public function getResult () {

		$result = $this->getObserver()->getData (self::PARAM_RESULT);

		df_assert ($result instanceof Varien_Object);

		return $result;

	}





	/**
	 * Адрес данного заказа
	 *
	 * @return Mage_Sales_Model_Quote_Address
	 */
	public function getAddress () {
		$result = $this->getEventParam (self::PARAM_ADDRESS);

		df_assert ($result instanceof Mage_Sales_Model_Quote_Address);

		return $result;
	}





	/**
	 * Текущая графа из заказа
	 *
	 * @return Mage_Sales_Model_Quote_Item_Abstract
	 */
	public function getCurrentQuoteItem () {
		$result = $this->getEventParam (self::PARAM_ITEM);

		df_assert ($result instanceof Mage_Sales_Model_Quote_Item_Abstract);

		return $result;
	}





	/**
	 * Возвращает текущее ценовое правило
	 *
	 * @return Mage_SalesRule_Model_Rule
	 */
	public function getRule () {
		$result = $this->getEventParam (self::PARAM_RULE);

		df_assert ($result instanceof Mage_SalesRule_Model_Rule);

		return $result;
	}



	/**
	 * Количество единиц товара, на которое распространяется скидка.
	 * Определяется администратором при редактировании ценового правила.
	 * Если же администратор не определил данное значение, то скидка распространяется
	 * на все единицы товара в заказе
	 *
	 * @see Mage_SalesRule_Model_Validator::_getItemQty()
	 *
	 * [code]
			$qty = $item->getTotalQty();
			return $rule->getDiscountQty() ? min($qty, $rule->getDiscountQty()) : $qty;
	 * [/code]
	 *
	 * @return int
	 */
	public function getQty () {
		$result = $this->getEventParam (self::PARAM_QTY);

		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_between ($result, 0);
		/*************************************/

		return $result;
	}



	/**
	 * Содержимое заказа
	 *
	 * @return Mage_Sales_Model_Quote
	 */
	public function getQuote () {
		$result = $this->getEventParam (self::PARAM_QUOTE);

		df_assert ($result instanceof Mage_Sales_Model_Quote);

		return $result;
	}



	/**
	 * @return string
	 */
	protected function getExpectedEventPrefix () {
		return self::EXPECTED_EVENT_PREFIX;
	}

	const EXPECTED_EVENT_PREFIX = 'salesrule_validator_process';

	const PARAM_QUOTE =		'quote';
	const PARAM_QTY =		'qty';
	const PARAM_RULE =		'rule';
	const PARAM_ITEM =		'item';
	const PARAM_ADDRESS =	'address';
	const PARAM_RESULT =		'result';

}
