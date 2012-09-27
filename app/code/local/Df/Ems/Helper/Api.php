<?php


class Df_Ems_Helper_Api extends Mage_Core_Helper_Abstract {
	
	
	
	
	/**
	 * @return Df_Ems_Model_Api_Locations_Cities
	 */
	public function cities () {

		if (!isset ($this->_cities)) {

			/** @var Df_Ems_Model_Api_Locations_Cities $result  */
			$result =
				df_model (
					Df_Ems_Model_Api_Locations_Cities::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Ems_Model_Api_Locations_Cities);

			$this->_cities = $result;

		}


		df_assert ($this->_cities instanceof Df_Ems_Model_Api_Locations_Cities);

		return $this->_cities;

	}


	/**
	* @var Df_Ems_Model_Api_Locations_Cities
	*/
	private $_cities;	
	
	
	
	
	
	/**
	 * @return Df_Ems_Model_Api_Locations_Countries
	 */
	public function countries () {

		if (!isset ($this->_countries)) {

			/** @var Df_Ems_Model_Api_Locations_Countries $result  */
			$result =
				df_model (
					Df_Ems_Model_Api_Locations_Countries::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Ems_Model_Api_Locations_Countries);

			$this->_countries = $result;

		}


		df_assert ($this->_countries instanceof Df_Ems_Model_Api_Locations_Countries);

		return $this->_countries;

	}


	/**
	* @var Df_Ems_Model_Api_Locations_Countries
	*/
	private $_countries;		
	
	
	



	/**
	 * @return Df_Ems_Model_Api_Locations_Regions
	 */
	public function regions () {

		if (!isset ($this->_regions)) {

			/** @var Df_Ems_Model_Api_Locations_Regions $result  */
			$result =
				df_model (
					Df_Ems_Model_Api_Locations_Regions::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Ems_Model_Api_Locations_Regions);

			$this->_regions = $result;

		}


		df_assert ($this->_regions instanceof Df_Ems_Model_Api_Locations_Regions);

		return $this->_regions;

	}


	/**
	* @var Df_Ems_Model_Api_Locations_Regions
	*/
	private $_regions;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Ems_Helper_Api';
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