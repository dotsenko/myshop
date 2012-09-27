<?php


class Df_Chronopay_Model_Gate_Exception extends Mage_Core_Exception {



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Chronopay_Model_Gate_Exception';
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










	/**
	 * @var array
	 */
	private $_params;

	/**
	 * @param array $params
	 *
	 */
	public function __construct (array $params = array ()) {
		$this->_params = $params;
		parent::__construct($this->getMessageForCustomer());
	}


	/**
	 * @return string
	 */
	public function getMessageForCustomer () {
	    return $this->getSpecificMessage ("messageForCustomer");
	}


	/**
	 * @return string
	 */
	public function getMessageForLog () {
	    return $this->getSpecificMessage ("messageForLog");
	}


	/**
	 * @return string
	 */
	public function getMessageForStatus () {
 	    return $this->getSpecificMessage ("messageForStatus");
	}


	/**
	 * @return string
	 */
	private function getSpecificMessage ($messageType) {
 	    return df_a ($this->_params, $messageType, $this->getDefaultMessage ());
	}


	/**
	 * @return string
	 */
	private function getDefaultMessage () {
 	    return df_a ($this->_params, "message");
	}

}