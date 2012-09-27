<?php

class Df_Dellin_Model_Method_Mini extends Df_Dellin_Model_Method {

	/**
	 * @override
	 * @return bool
	 */
	public function isApplicable () {

		/** @var bool $result  */
		$result =
				parent::isApplicable ()
			&&
				$this->hasLocationDestinationTerminal()
			&&
				$this->hasLocationOriginTerminal()
		    &&
				(3 >= $this->getRequest()->getWeightInKilogrammes())
			&&
				(
						0.36
					>=
						max (
							$this->getCargoDimensions()
						)
				)
			&&
				(
						0.65
					>=
						array_sum (
							$this->getCargoDimensions()
						)
				)
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
	 * @return int
	 */
	protected function getCostInRoubles () {

		/** @var int $result  */
		$result = intval ($this->getApiRate()->getResult());

		df_result_integer ($result);

		return $result;

	}


	
	
	/**
	 * @return Df_Dellin_Model_Request_Rate_Mini
	 */
	private function getApiRate () {
	
		if (!isset ($this->_apiRate)) {
	
			/** @var Df_Dellin_Model_Request_Rate_Mini $result  */
			$result = 
				df_model (
					Df_Dellin_Model_Request_Rate_Mini::getNameInMagentoFormat()
					,
					array (
						Df_Dellin_Model_Request_Rate_Mini
							::PARAM__LOCATION__DESTINATION =>
								$this->getLocationDestinationIdForRate()

						,
						Df_Dellin_Model_Request_Rate_Mini
							::PARAM__LOCATION__ORIGIN =>
								$this->getLocationOriginIdForRate()


						,
						Df_Dellin_Model_Request_Rate_Mini
							::PARAM__CARGO__WEIGHT => $this->getRequest()->getWeightInKilogrammes()


						,
						Df_Dellin_Model_Request_Rate_Mini
							::PARAM__NEED_INSURANCE =>
									0
								!==
									intval (
										$this->getRmConfig()->admin()->getDeclaredValuePercent()
									)

						,
						Df_Dellin_Model_Request_Rate_Mini
							::PARAM__CARGO__DECLARED_VALUE =>
									$this->getRequest()->getPackageValue()
								*
									$this->getRmConfig()->admin()->getDeclaredValuePercent()
								/
									100


						,
						Df_Dellin_Model_Request_Rate_Mini
							::PARAM__NEED_ADDITIONAL_PACKING =>
								$this->getConfigService()->needAdditionalPacking()

						,
						Df_Dellin_Model_Request_Rate_Mini
							::PARAM__CARGO__IS_LETTER => $this->isCargoLetter()

					)
				)
			;
	
	
			df_assert ($result instanceof Df_Dellin_Model_Request_Rate_Mini);
	
			$this->_apiRate = $result;
	
		}
	
	
		df_assert ($this->_apiRate instanceof Df_Dellin_Model_Request_Rate_Mini);
	
		return $this->_apiRate;
	
	}
	
	
	/**
	* @var Df_Dellin_Model_Request_Rate_Mini
	*/
	private $_apiRate;

	
	
	
	/**
	 * @return float
	 */
	private function getSecondDimension () {
	
		if (!isset ($this->_secondDimension)) {

			/** @var array $dimensions  */
			$dimensions = array_values ($this->getCargoDimensions());

			df_assert_array ($dimensions);


			sort ($dimensions);

	
			/** @var float $result  */
			$result = df_a ($dimensions, 1);
	
	
			df_assert_float ($result);
	
			$this->_secondDimension = $result;
	
		}
	
	
		df_result_float ($this->_secondDimension);
	
		return $this->_secondDimension;
	
	}
	
	
	/**
	* @var float
	*/
	private $_secondDimension;	
	
	
	
	
	
	/**
	 * @return bool
	 */
	private function isCargoLetter () {
	
		if (!isset ($this->_cargoLetter)) {
	
			/** @var bool $result  */
			$result =
					(0.5 >= $this->getRequest()->getWeightInKilogrammes())
				&&
					(
							0.3
						>=
							max (
								$this->getCargoDimensions()
							)
					)
				&&
					(
							0.21
						>=
							$this->getSecondDimension()
					)
			;
	
	
			df_assert_boolean ($result);
	
			$this->_cargoLetter = $result;
	
		}
	
	
		df_result_boolean ($this->_cargoLetter);
	
		return $this->_cargoLetter;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_cargoLetter;		



	const METHOD = 'mini';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Method_Mini';
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


