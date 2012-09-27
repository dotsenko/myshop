<?php

class Df_PonyExpress_Model_Method_Heavy_Economical extends Df_PonyExpress_Model_Method {



	/**
	 * @override
	 * @return string
	 */
	public function getMethod () {
		return 'heavy-economical';
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
		return 25;
	}


	/**
	 * @override
	 * @return int
	 */
	protected function getTimeOfDeliveryMin () {
		return 4;
	}





	/**
	 * @override
	 * @return string
	 */
	protected function getTitleBase () {
		return 'груз-эконом';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTitleForRequest () {
		return 'Груз-эконом';
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PonyExpress_Model_Method_Heavy_Economical';
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


