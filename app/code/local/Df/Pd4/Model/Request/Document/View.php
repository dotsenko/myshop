<?php



class Df_Pd4_Model_Request_Document_View extends Df_Core_Model_Abstract {
	
	
	
	/**
	 * @return Mage_Sales_Model_Order
	 */
	public function getOrder () {
	
		if (!isset ($this->_order)) {


			/** @var Mage_Sales_Model_Order $result  */
			$result =
				df_model (Df_Sales_Const::ORDER_CLASS_MF)
			;


			df_assert ($result instanceof Mage_Sales_Model_Order);


			$result->load ($this->getOrderId ());


			df_assert (
				$result->getId () == $this->getOrderId ()
				,
				"Заказ №{$this->getOrderId ()} отсутствует в системе."
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
	 * @return Df_Pd4_Model_Payment
	 */
	public function getPaymentMethod () {
	
		if (!isset ($this->_paymentMethod)) {
	
			/** @var Df_Pd4_Model_Payment $result  */
			$result = 
				Df_Payment_Model_Method_Base::getByOrder (
					$this->getOrder()
				)
			;


			df_assert (
				$result instanceof Df_Pd4_Model_Payment
				,
				"Заказ №{$this->getOrderId ()} не предназначен для оплаты через банковскую кассу."
			)
			;
	
	
			df_assert ($result instanceof Df_Pd4_Model_Payment);
	
			$this->_paymentMethod = $result;
	
		}
	
	
		df_assert ($this->_paymentMethod instanceof Df_Pd4_Model_Payment);
	
		return $this->_paymentMethod;
	
	}
	
	
	/**
	* @var Df_Pd4_Model_Payment
	*/
	private $_paymentMethod;	

	
	

	
	/**
	 * @return int
	 */
	private function getOrderId () {
	
		if (!isset ($this->_orderId)) {

			try {
				/** @var int $result  */
				$result =
					$this->getOrderResourceModel ()->getOrderIdByProtectCode (
						$this->getOrderProtectCode ()
					)
				;
			}
			catch (Exception $e) {
				df_error (
					"Заказ с кодом «{$this->getOrderProtectCode ()}» отсутствует в системе."
				);
			}



	
			df_assert (
				!is_null ($result)
				,
				$this->getInvalidUrlMessage ()
			)
			;



			df_assert (
				df_check_integer ($result)
				,
				$this->getInvalidUrlMessage ()
			)
			;



			df_assert (
				df_check_between ($result)
				,
				$this->getInvalidUrlMessage ()
			)
			;


			$this->_orderId = $result;
	
		}
	
	
		df_result_integer ($this->_orderId);
	
		return $this->_orderId;
	
	}
	
	
	/**
	* @var int
	*/
	private $_orderId;	
	
	
	
	
	
	
	/**
	 * @return Df_Sales_Model_Mysql4_Order
	 */
	private function getOrderResourceModel () {
	
		if (!isset ($this->_orderResourceModel)) {
	
			/** @var Df_Sales_Model_Mysql4_Order $result  */
			$result =
				df_model ('df_sales/mysql4_order')
			;
	
	
			df_assert ($result instanceof Df_Sales_Model_Mysql4_Order);
	
			$this->_orderResourceModel = $result;
	
		}
	
	
		df_assert ($this->_orderResourceModel instanceof Df_Sales_Model_Mysql4_Order);
	
		return $this->_orderResourceModel;
	
	}
	
	
	/**
	* @var Df_Sales_Model_Mysql4_Order
	*/
	private $_orderResourceModel;	
	
	
	




	/**
	 * @return integer
	 */
	private function getOrderProtectCode () {

		/** @var integer $result  */
		$result =
			df_request (Df_Pd4_Const::URL_PARAM__ORDER_PROTECT_CODE)
		;

		

		df_assert (
			!is_null ($result)
			,
			$this->getInvalidUrlMessage ()
		)
		;


		return $result;

	}
	
	
	
	
	
	


	
	
	
	/**
	 * @return string
	 */
	private function getInvalidUrlMessage () {
	
		if (!isset ($this->_invalidUrlMessage)) {
	
			/** @var string $result  */
			$result = 
				nl2br (
					df_helper()->pd4()->__ (
							"Вероятно, вы хотели распечатать квитанцию?"
						.
							"\nОднако ссылка на квитанцию не совсем верна."
						.
							"\nМожет быть, вы не полностью скопировали ссылку в адресмную строку браузера?"
						.
							"\nПопробуйте аккуратно ещё раз."
						.
							"\nЕсли вы вновь увидите данное сообщение — обратитесь к администратору магазина,"
						.
							" приложив к вашему обращению ссылку на квитанцию."
					)
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_invalidUrlMessage = $result;
	
		}
	
	
		df_result_string ($this->_invalidUrlMessage);
	
		return $this->_invalidUrlMessage;
	
	}
	
	
	/**
	* @var string
	*/
	private $_invalidUrlMessage;	
	


	
	
	
	
	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pd4_Model_Request_Document_View';
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


