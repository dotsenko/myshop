<?php

class Df_Chronopay_Model_Gate_Card extends Df_Core_Model_Abstract {






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Chronopay_Model_Gate_Card';
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
	 * @return int
	 */
	public function getNumber () {
		return $this->getPayment ()->getCcNumber ();
	}


	/**
	 * @return string
	 */
	public function getExpirationDate () {
		return
			sprintf (
				"%4d%02d"
				,
				$this->getPayment ()->getCcExpYear()
				,
				$this->getPayment ()->getCcExpMonth()
			)
		;
	}


	/**
	 * @return int
	 */
	public function getCvv () {
		return $this->getPayment ()->getCcCid ();
	}


	/**
	 * @return string
	 */
	public function getBankName () {
		return "Bnuu";
	}


	/**
	 * @return string
	 */
	public function getBankPhone () {
		return "+14564967654321";
	}


	/**
	 * @return Mage_Payment_Model_Info
	 */
	private function getPayment () {
		return $this->getData (self::PARAM_PAYMENT);
	}


	const PARAM_PAYMENT = 'payment';
	const PARAM_PAYMENT_TYPE = 'Mage_Payment_Model_Info';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM_PAYMENT, self::PARAM_PAYMENT_TYPE
			)
		;
	}

}
