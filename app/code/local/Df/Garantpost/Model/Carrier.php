<?php

class Df_Garantpost_Model_Carrier extends Df_Shipping_Model_Carrier {


	/**
	 * @override
	 * @return string
	 */
	public function getRmId () {

		/** @var string $result  */
		$result = self::RM__ID;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return bool
	 */
	public function isTrackingAvailable () {
		return true;
	}





	/**
	 * @override
	 * @return string
	 */
	protected function getCollectorClassMf () {
		return Df_Garantpost_Model_Rate_Collector::getNameInMagentoFormat();
	}




	/**
	 * @return string
	 */
	protected function getConfigClassServiceMf () {
		return Df_Garantpost_Model_Config_Area_Service::getNameInMagentoFormat();
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getRmConfigClassMf () {
		return Df_Garantpost_Model_Config_Facade::getNameInMagentoFormat();
	}





	/**
	 * @override
	 * @return string
	 */
	protected function getRmFeatureCode () {
 		return Df_Core_Feature::GARANTPOST;
	}






	const RM__ID = 'garantpost';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Carrier';
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


