<?php

class Df_1C_Model_Cml2_Registry_Import_Files extends Df_Core_Model_Abstract {
	
	
	
	/**
	 * @return string
	 */
	public function getBasePath () {
	
		if (!isset ($this->_basePath)) {
	
			/** @var string $result  */
			$result = 
				implode (
					DS
					,
					array (
						Mage::getBaseDir ('var'), 'rm', '1c', $this->getArea()
					)
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_basePath = $result;
	
		}
	
	
		df_result_string ($this->_basePath);
	
		return $this->_basePath;
	
	}
	
	
	/**
	* @var string
	*/
	private $_basePath;	
	



	/**
	 * @param string $name
	 * @return Varien_Simplexml_Element
	 */
	public function getByRelativePath ($name) {

		df_param_string ($name, 0);
	
		if (!isset ($this->_byName [$name])) {
	
			/** @var Varien_Simplexml_Element $result  */
			$result = 
				new Varien_Simplexml_Element (
					file_get_contents (
						$this->getFullPathByRelativePath ($name)
					)
				)
			;
	
	
			df_assert ($result instanceof Varien_Simplexml_Element);
	
			$this->_byName [$name] = $result;
	
		}
	
	
		df_assert ($this->_byName [$name] instanceof Varien_Simplexml_Element);
	
		return $this->_byName [$name];
	
	}
	
	
	/**
	* @var Varien_Simplexml_Element[]
	*/
	private $_byName = array ();






	/**
	 * @param string $relativePath
	 * @return string
	 */
	public function getFullPathByRelativePath ($relativePath) {

		df_param_string ($relativePath, 0);

		/** @var string $result  */
		$result =
			implode (
				DS
				,
				array (
					$this->getBasePath ()
					,
					$relativePath
				)
			)
		;


		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	private function getArea () {
		return $this->cfg (self::PARAM__AREA);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__AREA, new Df_Zf_Validate_String())
		;
	}




	const FILE__IMPORT = 'import.xml';
	const FILE__OFFERS = 'offers.xml';


	const PARAM__AREA = 'area';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Registry_Import_Files';
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

