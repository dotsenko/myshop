<?php


abstract class Df_Pd4_Block_LinkToDocument extends Df_Core_Block_Template {


	/**
	 * @override
	 * @return string
	 */
	public function getArea() {

		/** @var string $result  */
		$result = Df_Core_Const_Design_Area::FRONTEND;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getPaymentDocumentUrl () {

		if (!isset ($this->_paymentDocumentUrl)) {

			/** @var string $result  */
			$result =
				$this->getUrl(
					Df_Pd4_Const::PAYMENT_DOCUMENT_URL_BASE
					,
					array (
						Df_Pd4_Const::URL_PARAM__ORDER_PROTECT_CODE =>
							$this->getOrder ()->getData (
								Df_Sales_Const::ORDER_PARAM__PROTECT_CODE
							)
					)
				)
			;


			df_assert_string ($result);

			$this->_paymentDocumentUrl = $result;

		}


		df_result_string ($this->_paymentDocumentUrl);

		return $this->_paymentDocumentUrl;

	}


	/**
	* @var string
	*/
	private $_paymentDocumentUrl;





	/**
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result =
				parent::needToShow()
			&&
				$this->getOrder ()
			&&
				$this->getOrder()->getPayment()
			&&
				(
						$this->getOrder()->getPayment()->getMethodInstance()
					instanceof
						Df_Pd4_Model_Payment
				)
		;


		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @abstract
	 * @return Mage_Sales_Model_Order
	 */
	abstract protected function getOrder ();







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pd4_Block_LinkToDocument';
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


