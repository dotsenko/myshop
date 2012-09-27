<?php

class Df_Pec_Model_Method_Ground extends Df_Pec_Model_Method {


	/**
	 * @override
	 * @return string
	 */
	public function getMethod () {
		return self::METHOD;
	}



	/**
	 * @override
	 * @return string
	 */
	protected function getTitleBase () {
		return 'наземная';
	}




	const METHOD = 'ground';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pec_Model_Method_Ground';
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


