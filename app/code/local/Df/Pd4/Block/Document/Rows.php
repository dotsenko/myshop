<?php


class Df_Pd4_Block_Document_Rows extends Df_Core_Block_Template {




	/**
	 * @return string
	 */
	public function getRecipientName () {

		/** @var string $result  */
		$result =
			$this->escapeHtml (
				$this->getConfigAdmin()->getRecipientName()
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return string
	 */
	public function getRecipientTaxNumber () {


		/** @var string $result  */
		$result =
			$this->escapeHtml (
				$this->getConfigAdmin()->getRecipientTaxNumber()
			)
		;


		df_result_string ($result);


		return $result;

	}





	/**
	 * @return string
	 */
	public function getRecipientBankAccountNumber () {

		/** @var string $result  */
		$result =
			$this->escapeHtml (
				$this->getConfigAdmin()->getRecipientBankAccountNumber()
			)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	public function getRecipientBankName () {

		/** @var string $result  */
		$result =
			$this->escapeHtml (
				$this->getConfigAdmin()->getRecipientBankName()
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return string
	 */
	public function getRecipientBankId () {

		/** @var string $result  */
		$result =
			$this->escapeHtml (
				$this->getConfigAdmin()->getRecipientBankId()
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return string
	 */
	public function getRecipientBankLoro () {

		/** @var string $result  */
		$result =
			$this->escapeHtml (
				$this->getConfigAdmin()->getRecipientBankLoro()
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return string
	 */
	public function getPaymentPurpose () {

		/** @var string $result  */
		$result =
			$this->escapeHtml (
				strtr (
					$this->getConfigAdmin()->getPaymentPurposeTemplate ()
					,
					array (
						self::PAYMENT_PURPOSE_TEMPLATE__PARAM__ORDER_ID =>
							$this->getOrder ()->getDataUsingMethod (
								Df_Sales_Const::ORDER_PARAM__INCREMENT_ID
							)
						,
						self::PAYMENT_PURPOSE_TEMPLATE__PARAM__ORDER_DATE =>
							$this->getOrderDateAsString()
					)
				)

			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return string
	 */
	public function getCustomerName () {


		/** @var string $result  */
		$result =
			$this->escapeHtml (
				implode (
					Df_Core_Const::T_SPACE
					,
					df_clean (
						array (
							$this->getOrder()->getData (
								Df_Sales_Const::ORDER_PARAM__CUSTOMER_LASTNAME
							)
							,
							$this->getOrder()->getData (
								Df_Sales_Const::ORDER_PARAM__CUSTOMER_FIRSTNAME
							)
							,
							$this->getOrder()->getData (
								Df_Sales_Const::ORDER_PARAM__CUSTOMER_MIDDLENAME
							)
						)
					)
				)

			)
		;


		df_result_string ($result);


		return $result;

	}





	/**
	 * @return string
	 */
	public function getCustomerAddressAsCompositeString () {


		/** @var string $result  */
		$result =
			$this->escapeHtml (
				implode (
					implode (
						Df_Core_Const::T_EMPTY
						,
						array (
							Df_Core_Const::T_COMMA
							,
							Df_Core_Const::T_SPACE
						)
					)

					,
					df_clean (
						array (
							$this->getCustomerAddress()->getData (
								Df_Sales_Const::ORDER_ADDRESS__PARAM__POSTCODE
							)
							,
							$this->getCustomerAddress()->getData (
								Df_Sales_Const::ORDER_ADDRESS__PARAM__CITY
							)
							,
							$this->getCustomerAddress()->getData (
								Df_Sales_Const::ORDER_ADDRESS__PARAM__STREET
							)
						)
					)
				)

			)
		;


		df_result_string ($result);


		return $result;

	}





	/**
	 * @return string
	 */
	public function getOrderAmountIntegerPartAsString () {

		$result =
			df_string (
				$this->getActionDf()->getPaymentMethod()->getRequestPayment()->getAmount()
					->getIntegerPart()
			)
		;

		df_result_string ($result);

		return $result;

	}







	/**
	 * @return string
	 */
	public function getOrderAmountFractionalPartAsString () {

		/** @var string $result  */
		$result =
			df_string (
				$this->getActionDf()->getPaymentMethod()->getRequestPayment()->getAmount()
					->getFractionalPartAsString()
			)
		;


		df_result_string ($result);

		return $result;

	}



	

	/**
	 * @return int
	 */
	public function getOrderYear () {

		/** @var int $result  */
		$result =
			intval (
				$this->getOrderDate()->toString(
					Zend_Date::YEAR
				)
			)
		;


		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return Df_Pd4_Model_Config_Admin
	 */
	protected function getConfigAdmin () {

		if (!isset ($this->_configAdmin)) {

			/** @var Df_Pd4_Model_Config_Admin $result  */
			$result =
				$this->getActionDf()->getPaymentMethod()
					->getRmConfig()->admin()
			;


			df_assert ($result instanceof Df_Pd4_Model_Config_Admin);

			$this->_configAdmin = $result;

		}


		df_assert ($this->_configAdmin instanceof Df_Pd4_Model_Config_Admin);

		return $this->_configAdmin;

	}


	/**
	* @var Df_Pd4_Model_Config_Admin
	*/
	private $_configAdmin;


	


	/**
	 * @return Mage_Sales_Model_Order_Address
	 */
	private function getCustomerAddress () {

		/** @var Mage_Sales_Model_Order_Address $result  */
		$result =
			$this->getOrder()->getBillingAddress()
		;

		df_assert ($result instanceof Mage_Sales_Model_Order_Address);

		return $result;
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		df_result_string ($result);

		return $result;

	}


		

	/**
	 * @return string
	 */
	private function getOrderDateAsString () {
	
		if (!isset ($this->_orderDateAsString)) {
	
			/** @var string $result  */
			$result = 
				$this->getOrderDate ()->toString (
					Df_Core_Model_Format_Date::FORMAT__RUSSIAN
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_orderDateAsString = $result;
	
		}
	
	
		df_result_string ($this->_orderDateAsString);
	
		return $this->_orderDateAsString;
	
	}
	
	
	/**
	* @var string
	*/
	private $_orderDateAsString;	
	
	
	
	
	
	/**
	 * @return Zend_Date
	 */
	private function getOrderDate () {
	
		if (!isset ($this->_orderDate)) {
	
			/** @var Zend_Date $result  */
			$result =
				df_parse_mysql_datetime (
					$this->getOrder()->getData (
						Df_Sales_Const::ORDER_PARAM__CREATED_AT
					)
				)
			;

			df_assert ($result instanceof Zend_Date);
	
			$this->_orderDate = $result;
		}
	
	
		df_assert ($this->_orderDate instanceof Zend_Date);
	
		return $this->_orderDate;
	}
	
	
	/**
	* @var Zend_Date
	*/
	private $_orderDate;	
	
	
	




	/**
	 * @return Mage_Sales_Model_Order
	 */
	private function getOrder () {

		/** @var Mage_Sales_Model_Order $result  */
		$result = $this->getActionDf()->getOrder();

		df_assert ($result instanceof Mage_Sales_Model_Order);

		return $result;

	}






	/**
	 * @return Df_Pd4_Model_Request_Document_View
	 */
	private function getActionDf () {

		/** @var Df_Pd4_Model_Request_Document_View $result  */
		$result =
			df_helper()->pd4()->getDocumentViewAction()
		;

		df_assert ($result instanceof Df_Pd4_Model_Request_Document_View);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pd4_Block_Document_Rows';
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




	const DEFAULT_TEMPLATE = 'df/pd4/document/rows.phtml';


	const PAYMENT_PURPOSE_TEMPLATE__PARAM__ORDER_ID = '{order.id}';
	const PAYMENT_PURPOSE_TEMPLATE__PARAM__ORDER_DATE = '{order.date}';


}

