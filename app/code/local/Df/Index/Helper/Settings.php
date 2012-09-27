<?php


class Df_Index_Helper_Settings extends Df_Core_Helper_Settings {



	/**
	 * @return int
	 */
	public function getVarcharLength () {

		/** @var string $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_tweaks_admin/system_indices/varchar_length'
				)
			)
		;

		df_result_integer ($result);


		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Index_Helper_Settings';
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