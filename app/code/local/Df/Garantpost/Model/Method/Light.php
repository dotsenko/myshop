<?php

abstract class Df_Garantpost_Model_Method_Light extends Df_Garantpost_Model_Method {

	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getServiceCode ();




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
				(31.5 >= $this->getRequest()->getWeightInKilogrammes())
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
	 * @return int
	 */
	protected function getTimeOfDeliveryMax () {

		/** @var int $result  */
		$result = parent::getTimeOfDeliveryMax();

		/**
		 * Пока сайт Гарантпоста способен рассчитывать сроки доставки
		 * только при отправке из Москвы
		 */
		if ($this->isDeliveryFromMoscow ()) {
			$result = $this->getApiDeliveryTime()->getMax();
		}

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	protected function getTimeOfDeliveryMin () {

		/** @var int $result  */
		$result = parent::getTimeOfDeliveryMin();

		/**
		 * Пока сайт Гарантпоста способен рассчитывать сроки доставки
		 * только при отправке из Москвы
		 */
		if ($this->isDeliveryFromMoscow ()) {
			$result = $this->getApiDeliveryTime()->getMin();
		}

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return Df_Garantpost_Model_Request_DeliveryTime_Light
	 */
	private function getApiDeliveryTime () {

		if (!isset ($this->_apiDeliveryTime)) {

			/** @var Df_Garantpost_Model_Request_DeliveryTime_Light $result  */
			$result =
				df_model (
					Df_Garantpost_Model_Request_DeliveryTime_Light::getNameInMagentoFormat()
					,
					array (
						Df_Garantpost_Model_Request_DeliveryTime_Light
							::PARAM__LOCATION_DESTINATION_ID =>
								$this->getLocationDestinationId ($forRate = false)
					)
				)
			;


			df_assert ($result instanceof Df_Garantpost_Model_Request_DeliveryTime_Light);

			$this->_apiDeliveryTime = $result;

		}


		df_assert ($this->_apiDeliveryTime instanceof Df_Garantpost_Model_Request_DeliveryTime_Light);

		return $this->_apiDeliveryTime;

	}


	/**
	* @var Df_Garantpost_Model_Request_DeliveryTime_Light
	*/
	private $_apiDeliveryTime;



	
	
	/**
	 * @return Df_Garantpost_Model_Request_Rate_Light
	 */
	private function getApiRate () {
	
		if (!isset ($this->_apiRate)) {
	
			/** @var Df_Garantpost_Model_Request_Rate_Light $result  */
			$result = 
				df_model (
					Df_Garantpost_Model_Request_Rate_Light::getNameInMagentoFormat()
					,
					array (
						Df_Garantpost_Model_Request_Rate_Light::PARAM__WEIGHT =>
							$this->getRequest()->getWeightInKilogrammes()
						,
						Df_Garantpost_Model_Request_Rate_Light::PARAM__SERVICE =>
							$this->getServiceCode ()
						,
						Df_Garantpost_Model_Request_Rate_Light::PARAM__LOCATION_ORIGIN_ID =>
							$this->getLocationOriginId ($forRate = true)
						,
						Df_Garantpost_Model_Request_Rate_Light::PARAM__LOCATION_DESTINATION_ID =>
							$this->getLocationDestinationId ($forRate = true)
					)
				)
			;
	
	
			df_assert ($result instanceof Df_Garantpost_Model_Request_Rate_Light);
	
			$this->_apiRate = $result;
	
		}
	
	
		df_assert ($this->_apiRate instanceof Df_Garantpost_Model_Request_Rate_Light);
	
		return $this->_apiRate;
	
	}
	
	
	/**
	* @var Df_Garantpost_Model_Request_Rate_Light
	*/
	private $_apiRate;




	/**
	 * @param bool $forRate
	 * @return int
	 */
	private function getLocationDestinationId ($forRate) {

		df_param_boolean ($forRate, 0);


		/** @var int $result  */
		$result =
			$this->getLocationId (
				$this->getRequest()->getDestinationCity()
				,
				$this->getRequest()->getDestinationRegionId()
				,
				$isOrigin = false
				,
				$forRate
			)
		;

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @param string|null $city
	 * @param int $regionId
	 * @param bool $isOrigin
	 * @param bool $forRate
	 * @return int
	 */
	private function getLocationId ($city, $regionId, $isOrigin, $forRate) {

		if (!is_null ($city)) {
			df_param_string ($city, 0);
		}

		$regionId = intval ($regionId);

		df_param_integer ($regionId, 1);

		df_param_boolean ($isOrigin, 2);

		df_param_boolean ($forRate, 3);


		/** @var array $map  */
		$map =
				$forRate
			?
				Df_Garantpost_Model_Request_Locations_Internal_ForRate::i()
					->getResponseAsArray()
			:
				Df_Garantpost_Model_Request_Locations_Internal_ForDeliveryTime::i()
					->getResponseAsArray()
		;

		df_assert_array (
			$map
		);


		if (is_null ($city) && (0 === $regionId)) {
			df_error (
					$isOrigin
				?
					'Администратор должен указать город склада магазина'
				:
					'Укажите город или хотя бы область'
			);
		}


		/** @var int|null $result  */
		$result = null;


		if (!is_null ($city)) {
			$result =
				df_a (
					$map
					,
					mb_strtoupper ($city)
				)
			;
		}


		/** @var string|null $regionName  */
		$regionName =
				(0 !== $regionId)
			?
				df_helper()->directory()->getRegionNameById ($regionId)
			:
				null
		;


		if (is_null ($result)) {
			if (0 !== $regionId) {
				$result =
					df_a (
						$map
						,
						mb_strtoupper ($regionName)
					)
				;
			}
		}

		if (is_null ($result)) {


			if (is_null ($city)) {
				df_error ('Укажите город');
			}
			else {

				/** @var string $from  */
				$from =
						!is_null ($city)
					?
						'из населённого пункта'
					:
						'из субъекта федерации'
				;

				/** @var string $to  */
				$to =
						!is_null ($city)
					?
						'в населённый пункт'
					:
						'в субъект федерации'
				;


				/** @var string $location  */
				$location =
						!is_null ($city)
					?
						$city
					:
						df_helper()->directory()->getRegionFullNameById (
							$regionId
						)
				;

				df_assert_string ($location);


				df_error (
					sprintf (
						'Служба Гарантпост не отправляет грузы %s %s.'
						,
						$isOrigin ? $from : $to
						,
						$location
					)
				);

			}

		}


		df_result_integer ($result);

		return $result;

	}





	/**
	 * @param bool $forRate
	 * @return int
	 */
	private function getLocationOriginId ($forRate) {

		df_param_boolean ($forRate, 0);

		/** @var int $result  */
		$result =
			$this->getLocationId (
				$this->getRequest()->getOriginCity()
				,
				$this->getRequest()->getOriginRegionId()
				,
				$isOrigin = true
				,
				$forRate
			)
		;


		df_result_integer ($result);

		return $result;

	}

	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Method_Light';
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


