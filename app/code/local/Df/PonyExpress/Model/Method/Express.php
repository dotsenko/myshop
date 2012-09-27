<?php

class Df_PonyExpress_Model_Method_Express extends Df_PonyExpress_Model_Method {



	/**
	 * @override
	 * @return string
	 */
	public function getMethod () {
		return 'express';
	}




	/**
	 * @return bool
	 */
	public function isApplicable () {


		/** @var bool $result  */
		$result =
			(20 >= $this->getRequest()->getWeightInKilogrammes())
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @override
	 * @return int
	 */
	protected function getTimeOfDeliveryMax () {
		return 7;
	}



	/**
	 * @override
	 * @return int
	 */
	protected function getTimeOfDeliveryMin () {
		return 2;
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTitleBase () {
		return 'экспресс';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTitleForRequest () {
		return 'Экспресс';
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PonyExpress_Model_Method_Express';
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


