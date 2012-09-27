<?php


class Df_Localization_Helper_Data extends Mage_Core_Helper_Abstract {



	/**
	 * @return array
	 */
	public function getLanguages () {

		/** @var array $result  */
		$result =
			Mage::app()->getLocale()->getLocale()
				->getTranslationList(
					self::TRANSLATION_LIST__LANGUAGE
					,
					Mage::app()->getLocale()->getLocale()
				)
		;


		df_result_array ($result);

		return $result;

	}




	/**
	 * @return Df_Localization_Helper_Locale
	 */
	public function locale () {

		/** @var Df_Localization_Helper_Locale $result  */
		$result =
			Mage::helper (Df_Localization_Helper_Locale::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Localization_Helper_Locale);

		return $result;

	}



	/**
	 * @return Df_Localization_Helper_Translation
	 */
	public function translation () {

		/** @var Df_Localization_Helper_Translation $result  */
		$result =
			Mage::helper (Df_Localization_Helper_Translation::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Localization_Helper_Translation);

		return $result;

	}



	const TRANSLATION_LIST__LANGUAGE = 'language';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Helper_Data';
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