<?php

class Df_Assist_Model_Config_Service extends Df_Payment_Model_Config_Area_Service {



	/**
	 * @return string
	 */
	public function getUrlConfirmCharge () {

		/** @var string $result  */
		$result =
			$this->getUrl (
				self::KEY__CONST__URL__CONFIRM_CHARGE
			)
		;

		df_assert_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getUrlOrderState () {

		/** @var string $result  */
		$result =
			$this->getUrl (
				self::KEY__CONST__URL__ORDER_STATE
			)
		;

		df_assert_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getUrlPaymentHistory () {

		/** @var string $result  */
		$result =
			$this->getUrl (
				self::KEY__CONST__URL__PAYMENT_HISTORY
			)
		;

		df_assert_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	public function getUrlPaymentPage () {

		/** @var string $result  */
		$result =
			$this->getUrl (
				self::KEY__CONST__URL__PAYMENT_PAGE
			)
		;

		df_assert_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getUrlPaymentsHistory () {

		/** @var string $result  */
		$result =
			$this->getUrl (
				self::KEY__CONST__URL__PAYMENTS_HISTORY
			)
		;

		df_assert_string ($result);

		return $result;

	}



	/**
	 * @return string
	 */
	public function getUrlRecurrentPayment () {

		/** @var string $result  */
		$result =
			$this->getUrl (
				self::KEY__CONST__URL__RECURRENT_PAYMENT
			)
		;

		df_assert_string ($result);

		return $result;

	}



	/**
	 * @return string
	 */
	public function getUrlRefund () {

		/** @var string $result  */
		$result =
			$this->getUrl (
				self::KEY__CONST__URL__REFUND
			)
		;

		df_assert_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getDomain () {

		/** @var string $result  */
		$result =
				$this->isTestMode()
			?
				$this->getConst (self::KEY__CONST__DOMAIN)
			:
				$this->getVar (self::KEY__VAR__DOMAIN)
		;


		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	private function getPaymentPagePath () {

		/** @var string $result  */
		$result =
			$this->getConstManager()->getUrl (
				self::KEY__CONST__URL__PAYMENT_PAGE
				,
				false
			)
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @param string $type
	 * @return string
	 */
	private function getUrl ($type) {

		df_assert_string ($type);


		/** @var string $path  */
		$path =
				Df_Core_Const::T_URL_PATH_SEPARATOR
			.
				$this->getConstManager()->getUrl (
					$type
					,
					false
				)
		;


		/** @var Zend_Uri_Http $uri  */
		$uri = Zend_Uri::factory ();

		df_assert ($uri instanceof Zend_Uri_Http);


		$uri
			->setHost (
				$this->getDomain()
			)
		;

		$uri
			->setPath (
				$path
			)
		;


		/** @var string $result  */
		$result = $uri->getUri();


		df_result_string ($result);

		return $result;

	}




	const KEY__CONST__URL__CONFIRM_CHARGE = 'confirm_charge';
	const KEY__CONST__URL__ORDER_STATE = 'order_state';
	const KEY__CONST__URL__PAYMENT_HISTORY = 'payment_history';
	const KEY__CONST__URL__PAYMENT_PAGE = 'payment_page';
	const KEY__CONST__URL__PAYMENTS_HISTORY = 'payments_history';
	const KEY__CONST__URL__RECURRENT_PAYMENT = 'recurrent_payment';
	const KEY__CONST__URL__REFUND = 'refund';



	const KEY__CONST__DOMAIN = 'domain';

	const KEY__VAR__DOMAIN = 'domain';
	
	
	
	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Assist_Model_Config_Service';
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


