<?php


abstract class Df_Checkout_Model_Event_SaveOrder_Abstract extends Df_Core_Model_Event {


	/**
	 * @return Mage_Sales_Model_Order
	 */
	public function getOrder () {

		/** @var Mage_Sales_Model_Order $result  */
		$result = $this->getEventParam (self::EVENT_PARAM__ORDER);

		df_assert ($result instanceof Mage_Sales_Model_Order);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Event_SaveOrder_Abstract';
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


	const EVENT_PARAM__ORDER = 'order';


}


