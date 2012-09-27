<?php

class Df_Garantpost_Model_Method_Export extends Df_Garantpost_Model_Method {


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
	public function getMethodTitle () {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;


		if (!is_null ($this->getRequest())) {

			/** @var string|null $forCapital  */
			$forCapital =
					(0 === $this->getApiDeliveryTime()->getCapitalMin())
				?
					null
				:
					sprintf (
						'столица: %s'
						,
						$this->formatTimeOfDelivery (
							$this->getApiDeliveryTime()->getCapitalMin()
							,
							$this->getApiDeliveryTime()->getCapitalMax()
						)
					)
			;


			/** @var string|null $forNonCapital  */
			$forNonCapital =
					(0 === $this->getApiDeliveryTime()->getNonCapitalMin())
				?
					null
				:
					sprintf (
						is_null ($forCapital) ? '%s' : 'другие города: %s'
						,
						$this->formatTimeOfDelivery (
							$this->getApiDeliveryTime()->getNonCapitalMin()
							,
							$this->getApiDeliveryTime()->getNonCapitalMax()
						)
					)
			;


			$result =
				implode (
					' '
					,
					df_clean (
						array (
							$forCapital
							,
							$forNonCapital
						)
					)
				)
			;

		}


		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return bool
	 */
	public function isApplicable () {


		/** @var bool $result  */
		$result =
				parent::isApplicable ()
			&&
				(
						Df_Directory_Helper_Country::ISO_2_CODE__RUSSIA
					!==
						$this->getRequest()->getDestinationCountryId()
				)
			&&
				!is_null (
					df_a (
						Df_Garantpost_Model_Request_Countries_ForRate::i()->getResponseAsArray()
						,
						$this->getRequest()->getDestinationCountryId()
					)
				)
			&&
				(32 >= $this->getRequest()->getWeightInKilogrammes())
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @override
	 * @return int
	 */
	protected function getCostInRoubles () {

		/** @var int $result  */
		$result = intval ($this->getApiRate()->getResult());

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return Df_Garantpost_Model_Request_DeliveryTime_Export
	 */
	private function getApiDeliveryTime () {

		if (!isset ($this->_apiDeliveryTime)) {

			/** @var Df_Garantpost_Model_Request_DeliveryTime_Export $result  */
			$result =
				df_model (
					Df_Garantpost_Model_Request_DeliveryTime_Export::getNameInMagentoFormat()
					,
					array (
						Df_Garantpost_Model_Request_DeliveryTime_Export
							::PARAM__DESTINATION_COUNTRY_ISO2 =>
								$this->getRequest()->getDestinationCountryId()
					)
				)
			;


			df_assert ($result instanceof Df_Garantpost_Model_Request_DeliveryTime_Export);

			$this->_apiDeliveryTime = $result;

		}


		df_assert ($this->_apiDeliveryTime instanceof Df_Garantpost_Model_Request_DeliveryTime_Export);

		return $this->_apiDeliveryTime;

	}


	/**
	* @var Df_Garantpost_Model_Request_DeliveryTime_Export
	*/
	private $_apiDeliveryTime;








	/**
	 * @return Df_Garantpost_Model_Request_Rate_Export
	 */
	private function getApiRate () {

		if (!isset ($this->_apiRate)) {

			/** @var Df_Garantpost_Model_Request_Rate_Export $result  */
			$result =
				df_model (
					Df_Garantpost_Model_Request_Rate_Export::getNameInMagentoFormat()
					,
					array (
						Df_Garantpost_Model_Request_Rate_Export::PARAM__WEIGHT =>
							$this->getRequest()->getWeightInKilogrammes()
						,
						Df_Garantpost_Model_Request_Rate_Export
							::PARAM__DESTINATION_COUNTRY_ISO2 =>
								$this->getRequest()->getDestinationCountryId()
					)
				)
			;


			df_assert ($result instanceof Df_Garantpost_Model_Request_Rate_Export);

			$this->_apiRate = $result;

		}


		df_assert ($this->_apiRate instanceof Df_Garantpost_Model_Request_Rate_Export);

		return $this->_apiRate;

	}


	/**
	* @var Df_Garantpost_Model_Request_Rate_Export
	*/
	private $_apiRate;





	const METHOD = 'export';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Method_Export';
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


