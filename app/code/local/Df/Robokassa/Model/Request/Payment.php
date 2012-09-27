<?php


class Df_Robokassa_Model_Request_Payment extends Df_Payment_Model_Request_Payment {



	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {

		/** @var array $result  */
		$result =
			array (
				self::REQUEST_VAR__SHOP_ID => $this->getShopId()

				,
				self::REQUEST_VAR__PAYMENT_AMOUNT => $this->getAmount()->getAsString()

				,
				self::REQUEST_VAR__ORDER_ID => $this->getOrder ()->getIncrementId ()

				,
				self::REQUEST_VAR__DESCRIPTION => $this->getPaymentDescription ()

				,
				self::REQUEST_VAR__SIGNATURE =>	$this->getSignature ()

				,
				self::REQUEST_VAR__CURRENCY =>
					$this->getPaymentMethod()->getRmConfig()->service()
						->getCurrencyCodeInServiceFormat()

				,
				self::REQUEST_VAR__EMAIL => NULL
			)
		;


		df_result_array ($result);

		return $result;

	}




	/**
	 * @override
	 * @return Df_Robokassa_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_Robokassa_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_Robokassa_Model_Payment);

		return $result;

	}




	/**
	 * @param Mage_Sales_Model_Order_Item $orderItem
	 * @return string
	 */
	private function getOrderItemDescription (Mage_Sales_Model_Order_Item $orderItem) {

		/** @var Df_Sales_Model_Order_Item_Extended $orderItemExtended  */
		$orderItemExtended =
			Df_Sales_Model_Order_Item_Extended::create (
				$orderItem
			)
		;

		df_assert ($orderItemExtended instanceof Df_Sales_Model_Order_Item_Extended);


		/** @var string $result  */
		$result =
			sprintf (
				self::TEMPLATE__ORDER_ITEM_DESCRIPTION
				,
				$orderItem->getName ()
				,
				$orderItemExtended->getQtyOrdered ()
			)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string[]
	 */
	private function getOrderItemDescriptions () {

		if (!isset ($this->_orderItemDescriptions)) {

			/** @var array $result  */
			$result = array ();


			foreach ($this->getOrder()->getItemsCollection (array (), true) as $orderItem) {

				/** @var Mage_Sales_Model_Order_Item $orderItem */
				df_assert ($orderItem instanceof Mage_Sales_Model_Order_Item);

				$result []=
					$this->getOrderItemDescription (
						$orderItem
					)
				;
			}


			df_assert_array ($result);

			$this->_orderItemDescriptions = $result;

		}


		df_result_array ($this->_orderItemDescriptions);

		return $this->_orderItemDescriptions;

	}


	/**
	* @var string[]
	*/
	private $_orderItemDescriptions;




	/**
	 * @return string
	 */
	private function getPaymentDescription () {

		if (!isset ($this->_paymentDescription)) {

			/** @var string $result  */
			$result =
				implode (
					', '
					,
					$this->getOrderItemDescriptions()
				)
			;


			df_assert_string ($result);

			$this->_paymentDescription = $result;

		}


		df_result_string ($this->_paymentDescription);

		return $this->_paymentDescription;

	}


	/**
	* @var string
	*/
	private $_paymentDescription;



	
	
	/**
	 * @return string
	 */
	private function getSignature () {

		return
			md5 (
				implode (
					self::SIGNATURE_PARTS_SEPARATOR
					,
					$this->preprocessParams (
						array (
							self::REQUEST_VAR__SHOP_ID => $this->getShopId()
							,
							self::REQUEST_VAR__PAYMENT_AMOUNT => $this->getAmount()->getAsString()
							,
							self::REQUEST_VAR__ORDER_ID => $this->getOrder ()->getIncrementId ()
							,
							'dummy-1' => $this->getPaymentMethod()->getRmConfig()->service()
								->getRequestPassword()
						)
					)
				)
			)
		;
	}




	/**
	 * @return float
	 */
	private function getPaymentAmount () {

		/** @var float $result  */
		$result =
			round (
				$this->getAmount()->getOriginal()
				,
				2
			)
		;

		df_result_float ($result);

		return $result;

	}
	




	const REQUEST_VAR__CURRENCY = 'sIncCurrLabel';
	const REQUEST_VAR__DESCRIPTION = 'Desc';
	const REQUEST_VAR__EMAIL = 'sEmail';
	const REQUEST_VAR__ORDER_ID = 'InvId';
	const REQUEST_VAR__PAYMENT_AMOUNT = 'OutSum';
	const REQUEST_VAR__SHOP_ID = 'MrchLogin';
	const REQUEST_VAR__SIGNATURE = 'SignatureValue';

	const SIGNATURE_PARTS_SEPARATOR = ':';

	const TEMPLATE__ORDER_ITEM_DESCRIPTION = '%s (%d)';
	
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Robokassa_Model_Request_Payment';
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


