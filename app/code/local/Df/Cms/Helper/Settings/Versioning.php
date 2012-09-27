<?php

class Df_Cms_Helper_Settings_Versioning extends Df_Core_Helper_Settings {
	
	

	/**
	 * @return boolean
	 */
	public function isEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_cms/versioning/enabled'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function getDefault () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_cms/versioning/default'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Helper_Settings_Versioning';
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