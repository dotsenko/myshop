<?php

class Df_Spsr_Model_Method_Economical extends Df_Spsr_Model_Method {



	/**
	 * @override
	 * @return string
	 */
	public function getMethod () {
		return 'first-class';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTitleBase () {
		return 'эконом';
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Method_Economical';
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


