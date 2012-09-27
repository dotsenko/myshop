<?php


class Df_IPay_Model_Request_Payment extends Df_Payment_Model_Request_Payment {



	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {

		/** @var array $result  */
		$result =
			array_merge (
				array (

					self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()


					,
					self::REQUEST_VAR__ORDER_NUMBER =>
						$this
							->getOrder()
							/**
							 * iPay допускает не больше 6 символов в номере платежа,
							 * поэтому используем getId вместо обычного getIncrementId
							 */
							->getId()


					,
					self::REQUEST_VAR__ORDER_AMOUNT =>
						$this
							->getAmount()
							/**
							 * iPay требует, чтобы суммы были целыми числами
							 */
							->getAsInteger()



					,
					'amount_editable' => 'N'


					,
					self::REQUEST_VAR__URL_RETURN =>
						/**
						 * iPay (как и LiqPay),
						 * в отличие от других платёжных систем,
						 * не поддерживает разные веб-адреса
						 * для успешного и неуспешного сценариев оплаты
						 */
						$this->getUrlReturn()

				)
			)
		;

		df_result_array ($result);

		return $result;
	
	}








	/**
	 * @override
	 * @return Df_IPay_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_IPay_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_IPay_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_IPay_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_IPay_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_IPay_Model_Config_Service);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getUrlReturn () {

		if (!isset ($this->_urlReturn)) {

			/** @var string $result  */
			$result =
				Mage::getUrl (
					implode (
						Df_Core_Const::T_URL_PATH_SEPARATOR
						,
						array (
							$this->getPaymentMethod()->getCode()
							,
							'customerReturn'
						)
					)
					,
					/**
					 * Без _nosid система будет формировать ссылку c ?___SID=U.
					 * На всякий случай избегаем этого.
					 */
					array ('_nosid' => true)
				)
			;


			df_assert_string ($result);

			$this->_urlReturn = $result;

		}


		df_result_string ($this->_urlReturn);

		return $this->_urlReturn;

	}


	/**
	* @var string
	*/
	private $_urlReturn;


	



	const REQUEST_VAR__SHOP_ID = 'srv_no';
	const REQUEST_VAR__ORDER_NUMBER = 'pers_acc';
	const REQUEST_VAR__ORDER_AMOUNT = 'amount';
	const REQUEST_VAR__URL_RETURN = 'provider_url';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_IPay_Model_Request_Payment';
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


