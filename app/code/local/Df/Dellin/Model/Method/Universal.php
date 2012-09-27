<?php

class Df_Dellin_Model_Method_Universal extends Df_Dellin_Model_Method {

	/**
	 * @override
	 * @return bool
	 */
	public function isApplicable () {

		/** @var bool $result  */
		$result = parent::isApplicable ();

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
	 * @override
	 * @return Df_Dellin_Model_Api_Locator
	 */
	protected function getLocatorForRate () {

		if (!isset ($this->_locatorForRate)) {

			/** @var Df_Dellin_Model_Api_Locator_2 $result  */
			$result =
				df_model (
					Df_Dellin_Model_Api_Locator_2::getNameInMagentoFormat()
				)
			;


			df_assert ($result instanceof Df_Dellin_Model_Api_Locator_2);

			$this->_locatorForRate = $result;

		}


		df_assert ($this->_locatorForRate instanceof Df_Dellin_Model_Api_Locator_2);

		return $this->_locatorForRate;

	}


	/**
	* @var Df_Dellin_Model_Api_Locator
	*/
	private $_locatorForRate;




	
	/**
	 * @return Df_Dellin_Model_Request_Rate_Universal
	 */
	private function getApiRate () {
	
		if (!isset ($this->_apiRate)) {
	
			/** @var Df_Dellin_Model_Request_Rate_Universal $result  */
			$result = 
				df_model (
					Df_Dellin_Model_Request_Rate_Universal::getNameInMagentoFormat()
					,
					array (
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__LOCATION__DESTINATION =>
								$this->getLocationDestinationIdForRate()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__LOCATION__ORIGIN =>
								$this->getLocationOriginIdForRate()


						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__CARGO__DIMENSION__HEIGHT => $this->getCargoDimensionHeight()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__CARGO__DIMENSION__LENGTH => $this->getCargoDimensionLength()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__CARGO__DIMENSION__WIDTH => $this->getCargoDimensionWidth()


						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__CARGO__VOLUME => $this->getRequest()->getVolumeInCubicMetres()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__CARGO__WEIGHT => $this->getRequest()->getWeightInKilogrammes()


						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_GET_CARGO_FROM_THE_SHOP_STORE =>
									$this->getRmConfig()->service()->needGetCargoFromTheShopStore()
								||
									!$this->hasLocationOriginTerminal()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_DELIVER_CARGO_TO_THE_BUYER_HOME =>
									$this->getRmConfig()->service()->needDeliverCargoToTheBuyerHome()
								||
									!$this->hasLocationDestinationTerminal()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_INSURANCE =>
									0
								!==
									intval (
										$this->getRmConfig()->admin()->getDeclaredValuePercent()
									)

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__CARGO__DECLARED_VALUE =>
									$this->getRequest()->getPackageValue()
								*
									$this->getRmConfig()->admin()->getDeclaredValuePercent()
								/
									100


						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_ADDITIONAL_PACKING =>
								$this->getConfigService()->needAdditionalPacking()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_BAG_PACKING =>
								$this->getConfigService()->needBagPacking()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_CARGO_TAIL_LOADER_AT_ORIGIN =>
								$this->getConfigService()->needCargoTailLoaderAtOrigin()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_COLLAPSIBLE_PALLET_BOX_AT_ORIGIN =>
								$this->getConfigService()->needCollapsiblePalletBoxAtOrigin()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_MANIPULATOR_AT_DESTINATION =>
								$this->getConfigService()->needManipulatorAtDestination()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_OPEN_CAR_AT_DESTINATION =>
								$this->getConfigService()->needOpenCarAtDestination()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_OPEN_CAR_AT_ORIGIN =>
								$this->getConfigService()->needOpenCarAtOrigin()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_REMOVE_AWNING_AT_ORIGIN =>
								$this->getConfigService()->needRemoveAwningAtOrigin()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_RIGID_CONTAINER =>
								$this->getConfigService()->needRigidContainer()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_SIDE_CASTING_AT_DESTINATION =>
								$this->getConfigService()->needSideCastingAtDestination()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_SIDE_CASTING_AT_ORIGIN =>
								$this->getConfigService()->needSideCastingAtOrigin()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_SOFT_PACKING =>
								$this->getConfigService()->needSoftPacking()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_TOP_CASTING_AT_ORIGIN =>
								$this->getConfigService()->needTopCastingAtOrigin()

						,
						Df_Dellin_Model_Request_Rate_Universal
							::PARAM__NEED_TOP_CASTING_AT_DESTINATION =>
								$this->getConfigService()->needTopCastingAtDestination()

					)
				)
			;
	
	
			df_assert ($result instanceof Df_Dellin_Model_Request_Rate_Universal);
	
			$this->_apiRate = $result;
	
		}
	
	
		df_assert ($this->_apiRate instanceof Df_Dellin_Model_Request_Rate_Universal);
	
		return $this->_apiRate;
	
	}
	
	
	/**
	* @var Df_Dellin_Model_Request_Rate_Universal
	*/
	private $_apiRate;




	const METHOD = 'universal';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Method_Universal';
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


