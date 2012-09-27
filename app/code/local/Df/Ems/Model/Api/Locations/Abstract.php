<?php

abstract class Df_Ems_Model_Api_Locations_Abstract extends Df_Core_Model_Abstract {



	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getLocationType ();

	


	/**
	 * @return array
	 */
	public function getMapFromLocationNameToEmsLocationCode () {
	
		if (!isset ($this->_mapFromLocationNameToEmsLocationCode)) {
	
			/** @var array $result  */
			$result = array ();

			foreach ($this->getLocationsAsRawArray() as $location) {

				/** @var array $location */
				df_assert_array ($location);


				/** @var string $locationName  */
				$locationName = mb_strtoupper (df_a ($location, 'name'));

				df_assert_string ($locationName);


				/** @var string $locationCode  */
				$locationCode = df_a ($location, 'value');

				df_assert_string ($locationCode);


				$result [$locationName] = $locationCode;

			}
	
			df_assert_array ($result);
	
			$this->_mapFromLocationNameToEmsLocationCode = $result;
	
		}
	
	
		df_result_array ($this->_mapFromLocationNameToEmsLocationCode);
	
		return $this->_mapFromLocationNameToEmsLocationCode;
	
	}
	
	
	/**
	* @var array
	*/
	private $_mapFromLocationNameToEmsLocationCode;	





	/**
	 * @return Mage_Core_Model_Cache
	 */
	protected function getCache () {

		/** @var Mage_Core_Model_Cache $result  */
		$result = Mage::app()->getCacheInstance();

		df_assert ($result instanceof Mage_Core_Model_Cache);

		return $result;

	}




	/**
	 * @return array
	 */
	protected function getLocationsAsRawArray () {

		if (!isset ($this->_locationsAsRawArray)) {

			/** @var array $result  */
			$result = $this->getRequest()->getResponseParam ('locations');

			df_assert_array ($result);

			$this->_locationsAsRawArray = $result;

		}


		df_result_array ($this->_locationsAsRawArray);

		return $this->_locationsAsRawArray;

	}


	/**
	* @var array
	*/
	private $_locationsAsRawArray;



	
	
	
	/**
	 * @return Df_Ems_Model_Request
	 */
	private function getRequest () {

		if (!isset ($this->_request)) {

			/** @var Df_Ems_Model_Request $result  */
			$result =
				df_model (
					Df_Ems_Model_Request::getNameInMagentoFormat()
					,
					array (
						Df_Ems_Model_Request::PARAM__QUERY_PARAMS =>
							array (
								'method' => 'ems.get.locations'
								,
								'type' => $this->getLocationType()
								,
								'plain' => 'true'
							)
					)
				)
			;


			df_assert ($result instanceof Df_Ems_Model_Request);

			$this->_request = $result;

		}


		df_assert ($this->_request instanceof Df_Ems_Model_Request);

		return $this->_request;

	}


	/**
	* @var Df_Ems_Model_Request
	*/
	private $_request;


	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Ems_Model_Api_Locations_Abstract';
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


