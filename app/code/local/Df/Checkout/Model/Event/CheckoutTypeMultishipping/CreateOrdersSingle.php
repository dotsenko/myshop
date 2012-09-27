<?php


/**
 * Cообщение:		«checkout_type_multishipping_create_orders_single»
 * Источник:		Mage_Sales_Model_Service_Quote::submitOrder()
 * [code]
		foreach ($shippingAddresses as $address) {
			$order = $this->_prepareOrder($address);

			$orders[] = $order;
			Mage::dispatchEvent(
				'checkout_type_multishipping_create_orders_single',
				array('order'=>$order, 'address'=>$address)
			);
		}
 * [/code]
 *
 */
class Df_Checkout_Model_Event_CheckoutTypeMultishipping_CreateOrdersSingle
	extends Df_Checkout_Model_Event_SaveOrder_Abstract {



	/**
	 * @return Mage_Sales_Model_Quote_Address
	 */
	public function getAddress () {

		/** @var Mage_Sales_Model_Quote_Address $result  */
		$result = $this->getEventParam (self::EVENT_PARAM__ADDRESS);

		df_assert ($result instanceof Mage_Sales_Model_Quote_Address);

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
		return 'Df_Checkout_Model_Event_CheckoutTypeMultishipping_CreateOrdersSingle';
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



	const EXPECTED_EVENT_PREFIX = 'checkout_type_multishipping_create_orders_single';
	const EVENT_PARAM__ADDRESS = 'address';


}


