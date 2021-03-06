<?php

class Df_Garantpost_Model_Method_Light_Standard extends Df_Garantpost_Model_Method_Light {



	/**
	 * @override
	 * @return bool
	 */
	public function isApplicable () {


		/** @var bool $result  */
		$result =
				parent::isApplicable ()
			&&
				!$this->getRmConfig()->service()->needAcceptCashOnDelivery()
		;

		df_result_boolean ($result);

		return $result;

	}




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
	protected function getServiceCode () {
		return 'express';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTitleBase () {
		return 'стандартный';
	}




	const METHOD = 'light-standard';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Method_Light_Standard';
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


