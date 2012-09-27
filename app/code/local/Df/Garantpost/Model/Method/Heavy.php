<?php

abstract class Df_Garantpost_Model_Method_Heavy extends Df_Garantpost_Model_Method {

	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getLocationDestinationSuffix ();






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
					===
						$this->getRequest()->getDestinationCountryId()
				)
			&&
				(31.5 < $this->getRequest()->getWeightInKilogrammes())
			&&
				(80 >= $this->getRequest()->getWeightInKilogrammes())
			&&
				(0 < $this->getCostInRoubles())
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
	 * @return Df_Garantpost_Model_Request_Rate_Heavy
	 */
	private function getApiRate () {

		if (!isset ($this->_apiRate)) {

			/** @var Df_Garantpost_Model_Request_Rate_Heavy $result  */
			$result =
				df_model (
					Df_Garantpost_Model_Request_Rate_Heavy::getNameInMagentoFormat()
					,
					array (
						Df_Garantpost_Model_Request_Rate_Heavy::PARAM__WEIGHT =>
							$this->getRequest()->getWeightInKilogrammes()
						,
						Df_Garantpost_Model_Request_Rate_Heavy::PARAM__SERVICE =>
							$this->getServiceCode ()
						,
						Df_Garantpost_Model_Request_Rate_Heavy::PARAM__LOCATION_ORIGIN_ID =>
							$this->getLocationOriginId ()
						,
						Df_Garantpost_Model_Request_Rate_Heavy
							::PARAM__LOCATION_DESTINATION_NAME =>
								$this->getLocationDestinationName ()
					)
				)
			;


			df_assert ($result instanceof Df_Garantpost_Model_Request_Rate_Heavy);

			$this->_apiRate = $result;

		}


		df_assert ($this->_apiRate instanceof Df_Garantpost_Model_Request_Rate_Heavy);

		return $this->_apiRate;

	}


	/**
	* @var Df_Garantpost_Model_Request_Rate_Heavy
	*/
	private $_apiRate;




	/**
	 * @return string
	 */
	private function getLocationDestinationName () {


		if (df_empty ($this->getRequest()->getDestinationCity())) {
			df_error ('Укажите город');
		}


		/** @var string $result  */
		$result =
			implode (
				Df_Core_Const::T_SPACE
				,
				array (
					$this->getRequest()->getDestinationCity()
					,
					$this->getLocationDestinationSuffix()
				)
			)
		;


		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string|null
	 */
	private function getLocationOriginId () {

		/** @var string|null $result  */
		$result = null;


		if ($this->isDeliveryFromMoscow()) {
			$result = 'msk';
		}
		else {
			if (
				df_strings_are_equal_ci (
					'Московская'
					,
					$this->getRequest ()->getOriginRegionName()
				)
			) {
				$result = 'obl';
			}
		}


		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}






	/**
	 * @return string
	 */
	private function getServiceCode () {

		/** @var array $states */
		$states =
			array (
				false => 'term'
				,
				true => 'door'
			)
		;

		/** @var string $result  */
		$result =
			implode (
				'-'
				,
				array (
					df_a (
						$states
						,
						$this->getRmConfig()->service()->needDeliverCargoToTheBuyerHome()
					)
					,
					df_a (
						$states
						,
						$this->getRmConfig()->service()->needGetCargoFromTheShopStore()
					)
				)
			)
		;

		df_result_string ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Method_Heavy';
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


