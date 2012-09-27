<?php

class Df_Spsr_Model_Api_Locator extends Df_Core_Model_Abstract {


	/**
	 * @return int
	 */
	public function getLocationId () {

		/** @var int $result  */
		$result =
			df_a (
				$this->getMapFromLocationNameToLocationId()
				,
				mb_strtoupper (
					$this->getLocationName()
				)
			)
		;

		if (is_null ($result)) {
			df_error (
				sprintf (
					"Служба доставки СПСР-Экспресс не знает населённого пункта «%s».
					\nМожет быть, название населённого пункта написано с ошибкой?"
					,
					$this->getLocationName()
				)
			);
		}


		$result = intval ($result);


		df_result_integer ($result);

		return $result;

	}
	
	
	
	
	/**
	 * @return array
	 */
	private function getMapFromLocationNameToLocationId () {
	
		if (!isset ($this->_mapFromLocationNameToLocationId)) {
	
			/** @var array $result  */
			$result = array ();

			foreach ($this->getRequest()->getResponseAsArray() as $location) {

				/** @var array $location */
				df_assert_array ($location);


				/** @var string $locationName  */
				$locationName = df_a ($location, 'value');

				df_assert_string ($locationName);


				$locationName =
					strtr (
						$locationName
						,
						array (
							' авт. округ' => ''
							,
							' край' => ''
							,
							' обл.' => ''
							,
							' респ.' => ''
						)
					)
				;


				$locationName =
					df_a (
						array (
							'Саха' => 'Саха (Якутия)'
							,
							'Северная Осетия' => 'Северная Осетия — Алания'
							,
							'Тыва' => 'Тыва (Тува)'
						)
						,
						$locationName
						,
						$locationName
					)
				;


				$locationName = mb_strtoupper ($locationName);


				/** @var int $locationId  */
				$locationId = intval (df_a ($location, 'id'));

				df_assert_integer ($locationId);


				$result [$locationName] = $locationId;

			}
	
			df_assert_array ($result);
	
			$this->_mapFromLocationNameToLocationId = $result;
	
		}
	
	
		df_result_array ($this->_mapFromLocationNameToLocationId);
	
		return $this->_mapFromLocationNameToLocationId;
	
	}
	
	
	/**
	* @var array
	*/
	private $_mapFromLocationNameToLocationId;	
	

	
	
	
	/**
	 * @return Df_Spsr_Model_Request_Locations
	 */
	private function getRequest () {

		if (!isset ($this->_request)) {

			/** @var Df_Spsr_Model_Request_Locations $result  */
			$result =
				df_model (
					Df_Spsr_Model_Request_Locations::getNameInMagentoFormat()
					,
					array (
						Df_Spsr_Model_Request_Locations
							::PARAM__LOCATION_NAME_PART => $this->getLocationName ()
					)
				)
			;


			df_assert ($result instanceof Df_Spsr_Model_Request_Locations);

			$this->_request = $result;

		}


		df_assert ($this->_request instanceof Df_Spsr_Model_Request_Locations);

		return $this->_request;

	}


	/**
	* @var Df_Spsr_Model_Request_Locations
	*/
	private $_request;


	


	/**
	 * @return string
	 */
	private function getLocationName () {
		return $this->cfg (self::PARAM__LOCATION_NAME);
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__LOCATION_NAME
				,
				new Df_Zf_Validate_String ()
			)
		;
	}




	const PARAM__LOCATION_NAME = 'location_name';






	/**
	 * @static
	 * @param string $locationName
	 * @return int
	 */
	public static function getLocationIdStatic ($locationName) {

		/** @var Df_Spsr_Model_Api_Locator $locator */
		$locator =
			df_model (
				self::getNameInMagentoFormat()
				,
				array (
					self::PARAM__LOCATION_NAME => $locationName
				)
			)
		;

		df_assert ($locator instanceof Df_Spsr_Model_Api_Locator);

		/** @var int $result */
		$result = $locator->getLocationId();

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Api_Locator';
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
