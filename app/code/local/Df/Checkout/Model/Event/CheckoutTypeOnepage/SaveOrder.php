<?php


/**
 * Cообщение:		«checkout_type_onepage_save_order»
 * Источник:		Mage_Sales_Model_Service_Quote::submitOrder()
 * [code]
		Mage::dispatchEvent('checkout_type_onepage_save_order', array('order'=>$order, 'quote'=>$quote));
 * [/code]
 *
 */
class Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrder
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
		return 'Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrder';
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



	const EXPECTED_EVENT_PREFIX = 'checkout_type_onepage_save_order';

	const EVENT_PARAM__QUOTE = 'quote';


}


