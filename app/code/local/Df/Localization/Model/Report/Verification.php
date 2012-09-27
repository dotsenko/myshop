<?php


class Df_Localization_Model_Report_Verification extends Df_Core_Model_Abstract {



	/**
	 * @return Df_Localization_Model_Translation_File_Collection
	 */
	public function getFiles () {

		/** @var Df_Localization_Model_Translation_File_Collection $result  */
		$result =
			df_helper()->localization()->translation()->getDefaultFileStorage()->getFiles()
		;

		df_assert ($result instanceof Df_Localization_Model_Translation_File_Collection);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Model_Report_Verification';
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


