<?php


class Df_PromoGift_Block_Cart_Gift_Highlighter extends Df_Core_Block_Template {


	/**
	 * @return array
	 */
	public function getGiftingQuoteItemIds () {

		if (!isset ($this->_giftingQuoteItemIds)) {

			$counter = df_helper ()->promoGift()->getCustomerRuleCounter();
			/** @var Df_PromoGift_Model_Customer_Rule_Counter $counter  */

			$result = $counter->getGiftingQuoteItemIds ();

			/*************************************
			 * Проверка результата работы метода
			 */
			df_result_array ($result);
			/*************************************/

			$this->_giftingQuoteItemIds = $result;
		}

		return $this->_giftingQuoteItemIds;

	}


	/**
	 * @var array
	 */
	private $_giftingQuoteItemIds;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Block_Cart_Gift_Highlighter';
	}


	/**
	 * Например, для класса Df_SalesRule_Block_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()

				/**
				 * Для блоков тоже работает
				 */
				->getModelNameInMagentoFormat (
					self::getClass()
				)
		;
	}


}


