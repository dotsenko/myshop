<?php

class Df_Spsr_Model_Api_Calculator extends Df_Core_Model_Abstract {



	/**
	 * @return array
	 */
	public function getRates () {

		/** @var array $result  */
		$result = $this->getRateRequest()->getRates();

		df_result_array ($result);

		return $result;

	}



	/**
	 * @return float
	 */
	private function getDeclaredValue () {
		return $this->cfg (self::PARAM__DECLARED_VALUE);
	}
	
	
	
	
	/**
	 * @return int
	 */
	private function getLocationDestinationId () {

		if (!isset ($this->_locationDestinationId)) {

			/** @var Df_Spsr_Model_Converter_Location_ToServiceFormat $converter  */
			$converter =
				df_model (
					Df_Spsr_Model_Converter_Location_ToServiceFormat::getNameInMagentoFormat()
					,
					array (
						Df_Spsr_Model_Converter_Location_ToServiceFormat
							::PARAM__CITY => $this->getRequest()->getDestinationCity()

						,
						Df_Spsr_Model_Converter_Location_ToServiceFormat
							::PARAM__COUNTRY_ID => $this->getRequest()->getDestinationCountryId()

						,
						Df_Spsr_Model_Converter_Location_ToServiceFormat
							::PARAM__REGION_ID => $this->getRequest()->getDestinationRegionId()
					)
				)
			;

			df_assert ($converter instanceof Df_Spsr_Model_Converter_Location_ToServiceFormat);


			/** @var int $result  */
			$result =
				intval (
					$converter->getResult()
				)
			;


			df_assert_integer ($result);

			$this->_locationDestinationId = $result;

		}


		df_result_integer ($this->_locationDestinationId);

		return $this->_locationDestinationId;

	}


	/**
	* @var int
	*/
	private $_locationDestinationId;	
	
	



	/**
	 * @return int
	 */
	private function getLocationOriginId () {

		if (!isset ($this->_locationOriginId)) {

			/** @var Df_Spsr_Model_Converter_Location_ToServiceFormat $converter  */
			$converter =
				df_model (
					Df_Spsr_Model_Converter_Location_ToServiceFormat::getNameInMagentoFormat()
					,
					array (
						Df_Spsr_Model_Converter_Location_ToServiceFormat
							::PARAM__CITY => $this->getRequest()->getOriginCity()

						,
						Df_Spsr_Model_Converter_Location_ToServiceFormat
							::PARAM__COUNTRY_ID => $this->getRequest()->getOriginCountryId()

						,
						Df_Spsr_Model_Converter_Location_ToServiceFormat
							::PARAM__REGION_ID => $this->getRequest()->getOriginRegionId()
					)
				)
			;

			df_assert ($converter instanceof Df_Spsr_Model_Converter_Location_ToServiceFormat);


			/** @var int $result  */
			$result =
				intval (
					$converter->getResult()
				)
			;


			df_assert_integer ($result);

			$this->_locationOriginId = $result;

		}


		df_result_integer ($this->_locationOriginId);

		return $this->_locationOriginId;

	}


	/**
	* @var int
	*/
	private $_locationOriginId;





	/**
	 * @return Df_Spsr_Model_Request_Rate
	 */
	private function getRateRequest () {

		if (!isset ($this->_rateRequest)) {

			/** @var Df_Spsr_Model_Request_Rate $result  */
			$result =
				df_model (
					Df_Spsr_Model_Request_Rate::getNameInMagentoFormat()
					,
					array (
						Df_Spsr_Model_Request_Rate::PARAM__POST_PARAMS =>
							array (
								Df_Spsr_Model_Request_Rate::POST__DECLARED_VALUE =>
									$this->getDeclaredValue ()

								,
								Df_Spsr_Model_Request_Rate::POST__LOCATION__SOURCE =>
									$this->getLocationOriginId()

								,
								Df_Spsr_Model_Request_Rate::POST__LOCATION__DESTINATION =>
									$this->getLocationDestinationId()

								,
								Df_Spsr_Model_Request_Rate::POST__ENDORSE_DELIVERY_TIME =>
									intval (
										$this->getRmConfig()->service()->endorseDeliveryTime()
									)

								,
								Df_Spsr_Model_Request_Rate::POST__ENABLE_SMS_NOTIFICATION =>
									intval (
										$this->getRmConfig()->service()->enableSmsNotification()
									)


								,
								Df_Spsr_Model_Request_Rate::POST__INSURANCE_TYPE =>
										Df_Spsr_Model_Config_Source_Insurer::OPTION_VALUE__CARRIER
									===
										$this->getRmConfig()->service()->getInsurer()
									?
										0
									:
										1


								,
								Df_Spsr_Model_Request_Rate::POST__WEIGHT =>
									$this->getRequest()->getWeightInKilogrammes()

								,
								Df_Spsr_Model_Request_Rate::POST__PERSONAL_HANDING =>
									intval (
										$this->getRmConfig()->service()->needPersonalHanding()
									)
							)
					)
				)
			;


			df_assert ($result instanceof Df_Spsr_Model_Request_Rate);

			$this->_rateRequest = $result;

		}


		df_assert ($this->_rateRequest instanceof Df_Spsr_Model_Request_Rate);

		return $this->_rateRequest;

	}


	/**
	* @var Df_Spsr_Model_Request_Rate
	*/
	private $_rateRequest;




	/**
	 * @return Df_Shipping_Model_Rate_Request
	 */
	private function getRequest () {
		return $this->cfg (self::PARAM__REQUEST);
	}



	/**
	 * @return Df_Spsr_Model_Config_Facade
	 */
	private function getRmConfig () {
		return $this->cfg (self::PARAM__RM_CONFIG);
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__REQUEST, Df_Shipping_Model_Rate_Request::getClass ()
			)
			->validateClass (
				self::PARAM__RM_CONFIG, Df_Spsr_Model_Config_Facade::getClass ()
			)
			->addValidator (
				self::PARAM__DECLARED_VALUE, new Zend_Validate_Float ()
			)
		;
	}




	const PARAM__DECLARED_VALUE = 'declared_value';
	const PARAM__REQUEST = 'request';
	const PARAM__RM_CONFIG = 'rm_config';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Api_Calculator';
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
