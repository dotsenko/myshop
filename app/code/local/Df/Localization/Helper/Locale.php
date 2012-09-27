<?php


class Df_Localization_Helper_Locale extends Mage_Core_Helper_Abstract {



	/**                                                     
	 * @param string $localeCode
	 * @return string
	 */
	public function getLanguageCodeByLocaleCode ($localeCode) {
		
		df_param_string ($localeCode, 0);

		/** @var string $result  */
		$result =
			df_a (
				explode (
					self::SEPARATOR
					,
					$localeCode
				)
				,
				0
			)
		;


		df_result_string ($result);

		return $result;

	}

	
	const SEPARATOR = '_';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Helper_Locale';
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