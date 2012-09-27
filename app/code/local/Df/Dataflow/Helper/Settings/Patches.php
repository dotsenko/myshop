<?php


class Df_Dataflow_Helper_Settings_Patches extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function fixFieldMappingGui () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_dataflow/patches/fix_field_mapping_gui'
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
		return 'Df_Dataflow_Helper_Settings_Patches';
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