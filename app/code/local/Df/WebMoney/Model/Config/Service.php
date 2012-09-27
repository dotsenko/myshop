<?php

class Df_WebMoney_Model_Config_Service extends Df_Payment_Model_Config_Area_Service {



	/**
	 * @override
	 * @return string
	 */
	public function getCurrencyCode () {

		/** @var string $result  */
		$result =
			$this->translateCurrencyCodeReversed (
				$this->getCurrencyCodeInServiceFormat()
			)
		;

		df_result_string ($result);
		df_assert (!df_empty ($result));

		return $result;

	}






	/**
	 * @override
	 * @return string
	 */
	public function getCurrencyCodeInServiceFormat () {

		if (!isset ($this->_currencyCodeInServiceFormat)) {

			/** @var string $result  */
			$result =
				strtoupper (
					substr (
						$this->getShopId()
						,
						0
						,
						1
					)
				)
			;

			df_assert_string ($result);

			$this->_currencyCodeInServiceFormat = $result;

		}


		df_result_string ($this->_currencyCodeInServiceFormat);

		return $this->_currencyCodeInServiceFormat;

	}


	/**
	* @var string
	*/
	private $_currencyCodeInServiceFormat;




	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WebMoney_Model_Config_Service';
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


