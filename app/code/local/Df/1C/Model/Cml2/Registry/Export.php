<?php

class Df_1C_Model_Cml2_Registry_Export extends Df_Core_Model_Abstract {
	
	

	/**
	 * @return Df_1C_Model_Cml2_Registry_Export_Products
	 */
	public function getProducts () {
	
		if (!isset ($this->_products)) {
	
			/** @var Df_1C_Model_Cml2_Registry_Export_Products $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Registry_Export_Products::getNameInMagentoFormat()
				)
			;
	
			df_assert ($result instanceof Df_1C_Model_Cml2_Registry_Export_Products);
	
			$this->_products = $result;
		}
	
		df_assert ($this->_products instanceof Df_1C_Model_Cml2_Registry_Export_Products);
	
		return $this->_products;
	}
	
	
	/**
	* @var Df_1C_Model_Cml2_Registry_Export_Products
	*/
	private $_products;	
	
	
	
	
	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Registry_Export';
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


