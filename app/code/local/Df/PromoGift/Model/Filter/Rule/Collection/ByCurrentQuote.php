<?php


/**
 * Для данного множества подарочных промо-правил возвращает подмножество,
 * относящееся к текущему заказу
 */
class Df_PromoGift_Model_Filter_Rule_Collection_ByCurrentQuote
	extends Df_Core_Model_Filter_Collection {


	/**
	 * @return Zend_Validate_Interface
	 */
	protected function createValidator () {

		$result =
			df_model (
				Df_PromoGift_Model_Validate_Rule_ApplicableToCurrentQuote
					::getNameInMagentoFormat()
			)
		;

		df_assert ($result instanceof Df_PromoGift_Model_Validate_Rule_ApplicableToCurrentQuote);

		return $result;
	}




	/**
	 * Должна возвращать класс элементов коллекции
	 *
	 * @return string
	 */
	protected function getItemClass () {
		return Df_SalesRule_Const::RULE_CLASS;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Filter_Rule_Collection_ByCurrentQuote';
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


