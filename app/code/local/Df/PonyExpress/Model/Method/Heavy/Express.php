<?php

class Df_PonyExpress_Model_Method_Heavy_Express extends Df_PonyExpress_Model_Method {



	/**
	 * @override
	 * @return string
	 */
	public function getMethod () {
		return 'heavy-express';
	}




	/**
	 * @return bool
	 */
	public function isApplicable () {


		/** @var bool $result  */
		$result =
				(80 <= $this->getRequest()->getWeightInKilogrammes())
			&&
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
		return 8;
	}



	/**
	 * @override
	 * @return int
	 */
	protected function getTimeOfDeliveryMin () {
		return 1;
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTitleBase () {
		return 'груз-экспресс';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTitleForRequest () {
		return 'Груз-экспресс';
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PonyExpress_Model_Method_Heavy_Express';
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


