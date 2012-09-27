<?php

class Df_Directory_Helper_Settings_Regions_Ru extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_directory/regions_ru/enabled'
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @param int $position
	 * @return int
	 */
	public function getPriorityRegionIdAtPosition ($position) {

		df_param_integer ($position, 0);
		df_param_between ($position, 0, 1, self::NUM_PRIORITY_REGIONS);

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					sprintf (
						'df_directory/regions_ru/position_%d'
						,
						$position
					)
				)
			)
		;

		df_result_integer ($result);

		return $result;

	}


	const NUM_PRIORITY_REGIONS = 5;



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Helper_Settings_Regions_Ru';
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