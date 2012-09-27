<?php


class Df_Localization_Model_Translation_File_Collection
	extends Df_Varien_Data_Collection {



	/**
	 * @override
	 * @return string
	 */
	protected function getItemClass () {

		/** @var string $result */
		$result = Df_Localization_Model_Translation_File::getClass();

		return $result;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Model_Translation_File_Collection';
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


