<?php

abstract class Df_Dellin_Model_Api_Locator extends Df_Core_Model_Abstract {


	/**
	 * @abstract
	 * @param string|null $location
	 * @param bool $isOrigin
	 * @return array
	 */
	abstract protected function getLocationData ($location, $isOrigin);



	/**
	 * @param string|null $location
	 * @param bool $isOrigin
	 * @return string
	 */
	public function getLocationId ($location, $isOrigin) {

		if (!is_null ($location)) {
			df_param_string ($location, 0);
		}

		df_param_boolean ($isOrigin, 1);



		if (df_empty ($location)) {
			df_error (
					$isOrigin
				?
					'Администратор должен указать город склада магазина'
				:
					'Укажите город'
			);
		}



		/** @var string $result  */
		$result =
			df_a (
				$this->getLocationData ($location, $isOrigin)
				,
				Df_Dellin_Model_Request_Locations::RESULT__ID
			)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @param string|null $location
	 * @param bool $isOrigin
	 * @return bool
	 */
	public function hasLocationTerminal ($location, $isOrigin) {

		if (!is_null ($location)) {
			df_param_string ($location, 0);
		}

		df_param_boolean ($isOrigin, 1);


		/** @var bool $result  */
		$result =
				0
			!==
				intval (
					df_a (
						$this->getLocationData ($location, $isOrigin)
						,
						Df_Dellin_Model_Request_Locations::RESULT__IS_TERMINAL
					)
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
		return 'Df_Dellin_Model_Api_Locator';
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


