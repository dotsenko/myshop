<?php


class Df_Pd4_Helper_Data extends Mage_Core_Helper_Abstract {
	
	
	
	
	/**
	 * @return Df_Pd4_Model_Request_Document_View
	 */
	public function getDocumentViewAction () {
	
		if (!isset ($this->_documentViewAction)) {
	
			/** @var Df_Pd4_Model_Request_Document_View $result  */
			$result = 
				df_model (
					Df_Pd4_Model_Request_Document_View::getNameInMagentoFormat()
				)
			;
	
	
			df_assert ($result instanceof Df_Pd4_Model_Request_Document_View);
	
			$this->_documentViewAction = $result;
	
		}
	
	
		df_assert ($this->_documentViewAction instanceof Df_Pd4_Model_Request_Document_View);
	
		return $this->_documentViewAction;
	
	}
	
	
	/**
	* @var Df_Pd4_Model_Request_Document_View
	*/
	private $_documentViewAction;	
	
	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pd4_Helper_Data';
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