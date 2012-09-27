<?php


class Df_Ems_Helper_Data extends Mage_Core_Helper_Abstract {


	/**
	 * @return Df_Ems_Helper_Api
	 */
	public function api () {

		/** @var Df_Ems_Helper_Api $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Ems_Helper_Api $result  */
			$result = Mage::helper (Df_Ems_Helper_Api::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Ems_Helper_Api);

		}

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Ems_Helper_Data';
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