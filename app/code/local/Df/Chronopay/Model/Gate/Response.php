<?php


class Df_Chronopay_Model_Gate_Response extends Df_Phpquery_Model_Xml {




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Chronopay_Model_Gate_Response';
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
	 * @return Df_Chronopay_Model_Gate_Response
	 */
	public function check () {
	    if (0 != $this->getCode ()) {
		    df_log ($this->getDiagnosticMessage());
			Mage::throwException ($this->getDiagnosticMessage());
	    }
	    return $this;
	}


	/**
	 * @var string
	 */
	private $_diagnosticMessage;

	/**
	 * @return string
	 */
	public function getDiagnosticMessage () {
		if (!isset ($this->_diagnosticMessage)) {
			$this->_diagnosticMessage =
					"Error in capturing the payment via ChronoPay."
				.
					"\r\nChronoPay error code: " . $this->getCode ()
				.
					"\r\nChronoPay extended error code: " . $this->getExtendedCode ()
				.
					"\r\nChronoPay error message: " . $this->getMessage ()
				.
					"\r\nChronoPay extended error message: " . $this->getExtendedMessage ()
				.
					"\r\nTransaction ID: " . $this->getTransactionId ()
			;
		}
	    return $this->_diagnosticMessage;
	}


	/**
	 * @var string
	 */
	private $_extendedCode;

	/**
	 * @return string
	 */
	public function getExtendedCode () {
		if (!isset ($this->_extendedCode)) {
			$this->_extendedCode =
				$this->pq ("response > Extended > code")->text ()
			;
		}
		return $this->_extendedCode;
	}


	/**
	 * @var string
	 */
	private $_extendedMessage;

	/**
	 * @return string
	 */
	public function getExtendedMessage () {
		if (!isset ($this->_extendedMessage)) {
			$this->_extendedMessage =
				$this->pq ("response > Extended > message")->text ()
			;
		}
		return $this->_extendedMessage;
	}


	/**
	 * @var string
	 */
	private $_message;

	/**
	 * @return string
	 */
	public function getMessage () {
		if (!isset ($this->_message)) {
			$this->_message =
				$this->pq ("response > message")->text ()
			;
		}
		return $this->_message;
	}


	/**
	 * @var int
	 */
	private $_code;

	/**
	 * @return int
	 */
	public function getCode () {
		if (!isset ($this->_code)) {
			$this->_code =
				$this->pq ("response > code")->text ()
			;
			df_assert (
				df_check_integer ($this->_code)
				,
				sprintf (
					"Invalid response code from ChronoPay gate: %s"
					,
					$this->_code
				)
			)
			;
		    $this->_code = (int)$this->_code;
		}
		return $this->_code;
	}



	/**
	 * @var string
	 */
	private $_transactionId;

	/**
	 * @return string
	 */
	public function getTransactionId () {
		if (!isset ($this->_transactionId)) {
			$this->_transactionId =
				// could be empty for report about an error
				$this->pq ("response > Transaction")->text ()
			;
		}
		return $this->_transactionId;
	}



	/**
	 * @return string
	 */
	protected function getDocument () {
		$result = NULL;
		try {
			$result = parent::getDocument();
		}
		catch (Exception $e) {
			Mage
				::throwException (
						df_helper()->chronopay()->__ (
							"Sorry, the payment gateway seems not working right now."
						)
					.
						"\r\n"
					.
						df_helper()->chronopay()->__ (
							"Please, choose another payment method or contact us."
						)
				)
			;
		}
	    return $result;
	}

}