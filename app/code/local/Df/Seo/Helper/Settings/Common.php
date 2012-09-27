<?php


class Df_Seo_Helper_Settings_Common extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getEnhancedRussianTransliteration () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_seo/common/enhanced_russian_transliteration'
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
		return 'Df_Seo_Helper_Settings_Common';
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