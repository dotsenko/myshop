<?php

class Df_Megapolis_Model_Rate_Collector extends Df_Shipping_Model_Rate_Collector {


	
	/**
	 * @override
	 * @return array
	 */
	protected function getMethods () {
	
		if (!isset ($this->_methods)) {

			/** @var array $result  */
			$result = array ();


			/** @var Df_Megapolis_Model_Method $method  */
			$method =
				$this->createMethod (
					Df_Megapolis_Model_Method::getNameInMagentoFormat()
				)
			;

			df_assert ($method instanceof Df_Megapolis_Model_Method);


			if ($method->isApplicable()) {
				$method->getCost();
				$result []= $method;
			}

	
			df_assert_array ($result);
	
			$this->_methods = $result;
	
		}
	
	
		df_result_array ($this->_methods);
	
		return $this->_methods;
	
	}


	/**
	* @var array
	*/
	private $_methods;






	/**
	 * @return Df_Megapolis_Model_Api_Calculator
	 */
	private function getApi () {

		if (!isset ($this->_api)) {

			/** @var Df_Megapolis_Model_Api_Calculator $result  */
			$result =
				df_model (
					Df_Megapolis_Model_Api_Calculator::getNameInMagentoFormat()
					,
					array (
						Df_Megapolis_Model_Api_Calculator::PARAM__REQUEST =>
							$this->getRateRequest()

						,
						Df_Megapolis_Model_Api_Calculator::PARAM__RM_CONFIG => $this->getRmConfig()

					)
				)
			;


			df_assert ($result instanceof Df_Megapolis_Model_Api_Calculator);

			$this->_api = $result;

		}


		df_assert ($this->_api instanceof Df_Megapolis_Model_Api_Calculator);

		return $this->_api;

	}


	/**
	* @var Df_Megapolis_Model_Api_Calculator
	*/
	private $_api;






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Megapolis_Model_Rate_Collector';
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


