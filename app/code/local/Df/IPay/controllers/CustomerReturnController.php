<?php


class Df_IPay_CustomerReturnController extends Mage_Core_Controller_Front_Action {

	/**
	 * Платёжная система присылает сюда подтверждение приёма оплаты от покупателя.
	 *
	 * @return void
	 */
    public function indexAction() {

		try {

			$this
				->setRedirectUrl (
						(
								Mage_Sales_Model_Order::STATE_PROCESSING
							 ===
								$this->getOrder()->getState()
						)
					?
						df_helper()->payment()->url()->getCheckoutSuccess()
					:
						df_helper()->payment()->url()->getCheckoutFail()
				)
			;

		}
		catch (Exception $e) {
			df_handle_entry_point_exception ($e, false);
		}

		$this->getResponse()->setRedirect ($this->getRedirectUrl());

    }


	
	
	/**
	 * @return Df_IPay_CustomerReturnController
	 */
	public function processDelayed () {
		
		$this
			->setRedirectUrl (
				df_helper()->payment()->url()->getCheckoutSuccess()
			)
		;

		$this->getOrder()
			->addStatusHistoryComment(
				'Покупатель решил оплатить заказ через терминал Приватбанка. Ждём оплату.'
			)
		;

		$this->getOrder()
			->setData (
				Df_Sales_Const::ORDER_PARAM__IS_CUSTOMER_NOTIFIED
				,
				false
			)
		;

		$this->getOrder()->save();

	
		return $this;
	
	}	
	
	

	
	/**
	 * @return Df_IPay_CustomerReturnController
	 */
	public function processFailure () {
		
		$this
			->setRedirectUrl (
				df_helper()->payment()->url()->getCheckoutFail()
			)
		;
	
		return $this;
	
	}	
	
	
	
	
	/**
	 * @return Df_IPay_CustomerReturnController
	 */
	public function processSuccess () {
		
		$this
			->setRedirectUrl (
				df_helper()->payment()->url()->getCheckoutSuccess()
			)
		;		
	
		return $this;

	}





	/**
	 * @return Mage_Sales_Model_Order
	 */
	private function getOrder () {

		if (!isset ($this->_order)) {

			/** @var Mage_Sales_Model_Order $result  */
			$result =
				df_model (
					Df_Sales_Const::ORDER_CLASS_MF
				)
			;

			$result
				->loadByIncrementId (
					df_mage()->checkout()->sessionSingleton()->getData (
						Df_Checkout_Const::SESSION_PARAM__LAST_REAL_ORDER_ID
					)
				)
			;

			df_assert ($result instanceof Mage_Sales_Model_Order);

			$this->_order = $result;

		}


		df_assert ($this->_order instanceof Mage_Sales_Model_Order);

		return $this->_order;

	}


	/**
	* @var Mage_Sales_Model_Order
	*/
	private $_order;



	
	


	/**
	 * @return array
	 */
	private function getPaymentInfoAsArray () {

		if (!isset ($this->_paymentInfoAsArray)) {

			/** @var array $result  */
			$result = $this->getPaymentInfoAsVarienXml()->asCanonicalArray();

			df_assert_array ($result);

			$this->_paymentInfoAsArray = $result;

		}


		df_result_array ($this->_paymentInfoAsArray);

		return $this->_paymentInfoAsArray;

	}


	/**
	* @var array
	*/
	private $_paymentInfoAsArray;






	/**
	 * @return Varien_Simplexml_Element
	 */
	private function getPaymentInfoAsVarienXml () {

		if (!isset ($this->_paymentInfoAsVarienXml)) {

			/** @var Varien_Simplexml_Element $result  */
			$result =
				new Varien_Simplexml_Element (
					base64_decode (
						$this->getRequest()->getParam ('operation_xml')						
					)					
				)
			;


			df_assert ($result instanceof Varien_Simplexml_Element);

			$this->_paymentInfoAsVarienXml = $result;

		}


		df_assert ($this->_paymentInfoAsVarienXml instanceof Varien_Simplexml_Element);

		return $this->_paymentInfoAsVarienXml;

	}


	/**
	* @var Varien_Simplexml_Element
	*/
	private $_paymentInfoAsVarienXml;



	
	
	/**
	 * @return string
	 */
	private function getPaymentStatusCode () {
	
		if (!isset ($this->_paymentStatusCode)) {
	
			/** @var string $result  */
			$result = 
				df_a (
					$this->getPaymentInfoAsArray()
					,
					'status'
				)
			;

	
			df_assert_string ($result);
	
			$this->_paymentStatusCode = $result;
	
		}
	
	
		df_result_string ($this->_paymentStatusCode);
	
		return $this->_paymentStatusCode;
	
	}
	
	
	/**
	* @var string
	*/
	private $_paymentStatusCode;	
	




	/**
	 * @return string
	 */
	private function getProcessorMethodName () {

		if (!isset ($this->_processorMethodName)) {

			/** @var string $result  */
			$result =
				implode (
					Df_Core_Const::T_EMPTY
					,
					array (
						'process'
						,
						uc_words (
							$this->translatePaymentStatusCodeToProcessorCode (
								$this->getPaymentStatusCode()								
							)							
							,
							// $destSep
							Df_Core_Const::T_EMPTY
						)
					)
				)
			;

			if (!method_exists($this, $result)) {
				$result = 'processSuccess';
			}


			df_assert_string ($result);

			$this->_processorMethodName = $result;

		}


		df_result_string ($this->_processorMethodName);

		return $this->_processorMethodName;

	}


	/**
	* @var string
	*/
	private $_processorMethodName;

	
	
	
	

	
	

	/**
	 * @return string
	 */
	private function getRedirectUrl () {

		if (!isset ($this->_redirectUrl)) {

			/** @var string $result  */
			$result = df_helper()->payment()->url()->getCheckoutSuccess();

			df_assert_string ($result);

			$this->_redirectUrl = $result;

		}


		df_result_string ($this->_redirectUrl);

		return $this->_redirectUrl;

	}


	/**
	* @var string
	*/
	private $_redirectUrl;





	/**
	 * @return Df_IPay_CustomerReturnController
	 */
	private function processPaymentStatusCode () {
		
		call_user_func (array ($this, $this->getProcessorMethodName ()));

		return $this;

	}


	
	
	
	/**                                          
	 * @param string  $redirectUrl
	 * @return string
	 */
	private function setRedirectUrl ($redirectUrl) {
		
		df_param_string ($redirectUrl, 0);
	
		$this->_redirectUrl = $redirectUrl;
	
		return $this;
	
	}	
		
	
	
	
	
	/**            
	 * @param string $paymentStatusCode
	 * @return string
	 */
	private function translatePaymentStatusCodeToProcessorCode ($paymentStatusCode) {
		
		df_param_string ($paymentStatusCode, 0);
	
		/** @var string $result  */
		$result =
			df_a (
				array (
						'PAYMENT_STATE__WAIT_SECURE'
					=>
						'PAYMENT_STATE__SUCCESS'
				)
				,
				$paymentStatusCode
				,
				$paymentStatusCode
			)
		;
	
	
		df_result_string ($result);
	
		return $result;
	
	}	
		

}



