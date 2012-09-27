<?php


class Df_Directory_Helper_Finder extends Mage_Core_Helper_Abstract {



	/**
	 * @return Df_Directory_Model_Finder_CallingCode
	 */
	public function callingCode () {

		/** @var Df_Directory_Model_Finder_CallingCode $result  */
		$result =
			Mage::getSingleton (
				Df_Directory_Model_Finder_CallingCode::getNameInMagentoFormat()
			)
		;

		df_assert ($result instanceof Df_Directory_Model_Finder_CallingCode);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Helper_Finder';
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