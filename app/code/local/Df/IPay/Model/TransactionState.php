<?php

class Df_IPay_Model_TransactionState extends Df_Core_Model_Abstract {


	/**
	 * @return Df_IPay_Model_TransactionState
	 */
	public function clear () {

		$this->getPayment()
			->unsAdditionalInformation (
				self::PAYMENT_PARAM__STATE
			)
			->save ()
		;

		return $this;

	}



	/**
	 * @return string|null
	 */
	public function get () {

		/** @var string|null $result  */
		$result =
			$this->getPayment()
				->getAdditionalInformation (
					self::PAYMENT_PARAM__STATE
				)
		;


		if (!is_null ($result)) {
			df_result_string ($result);
		}


		return $result;

	}



	/**
	 * @return Df_IPay_Model_TransactionState
	 */
	public function restore () {

		if (!is_null ($this->_previousState)) {

			$this
				->update (
					$this->_previousState
				)
			;

		}

		return $this;

	}



	/**
	 * @param string $newState
	 * @return Df_IPay_Model_TransactionState
	 */
	public function update ($newState) {

		df_param_string ($newState, 0);

		$this->_previousState = $this->get ();


		/**
		 * Обратите внимание, что хранить состояние транзации в сессии было бы неправильно:
		 * это не защищает при одновременной оплате одного заказа несколькими пользователями
		 */
		$this->getPayment()
			->setAdditionalInformation (
				self::PAYMENT_PARAM__STATE
				,
				$newState
			)
			->save ()
		;

		return $this;

	}


	/**
	 * @var string|null
	 */
	private $_previousState = null;



	/**
	 * @return Mage_Payment_Model_Info
	 */
	private function getPayment () {

		/** @var Mage_Payment_Model_Info $result  */
		$result = $this->cfg (self::PARAM__PAYMENT);

		df_assert ($result instanceof Mage_Payment_Model_Info);

		return $result;

	}


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__PAYMENT, 'Mage_Payment_Model_Info'
			)
		;
	}


	const PARAM__PAYMENT = 'payment';

	const PAYMENT_PARAM__STATE = 'df_ipay__transaction_state';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_IPay_Model_TransactionState';
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

