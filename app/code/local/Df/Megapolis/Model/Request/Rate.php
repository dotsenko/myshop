<?php

class Df_Megapolis_Model_Request_Rate extends Df_Shipping_Model_Request {


	/**
	 * @return float
	 */
	public function getResult () {
	
		if (!isset ($this->_result)) {

			$this->responseFailureDetect();

			/** @var string $responseAsJson  */
			$responseAsJson =
				strtr (
					$this->getResponseAsText()
					,
					array (
						'=>' => ':'
					)
				)
			;

			df_assert_string ($responseAsJson);


			/** @var array $responseAsArray  */
			$responseAsArray = json_decode ($responseAsJson, $isAssoc = true);

			df_assert_array ($responseAsArray);



			/** @var float $result  */
			$result = floatval (df_a ($responseAsArray, 'price'));

			df_assert_float ($result);

	
			$this->_result = $result;
	
		}
	
	
		df_result_float ($this->_result);
	
		return $this->_result;
	
	}
	
	
	/**
	* @var float
	*/
	private $_result;




	/**
	 * @override
	 * @return Df_Shipping_Model_Request
	 */
	protected function responseFailureDetect () {

		parent::responseFailureDetect();

		if (false !== mb_strpos ($this->getResponseAsText(), '{ERR}')) {

			/** @var string $errorMessage */
			$errorMessage = self::T__ERROR_MESSAGE__DEFAULT;


			/** @var string $pattern  */
			$pattern = '#описание ошибки: ([^\n]+)\n#u';

			/** @var array $matches */
			$matches = array ();

			if (1 === preg_match($pattern, $this->getResponseAsText(), $matches)) {

				$errorMessage = df_a ($matches, 1);

			}

			$this->error ($errorMessage);

		}

		return $this;

	}


	


	/**
	 * @override
	 * @return array
	 */
	protected function getHeaders () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getHeaders()
				,
				array (
					'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
					,
					'Accept-Encoding' => 'gzip, deflate'
					,
					'Accept-Language' => 'en-us,en;q=0.5'
					,
					'Connection' => 'keep-alive'
					,
					'Host' => 'www.megapolis-exp.ru'
					,
					'Referer' => 'http://www.megapolis-exp.ru/'
					,
					'User-Agent' => Df_Core_Const::FAKE_USER_AGENT
				)
			)
		;


		df_result_array ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getQueryHost () {
		return 'www.megapolis-exp.ru';
	}




	/**
	 * @override
	 * @return array
	 */
	protected function getQueryParams () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getQueryParams()
				,
				array (
					'get_calc' => true
					,
					'city_id' => $this->getLocationDestination()
					,
					'type_of_service_id' => 1
					,
					'weights_name' => $this->getCargoWeightCode ()
					,
					'declared_value' => $this->getCargoDeclaredValue()
				)
			)
		;

		df_result_array ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	protected function getQueryPath () {
		return '/api/';
	}




	/**
	 * @return float
	 */
	private function getCargoDeclaredValue () {
		return $this->cfg (self::PARAM__CARGO__DECLARED_VALUE);
	}




	/**
	 * @return float
	 */
	private function getCargoWeight () {
		return $this->cfg (self::PARAM__CARGO__WEIGHT);
	}

	
	
	/**
	 * @return string
	 */
	private function getCargoWeightCode () {
	
		if (!isset ($this->_cargoWeightCode)) {
	
			/** @var string $result  */
			$result = null;


			if (0.1 >= $this->getCargoWeight()) {
				$result = '0.0-0.1';
			}
			else if (0.5 >= $this->getCargoWeight()) {
				$result = '0.1-0.5';
			}
			else if (1.0 >= $this->getCargoWeight()) {
				$result = '0.5-1.0';
			}
			else if (1.5 >= $this->getCargoWeight()) {
				$result = '1.0-1.5';
			}
			else if (2.0 >= $this->getCargoWeight()) {
				$result = '1.5-2.0';
			}
			else {

				/** @var int $ceil  */
				$ceil = ceil ($this->getCargoWeight());

				df_assert_integer ($ceil);

				$result =
					implode (
						'-'
						,
						array (
							sprintf ('%.1f', $ceil - 1)
							,
							sprintf ('%.1f', $ceil)
						)
					)
				;
			}
	
	
			df_assert_string ($result);
	
			$this->_cargoWeightCode = $result;
	
		}
	
	
		df_result_string ($this->_cargoWeightCode);
	
		return $this->_cargoWeightCode;
	
	}
	
	
	/**
	* @var string
	*/
	private $_cargoWeightCode;	
	



	/**
	 * @return string
	 */
	private function getLocationDestination () {
		return $this->cfg (self::PARAM__LOCATION__DESTINATION);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__CARGO__DECLARED_VALUE, new Zend_Validate_Float())
			->addValidator (self::PARAM__CARGO__WEIGHT, new Zend_Validate_Float())
			->addValidator (self::PARAM__LOCATION__DESTINATION, new Df_Zf_Validate_Int())
		;
	}


	const PARAM__CARGO__DECLARED_VALUE = 'cargo__declared_value';
	const PARAM__CARGO__WEIGHT = 'cargo__weight';
	const PARAM__LOCATION__DESTINATION = 'location__destination';


	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Megapolis_Model_Request_Rate';
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


