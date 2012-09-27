<?php

class Df_OnPay_Model_Config_Service extends Df_Payment_Model_Config_Area_Service {
	
	
	
	/**
	 * @return string
	 */
	public function getReceiptCurrency () {

		if (!isset ($this->_receiptCurrency)) {

			/** @var string $result  */
			$result =
				$this->getVar (
					self::KEY__VAR__RECEIPT_CURRENCY
				)
			;


			df_assert_string ($result);

			$this->_receiptCurrency = $result;

		}


		df_result_string ($this->_receiptCurrency);

		return $this->_receiptCurrency;

	}


	/**
	* @var string
	*/
	private $_receiptCurrency;
	



	/**
	 * @override
	 * @return string
	 */
	public function getUrlPaymentPage () {

		/** @var string $result  */
		$result =
			str_replace (
				'{shop-id}'
				,
				parent::getShopId()
				,
				parent::getUrlPaymentPage()
			)
		;

		df_assert_string ($result);

		return $result;

	}



	const KEY__VAR__RECEIPT_CURRENCY = 'receipt_currency';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_OnPay_Model_Config_Service';
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


