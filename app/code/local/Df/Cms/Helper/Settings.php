<?php

class Df_Cms_Helper_Settings extends Df_Core_Helper_Settings {
	
	
	
	/**
	 * @return Df_Cms_Helper_Settings_Hierarchy
	 */
	public function hierarchy () {
	
		/** @var Df_Cms_Helper_Settings_Hierarchy $result */
		static $result;
	
		if (!isset ($result)) {
	
			/** @var Df_Cms_Helper_Settings_Hierarchy $result  */
			$result = Mage::helper (Df_Cms_Helper_Settings_Hierarchy::getNameInMagentoFormat());
	
			df_assert ($result instanceof Df_Cms_Helper_Settings_Hierarchy);
	
		}
	
		return $result;
	
	}	
	
	
	
	
	
	/**
	 * @return Df_Cms_Helper_Settings_Versioning
	 */
	public function versioning () {
	
		/** @var Df_Cms_Helper_Settings_Versioning $result */
		static $result;
	
		if (!isset ($result)) {
	
			/** @var Df_Cms_Helper_Settings_Versioning $result  */
			$result = Mage::helper (Df_Cms_Helper_Settings_Versioning::getNameInMagentoFormat());
	
			df_assert ($result instanceof Df_Cms_Helper_Settings_Versioning);
	
		}
	
		return $result;
	
	}	
	
	
	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Helper_Settings';
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