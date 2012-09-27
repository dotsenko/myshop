<?php


/**
 * Cообщение:		«checkout_type_onepage_save_order_after»
 * Источник:		Mage_Checkout_Model_Type_Onepage::saveOrder()
 * [code]
            Mage::dispatchEvent('checkout_type_onepage_save_order_after',
                array('order'=>$order, 'quote'=>$this->getQuote()));
 * [/code]
 *
 */
class Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrderAfter
	extends Df_Checkout_Model_Event_SaveOrder_Abstract {



	/**
	 * @return Mage_Sales_Model_Quote
	 */
	public function getQuote () {

		/** @var Mage_Sales_Model_Quote $result  */
		$result = $this->getEventParam (self::EVENT_PARAM__QUOTE);

		df_assert ($result instanceof Mage_Sales_Model_Quote);

		return $result;
	}




	/**
	 * @return string
	 */
	protected function getExpectedEventPrefix () {
		return self::EXPECTED_EVENT_PREFIX;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrderAfter';
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



	const EXPECTED_EVENT_PREFIX = 'checkout_type_onepage_save_order_after';

	const EVENT_PARAM__QUOTE = 'quote';


}


