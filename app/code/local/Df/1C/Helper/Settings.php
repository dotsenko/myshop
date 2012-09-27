<?php

class Df_1C_Helper_Settings extends Df_Core_Helper_Settings {


	/**
	 * @return Df_1C_Helper_Settings_General
	 */
	public function general () {

		/** @var Df_1C_Helper_Settings_General $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Helper_Settings_General $result  */
			$result = Mage::helper (Df_1C_Helper_Settings_General::getNameInMagentoFormat());

			df_assert ($result instanceof Df_1C_Helper_Settings_General);
		}

		return $result;
	}
	
	
	
	
	/**
	 * @return Df_1C_Helper_Settings_Orders
	 */
	public function orders () {

		/** @var Df_1C_Helper_Settings_Orders $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Helper_Settings_Orders $result  */
			$result = Mage::helper (Df_1C_Helper_Settings_Orders::getNameInMagentoFormat());

			df_assert ($result instanceof Df_1C_Helper_Settings_Orders);
		}

		return $result;
	}	
	
	



	/**
	 * @return Df_1C_Helper_Settings_Products
	 */
	public function products () {

		/** @var Df_1C_Helper_Settings_Products $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Helper_Settings_Products $result  */
			$result = Mage::helper (Df_1C_Helper_Settings_Products::getNameInMagentoFormat());

			df_assert ($result instanceof Df_1C_Helper_Settings_Products);
		}

		return $result;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Helper_Settings';
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