<?php


class Df_Sales_Helper_Order extends Mage_Core_Helper_Abstract {



	/**
	 * @return Mage_Sales_Model_Order_Config
	 */
	public function configSingleton () {

		/** @var Mage_Sales_Model_Order_Config $result  */
		$result =
			Mage::getSingleton('sales/order_config')
		;

		df_assert ($result instanceof Mage_Sales_Model_Order_Config);

		return $result;

	}
	

	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Helper_Order';
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