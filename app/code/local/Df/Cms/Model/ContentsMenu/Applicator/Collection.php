<?php

class Df_Cms_Model_ContentsMenu_Applicator_Collection extends Df_Varien_Data_Collection {


	/**
	 * @override
	 * @return string
	 */
	protected function getItemClass () {

		/** @var string $result */
		$result = Df_Cms_Model_ContentsMenu_Applicator::getClass();

		df_result_string ($result);

		return $result;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_ContentsMenu_Applicator_Collection';
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

