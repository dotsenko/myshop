<?php

class Df_Sales_Helper_Settings extends Df_Core_Helper_Settings {
	
	
	/**
	 * @return Df_Sales_Helper_Settings_OrderComments
	 */
	public function orderComments () {

		/** @var Df_Sales_Helper_Settings_OrderComments $result  */
		$result =
			Mage::helper (
				Df_Sales_Helper_Settings_OrderComments::getNameInMagentoFormat()
			)
		;

		df_assert ($result instanceof Df_Sales_Helper_Settings_OrderComments);

		return $result;
	}



	/**
	 * @return Df_Sales_Helper_Settings_OrderGrid
	 */
	public function orderGrid () {

		/** @var Df_Sales_Helper_Settings_OrderGrid $result  */
		$result =
			Mage::helper (
				Df_Sales_Helper_Settings_OrderGrid::getNameInMagentoFormat()
			)
		;

		df_assert ($result instanceof Df_Sales_Helper_Settings_OrderGrid);

		return $result;
	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Helper_Settings';
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