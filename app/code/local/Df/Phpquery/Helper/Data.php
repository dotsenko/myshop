<?php


class Df_Phpquery_Helper_Data extends Mage_Core_Helper_Abstract {
	

	/**
	 * @return Df_Phpquery_Helper_Lib
	 */
	public function lib () {
	
		/** @var Df_Phpquery_Helper_Lib $result */
		static $result;
	
		if (!isset ($result)) {
	
			/** @var Df_Phpquery_Helper_Lib $result  */
			$result = Mage::helper (Df_Phpquery_Helper_Lib::getNameInMagentoFormat());
	
			df_assert ($result instanceof Df_Phpquery_Helper_Lib);
	
		}
	
		return $result;
	
	}

	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Phpquery_Helper_Data';
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