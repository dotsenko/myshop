<?php

abstract class Df_PonyExpress_Model_Method extends Df_Shipping_Model_Method {


	/**
	 * @abstract
	 * @return bool
	 */
	abstract public function isApplicable ();



	/**
	 * @abstract
	 * @return int
	 */
	abstract protected function getTimeOfDeliveryMax ();


	/**
	 * @abstract
	 * @return int
	 */
	abstract protected function getTimeOfDeliveryMin ();



	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getTitleBase ();




	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getTitleForRequest ();




	/**          
	 * @override
	 * @return float
	 */
	public function getCost () {

		if (!isset ($this->_cost)) {

			/** @var float $result  */
			$result =
				df_helper()->directory()->currency()->convertFromRoublesToBase (
					$this->getCostInRoubles ()
				)
			;

			df_assert_float ($result);

			$this->_cost = $result;

		}


		df_result_float ($this->_cost);

		return $this->_cost;

	}


	/**
	* @var float
	*/
	private $_cost;
	
	



	/**
	 * @override
	 * @return string
	 */
	public function getMethodTitle () {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;

		if (!is_null ($this->getRequest())) {
			$result =
				sprintf (
					'%s: %s'
					,
					$this->getTitleBase()
					,
					$this->formatTimeOfDelivery (
						$this->getTimeOfDeliveryMin()
						,
						$this->getTimeOfDeliveryMax()
					)
				)
			;
		}

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Df_PonyExpress_Model_Request_Rate
	 */
	private function getApi () {

		if (!isset ($this->_api)) {

			/** @var Df_PonyExpress_Model_Request_Rate $result  */
			$result =
				df_model (
					Df_PonyExpress_Model_Request_Rate::getNameInMagentoFormat()
					,
					array (
						Df_PonyExpress_Model_Request_Rate::PARAM__POST_PARAMS =>
							array (
								Df_PonyExpress_Model_Request_Rate
									::POST__LOCATION__DESTINATION => $this->getLocationDestination()
								,
								Df_PonyExpress_Model_Request_Rate
									::POST__LOCATION__SOURCE => $this->getLocationOrigin()
								,
								Df_PonyExpress_Model_Request_Rate
									::POST__METHOD => $this->getTitleForRequest ()
								,
								Df_PonyExpress_Model_Request_Rate
									::POST__WEIGHT => $this->getRequest()->getWeightInKilogrammes()
							)
					)
				)
			;


			df_assert ($result instanceof Df_PonyExpress_Model_Request_Rate);

			$this->_api = $result;

		}


		df_assert ($this->_api instanceof Df_PonyExpress_Model_Request_Rate);

		return $this->_api;

	}


	/**
	* @var Df_PonyExpress_Model_Request_Rate
	*/
	private $_api;





	/**
	 * @return float
	 */
	private function getCostInRoubles () {

		/** @var float $result */
		$result = $this->getApi()->getResult();

		df_result_float ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getLocationDestination () {

		if (!isset ($this->_locationDestination)) {


			/** @var Df_PonyExpress_Model_Converter_Location_ToServiceFormat $converter  */
			$converter =
				df_model (
					Df_PonyExpress_Model_Converter_Location_ToServiceFormat::getNameInMagentoFormat()
					,
					array (
						Df_PonyExpress_Model_Converter_Location_ToServiceFormat
							::PARAM__CITY => $this->getRequest()->getDestinationCity()
						,
						Df_PonyExpress_Model_Converter_Location_ToServiceFormat
							::PARAM__REGION_ID => $this->getRequest()->getDestinationRegionId()
						,
						Df_PonyExpress_Model_Converter_Location_ToServiceFormat
							::PARAM__COUNTRY_ID => $this->getRequest()->getDestinationCountryId()
					)
				)
			;

			df_assert ($converter instanceof Df_PonyExpress_Model_Converter_Location_ToServiceFormat);


			/** @var string $result  */
			$result = $converter->getResult();


			df_assert_string ($result);

			$this->_locationDestination = $result;

		}


		df_result_string ($this->_locationDestination);

		return $this->_locationDestination;

	}


	/**
	* @var string
	*/
	private $_locationDestination;






	/**
	 * @return string
	 */
	private function getLocationOrigin () {
	
		if (!isset ($this->_locationOrigin)) {
	
			/** @var Df_PonyExpress_Model_Converter_Location_ToServiceFormat $converter  */
			$converter =
				df_model (
					Df_PonyExpress_Model_Converter_Location_ToServiceFormat::getNameInMagentoFormat()
					,
					array (
						Df_PonyExpress_Model_Converter_Location_ToServiceFormat
							::PARAM__CITY => $this->getRequest()->getOriginCity()
						,
						Df_PonyExpress_Model_Converter_Location_ToServiceFormat
							::PARAM__REGION_ID => $this->getRequest()->getOriginRegionId()
						,
						Df_PonyExpress_Model_Converter_Location_ToServiceFormat
							::PARAM__COUNTRY_ID => $this->getRequest()->getOriginCountryId()
					)
				)
			;

			df_assert ($converter instanceof Df_PonyExpress_Model_Converter_Location_ToServiceFormat);


			/** @var string $result  */
			$result = $converter->getResult();
	
	
			df_assert_string ($result);
	
			$this->_locationOrigin = $result;
	
		}
	
	
		df_result_string ($this->_locationOrigin);
	
		return $this->_locationOrigin;
	
	}
	
	
	/**
	* @var string
	*/
	private $_locationOrigin;

	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PonyExpress_Model_Method';
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


