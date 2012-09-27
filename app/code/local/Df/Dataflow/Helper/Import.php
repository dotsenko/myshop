<?php


class Df_Dataflow_Helper_Import extends Mage_Core_Helper_Abstract {


	/**
	 * @return Df_Dataflow_Model_Import_Config
	 */
	public function getConfig () {
	
		if (!isset ($this->_config)) {
	
			/** @var Df_Dataflow_Model_Import_Config $result  */
			$result =
				df_model (
					Df_Dataflow_Model_Import_Config::getNameInMagentoFormat()
				)
			;
	
	
			df_assert ($result instanceof Df_Dataflow_Model_Import_Config);
	
			$this->_config = $result;
	
		}
	
	
		df_assert ($this->_config instanceof Df_Dataflow_Model_Import_Config);
	
		return $this->_config;
	
	}
	
	
	/**
	* @var Df_Dataflow_Model_Import_Config
	*/
	private $_config;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Helper_Import';
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