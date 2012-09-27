<?php

class Df_Reports_Helper_Settings_Common extends Df_Core_Helper_Settings {




	/**
	 * @return boolean
	 */
	public function enableGroupByWeek () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_reports/common/enable_group_by_week'
			)
		;

		df_result_boolean ($result);

		return $result;

	}






	/**
	 * @return boolean
	 */
	public function getEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_reports/common/enabled'
			)
		;

		df_result_boolean ($result);

		return $result;
	}






	/**
	 * @return boolean
	 */
	public function needRemoveTimezoneNotice () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_reports/common/remove_timozone_notice'
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return boolean
	 */
	public function needSetEndDateToTheYesterday () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_reports/common/set_end_date_to_the_yesterday'
			)
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	public function getPeriodDuration () {


		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_reports/common/period_duration'
			)
		;

		if (is_null ($result)) {
			$result = Df_Reports_Model_System_Config_Source_Duration::UNDEFINED;
		}


		df_result_string ($result);


		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Helper_Settings_Common';
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