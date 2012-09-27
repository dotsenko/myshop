<?php


class Df_WebPay_Model_Request_Payment extends Df_Payment_Model_Request_Payment {


	/**
	 * @param double|float|string $amountInOrderCurrency
	 * @return Df_Core_Model_Money
	 */
	public function convertAmountFromOrderCurrencyToServiceCurrency ($amountInOrderCurrency) {

		/** @var Df_Core_Model_Money $result  */
		$result =
			$this->getServiceConfig()
				->convertAmountFromOrderCurrencyToServiceCurrency (
					$this->getOrder()
					,
					$amountInOrderCurrency
				)
		;

		df_assert ($result instanceof Df_Core_Model_Money);

		return $result;

	}




	/**
	 * @override
	 * @return array
	 */
	protected function getParamsInternal () {

		/**
		 * Документация WEBPAY:
		 *
		 * «Оплата не будет произведена,
		 * если wsb_total и посчитанное значения товаров не будут совпадать.
		 * Покупателю будет отображена ошибка.»
		 *
		 * Выявляем эту проблему на максимально ранней стадии.
		 */
		$this->verifyAmount ();


		/** @var array $result  */
		$result =
			array_merge (
				array (
					'*scart' => Df_Core_Const::T_EMPTY


					,
					self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()


					,
					self::REQUEST_VAR__SHOP_NAME => $this->getTransactionDescription()



					,
					self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()


					,
					self::REQUEST_VAR__ORDER_CURRENCY =>
						$this->getServiceConfig()->getCurrencyCodeInServiceFormat()


					,
					'wsb_version' => '2'


					,
					self::REQUEST_VAR__PAYMENT_SERVICE__LANGUAGE =>
						$this->getServiceConfig()->getLocaleCodeInServiceFormat()


					,
					self::REQUEST_VAR__OPEN_KEY => $this->getOpenKey()



					,
					self::REQUEST_VAR__SIGNATURE =>	$this->getSignature ()


					,
					self::REQUEST_VAR__URL_RETURN_OK =>	$this->getUrlCheckoutSuccess()

					,
					self::REQUEST_VAR__URL_RETURN_NO =>	$this->getUrlCheckoutFail()


					,
					self::REQUEST_VAR__URL_CONFIRM => $this->getUrlConfirm()


					,
					self::REQUEST_VAR__REQUEST__TEST_MODE => $this->isTestMode ()


					,
					'wsb_invoice_item_name' => $this->getOrderItemNames()

					,
					'wsb_invoice_item_quantity' => $this->getOrderItemQtys()

					,
					'wsb_invoice_item_price' =>
						df_each (
							'getAsInteger'
							,
							$this->getOrderItemPrices()
						)

					,
					'wsb_tax' => $this->getAmountTaxCorrected()->getAsInteger()


					,
					'wsb_shipping_name' => $this->getOrder()->getShippingCarrier()->getConfigData ('name')


					,
					'wsb_shipping_price' => $this->getAmountShipping()->getAsInteger()

					,
					'wsb_discount_name' => 'Скидка'

					,
					'wsb_discount_price' =>	$this->getAmountDiscountCorrected()->getAsInteger()


					,
					self::REQUEST_VAR__ORDER_AMOUNT =>
						$this
							->getAmount()
							/**
							 * WEBPAY требует, чтобы суммы были целыми числами
							 */
							->getAsInteger()


					,
					self::REQUEST_VAR__CUSTOMER__EMAIL => $this->getOrder()->getCustomerEmail()



					,
					self::REQUEST_VAR__CUSTOMER__PHONE => $this->getBillingAddress()->getTelephone()


				)
			)
		;

		df_result_array ($result);

		return $result;
	
	}








	/**
	 * @override
	 * @return Df_WebPay_Model_Payment
	 */
	protected function getPaymentMethod () {

		/** @var Df_WebPay_Model_Payment $result  */
		$result = parent::getPaymentMethod ();

		df_assert ($result instanceof Df_WebPay_Model_Payment);

		return $result;

	}




	/**
	 * @return Df_WebPay_Model_Config_Service
	 */
	protected function getServiceConfig () {

		/** @var Df_WebPay_Model_Config_Service $result  */
		$result = parent::getServiceConfig();

		df_assert ($result instanceof Df_WebPay_Model_Config_Service);

		return $result;

	}

	
	
	
	
	/**
	 * @return Df_Core_Model_Money
	 */
	private function getAmountDiscount () {

		if (!isset ($this->_amountDiscount)) {

			/** @var Df_Core_Model_Money $result  */
			$result =
				$this->convertAmountFromOrderCurrencyToServiceCurrency (
						$this->getOrder()->getDiscountAmount()

					/**
					 * Обратите внимание, что помимо стандартных скидок Magento Community Edition
					 * мы должны учесть скидки накопительной программы и личного счёта.
					 *
					 * Модули "Накопительная программа" и "Личный счёт" не добавляют свои скидки к общей смкидке.
					 * Поэтому нам надо учесть их скидки вручную
					 */
					+
						$this->getOrder()->getData ('reward_currency_amount')
					+
						$this->getOrder()->getData ('customer_balance_amount')
				)
			;


			df_assert ($result instanceof Df_Core_Model_Money);

			$this->_amountDiscount = $result;

		}


		df_assert ($this->_amountDiscount instanceof Df_Core_Model_Money);

		return $this->_amountDiscount;

	}


	/**
	* @var Df_Core_Model_Money
	*/
	private $_amountDiscount;	
	
	
	
	
	
	/**
	 * @return Df_Core_Model_Money
	 */
	private function getAmountDiscountCorrected () {

		if (!isset ($this->_amountDiscountCorrected)) {

			/** @var Df_Core_Model_Money $result  */
			$result =
				df_model (
					Df_Core_Model_Money::getNameInMagentoFormat()
					,
					array (
						Df_Core_Model_Money::PARAM__AMOUNT =>
								$this->getAmountDiscount()->getAsInteger()
							-
								/**
								 * delta < 0 означает, что рассчитанная Magento заказа
								 * ниже, чем рассчитанная вручную.
								 *
								 * Чтобы уравнять обе суммы, 
								 * увеличиваем скидку для суммы, рассчитанной вручную.
								 *
								 * Мы применяем операцию вычитания, потому что
								 * второй операнд гарантированно неположителен.
								 */
								min (0, $this->getAmountDelta()->getAsInteger())
					)
				)
			;


			df_assert ($result instanceof Df_Core_Model_Money);

			$this->_amountDiscountCorrected = $result;

		}


		df_assert ($this->_amountDiscountCorrected instanceof Df_Core_Model_Money);

		return $this->_amountDiscountCorrected;

	}


	/**
	* @var Df_Core_Model_Money
	*/
	private $_amountDiscountCorrected;	
	
	




	/**
	 * @return Df_Core_Model_Money
	 */
	private function getAmountShipping () {

		if (!isset ($this->_amountShipping)) {

			/** @var Df_Core_Model_Money $result  */
			$result =
				$this->convertAmountFromOrderCurrencyToServiceCurrency (
					$this->getOrder()->getShippingAmount()
				)
			;


			df_assert ($result instanceof Df_Core_Model_Money);

			$this->_amountShipping = $result;

		}


		df_assert ($this->_amountShipping instanceof Df_Core_Model_Money);

		return $this->_amountShipping;

	}


	/**
	* @var Df_Core_Model_Money
	*/
	private $_amountShipping;
	
	
	
	
	/**
	 * @return Df_Core_Model_Money
	 */
	private function getAmountTax () {

		if (!isset ($this->_amountTax)) {

			/** @var Df_Core_Model_Money $result  */
			$result =
				$this->convertAmountFromOrderCurrencyToServiceCurrency (
					$this->getOrder()->getTaxAmount()
				)
			;


			df_assert ($result instanceof Df_Core_Model_Money);

			$this->_amountTax = $result;

		}


		df_assert ($this->_amountTax instanceof Df_Core_Model_Money);

		return $this->_amountTax;

	}


	/**
	* @var Df_Core_Model_Money
	*/
	private $_amountTax;






	/**
	 * @return Df_Core_Model_Money
	 */
	private function getAmountTaxCorrected () {

		if (!isset ($this->_amountTaxCorrected)) {

			/** @var Df_Core_Model_Money $result  */
			$result =
				df_model (
					Df_Core_Model_Money::getNameInMagentoFormat()
					,
					array (
						Df_Core_Model_Money::PARAM__AMOUNT =>
								$this->getAmountTax()->getAsInteger()
							+
								/**
								 * delta > 0 означает, что рассчитанная Magento заказа
								 * выше, чем рассчитанная вручную.
								 *
								 * Чтобы уравнять обе суммы, 
								 * увеличиваем налог для суммы, рассчитанной вручную.
								 */
								max (0, $this->getAmountDelta()->getAsInteger())
					)
				)
			;


			df_assert ($result instanceof Df_Core_Model_Money);

			$this->_amountTaxCorrected = $result;

		}


		df_assert ($this->_amountTaxCorrected instanceof Df_Core_Model_Money);

		return $this->_amountTaxCorrected;

	}


	/**
	* @var Df_Core_Model_Money
	*/
	private $_amountTaxCorrected;


	
	
	
	
	/**
	 * @return Df_Core_Model_Money
	 */
	private function getCalculatedAmountSubtotal () {

		if (!isset ($this->_calculatedAmountSubtotal)) {

			/** @var int $resultAsInteger  */
			$resultAsInteger = 0;


			/** @var array $tuple  */
			$tuple =
				df_tuple (
					array (
						'price' => $this->getOrderItemPrices()
						,
						'qty' => $this->getOrderItemQtys()
					)
				)
			;

			foreach ($tuple as $item) {

				/** @var array $item */
				df_assert_array ($item);


				/** @var Df_Core_Model_Money $price */
				$price = df_a ($item, 'price');

				df_assert ($price instanceof Df_Core_Model_Money);


				/** @var int $qty  */
				$qty = df_a ($item, 'qty');

				df_assert_integer ($qty);


				$resultAsInteger += ($qty * $price->getAsInteger());

			}



			$result =
				df_model (
					Df_Core_Model_Money::getNameInMagentoFormat()
					,
					array (
						Df_Core_Model_Money::PARAM__AMOUNT => $resultAsInteger
					)
				)
			;


			df_assert ($result instanceof Df_Core_Model_Money);

			$this->_calculatedAmountSubtotal = $result;

		}

		df_assert ($this->_calculatedAmountSubtotal instanceof Df_Core_Model_Money);

		return $this->_calculatedAmountSubtotal;

	}


	/**
	* @var Df_Core_Model_Money
	*/
	private $_calculatedAmountSubtotal;		
	
	
	



	/**
	 * @return Df_Core_Model_Money
	 */
	private function getCalculatedAmountTotal () {

		if (!isset ($this->_calculatedAmountTotal)) {

			/** @var Df_Core_Model_Money $result  */
			$result =
				df_model (
					Df_Core_Model_Money::getNameInMagentoFormat()
					,
					array (
						Df_Core_Model_Money::PARAM__AMOUNT =>
								$this->getCalculatedAmountSubtotal()->getAsInteger()
							+
								$this->getAmountShipping()->getAsInteger()
							+
								$this->getAmountTax()->getAsInteger()
							-
								$this->getAmountDiscount()->getAsInteger()
					)
				)
			;

			df_assert ($result instanceof Df_Core_Model_Money);

			$this->_calculatedAmountTotal = $result;

		}


		df_assert ($this->_calculatedAmountTotal instanceof Df_Core_Model_Money);

		return $this->_calculatedAmountTotal;

	}


	/**
	* @var Df_Core_Model_Money
	*/
	private $_calculatedAmountTotal;
	
	
	
	
	
	/**
	 * @return Df_Core_Model_Money
	 */
	private function getCalculatedAmountTotalCorrected () {
	
		if (!isset ($this->_calculatedAmountTotalCorrected)) {
	
			/** @var Df_Core_Model_Money $result  */
			$result = 
				df_model (
					Df_Core_Model_Money::getNameInMagentoFormat()
					,
					array (
						Df_Core_Model_Money::PARAM__AMOUNT =>
								$this->getCalculatedAmountSubtotal()->getAsInteger()
							+
								$this->getAmountShipping()->getAsInteger()
							+
								$this->getAmountTaxCorrected()->getAsInteger()
							-
								$this->getAmountDiscountCorrected()->getAsInteger()
					)
				)
			;
	
	
			df_assert ($result instanceof Df_Core_Model_Money);
	
			$this->_calculatedAmountTotalCorrected = $result;
	
		}
	
	
		df_assert ($this->_calculatedAmountTotalCorrected instanceof Df_Core_Model_Money);
	
		return $this->_calculatedAmountTotalCorrected;
	
	}
	
	
	/**
	* @var Df_Core_Model_Money
	*/
	private $_calculatedAmountTotalCorrected;	




	
	
	/**
	 * @return Df_Core_Model_Money
	 */
	private function getAmountDelta () {
	
		if (!isset ($this->_amountDelta)) {
	
			/** @var Df_Core_Model_Money $result  */
			$result = 
				df_model (
					Df_Core_Model_Money::getNameInMagentoFormat()
					,
					array (
						Df_Core_Model_Money::PARAM__AMOUNT =>
								$this->getAmount()->getAsInteger()
							-
								$this->getCalculatedAmountTotal()->getAsInteger()
					)
				)
			;

			df_assert ($result instanceof Df_Core_Model_Money);


			if ($result->getAsInteger() !== Df_Core_Model_Money::getZero()->getAsInteger()) {

				df_log (
					sprintf (
						"Рассчитанная вручную сумма заказа не соответствует рассчитанной Magento.
						\nСумма, рассчитанная вручную: %s
						\nСумма, рассчитанная Magento: %s
						\nМодуль «WEBPAY» уравняет эти суммы посредством изменения налога или скидки.
						"
						,
						$this->getCalculatedAmountTotal()->getAsInteger()
						,
						$this->getAmount()->getAsInteger()
					)
				);

			}

	
			$this->_amountDelta = $result;
	
		}
	
	
		df_assert ($this->_amountDelta instanceof Df_Core_Model_Money);
	
		return $this->_amountDelta;
	
	}
	
	
	/**
	* @var Df_Core_Model_Money
	*/
	private $_amountDelta;	
	

	
	
	
	
	/**
	 * @return string
	 */
	private function getOpenKey () {
	
		if (!isset ($this->_openKey)) {
	
			/** @var string $result  */
			$result = 
				uniqid ()
			;
	
	
			df_assert_string ($result);
	
			$this->_openKey = $result;
	
		}
	
	
		df_result_string ($this->_openKey);
	
		return $this->_openKey;
	
	}
	
	
	/**
	* @var string
	*/
	private $_openKey;





	/**
	 * @return Mage_Sales_Model_Resource_Order_Item_Collection|Mage_Sales_Model_Mysql4_Order_Item_Collection
	 */
	private function getOrderItems () {

		/** @var Mage_Sales_Model_Resource_Order_Item_Collection|Mage_Sales_Model_Mysql4_Order_Item_Collection $result  */
		$result = $this->getOrder()->getItemsCollection();

		return $result;

	}





	/**
	 * @return array
	 */
	private function getOrderItemNames () {

		if (!isset ($this->_orderItemNames)) {

			/** @var array $result  */
			$result = $this->getOrderItems ()->getColumnValues ('name');

			df_assert_array ($result);

			$this->_orderItemNames = $result;

		}


		df_result_array ($this->_orderItemNames);

		return $this->_orderItemNames;

	}


	/**
	* @var array
	*/
	private $_orderItemNames;





	/**
	 * @return array
	 */
	private function getOrderItemPrices () {

		if (!isset ($this->_orderItemPrices)) {

			/** @var array $result  */
			$result =
				array_map (
					array ($this, 'convertAmountFromOrderCurrencyToServiceCurrency')
					,
					$this->getOrderItems ()->getColumnValues ('price')
				)
			;


			df_assert_array ($result);

			$this->_orderItemPrices = $result;

		}


		df_result_array ($this->_orderItemPrices);

		return $this->_orderItemPrices;

	}


	/**
	* @var array
	*/
	private $_orderItemPrices;





	/**
	 * @return array
	 */
	private function getOrderItemQtys () {

		if (!isset ($this->_orderItemQtys)) {

			/** @var array $result  */
			$result =
				array_map (
					'intval'
					,
					$this->getOrderItems ()->getColumnValues ('qty_ordered')
				)
			;

			df_assert_array ($result);

			$this->_orderItemQtys = $result;

		}


		df_result_array ($this->_orderItemQtys);

		return $this->_orderItemQtys;

	}


	/**
	* @var array
	*/
	private $_orderItemQtys;






	/**
	 * @return string
	 */
	private function getSignature () {

		/** @var string $result */
		$result =
			sha1 (
				implode (
					Df_Core_Const::T_EMPTY
					,
					$this->preprocessParams (
						array (
							self::REQUEST_VAR__OPEN_KEY => $this->getOpenKey()

							,
							self::REQUEST_VAR__SHOP_ID => $this->getServiceConfig()->getShopId()

							,
							self::REQUEST_VAR__ORDER_NUMBER => $this->getOrder()->getIncrementId()

							,
							self::REQUEST_VAR__REQUEST__TEST_MODE => $this->isTestMode ()

							,
							self::REQUEST_VAR__ORDER_CURRENCY =>
								$this->getServiceConfig()->getCurrencyCodeInServiceFormat()

							,
							self::REQUEST_VAR__ORDER_AMOUNT => $this->getAmount()->getAsInteger()

							,
							self::SIGNATURE_PARAM__ENCRYPTION_KEY =>
								$this->getServiceConfig()->getResponsePassword()

						)
					)
				)
			)
		;

		df_result_string ($result);

		return $result;
	}
	
	



	/**
	 * Документация WEBPAY:
	 *
	 * «Оплата не будет произведена,
	 * если wsb_total и посчитанное значения товаров не будут совпадать.
	 * Покупателю будет отображена ошибка.»
	 *
	 * Выявляем эту проблему на максимально ранней стадии.
	 *
	 * @return Df_WebPay_Model_Request_Payment
	 */
	private function verifyAmount () {

		/** @var Df_Core_Model_Money $calculatedAmountTotalCorrected  */
		$calculatedAmountTotalCorrected =
			df_model (
				Df_Core_Model_Money::getNameInMagentoFormat()
				,
				array (
					Df_Core_Model_Money::PARAM__AMOUNT =>
							$this->getCalculatedAmountSubtotal()->getAsInteger()
						+
							$this->getAmountShipping()->getAsInteger()
						+
							$this->getAmountTaxCorrected()->getAsInteger()
						-
							$this->getAmountDiscountCorrected()->getAsInteger()
				)
			)
		;

		df_assert ($calculatedAmountTotalCorrected instanceof Df_Core_Model_Money);


		/** @var Df_Core_Model_Money $deltaCorrected  */
		$deltaCorrected =
			df_model (
				Df_Core_Model_Money::getNameInMagentoFormat()
				,
				array (
					Df_Core_Model_Money::PARAM__AMOUNT =>
							$this->getAmount()->getAsInteger()
						-
							$this->getCalculatedAmountTotalCorrected()->getAsInteger()
				)
			)
		;

		df_assert ($deltaCorrected instanceof Df_Core_Model_Money);


		if ($deltaCorrected->getAsInteger() !== Df_Core_Model_Money::getZero()->getAsInteger()) {

			df_log (
				sprintf (
					"Рассчитанная вручную сумма заказа
					даже после корректировки не соответствует рассчитанной Magento.
					\nЭто — ошибка программиста.
					\nСумма, рассчитанная вручную: %s
					\nСумма, рассчитанная Magento: %s
					\nМодуль «WEBPAY» уравняет эти суммы посредством изменения налога или скидки.
					"
					,
					$calculatedAmountTotalCorrected->getAsInteger()
					,
					$this->getAmount()->getAsInteger()
				)
			);

		}


		return $this;

	}





	/**
	 * @return int
	 */
	private function isTestMode () {

		if (!isset ($this->_testMode)) {

			/** @var int $result  */
			$result =
				intval (
						$this->getServiceConfig()->isTestMode ()
					||
						$this->getServiceConfig()->isTestModeOnProduction()
				)
			;

			df_assert_integer ($result);

			$this->_testMode = $result;

		}


		df_result_integer ($this->_testMode);

		return $this->_testMode;

	}


	/**
	* @var int
	*/
	private $_testMode;





	const REQUEST_VAR__SHOP_ID = 'wsb_storeid';
	const REQUEST_VAR__SHOP_NAME = 'wsb_store';
	const REQUEST_VAR__ORDER_COMMENT = 'wsb_store';
	const REQUEST_VAR__ORDER_NUMBER = 'wsb_order_num';
	const REQUEST_VAR__ORDER_CURRENCY = 'wsb_currency_id';
	const REQUEST_VAR__PAYMENT_SERVICE__LANGUAGE = 'wsb_language_id';
	const REQUEST_VAR__OPEN_KEY = 'wsb_seed';
	const REQUEST_VAR__SIGNATURE = 'wsb_signature';
	const REQUEST_VAR__REQUEST__TEST_MODE = 'wsb_test';

	const REQUEST_VAR__URL_RETURN_OK = 'wsb_return_url';
	const REQUEST_VAR__URL_RETURN_NO = 'wsb_cancel_return_url';
	const REQUEST_VAR__URL_CONFIRM = 'wsb_notify_url';

	const REQUEST_VAR__ORDER_AMOUNT = 'wsb_total';


	const REQUEST_VAR__CUSTOMER__EMAIL = 'wsb_email';
	const REQUEST_VAR__CUSTOMER__PHONE = 'wsb_phone';








	const PAYMENT_MODE__FIX = 'fix';



	const REQUEST_VAR__PAYMENT_SERVICE__IS_FEE_PAYED_BY_SHOP = 'price_final';



	const REQUEST_VAR__PAYMENT_SERVICE__NEED_CONVERT_RECEIPTS = 'convert';


	const REQUEST_VAR__PAYMENT_SERVICE__PAYMENT_MODE = 'pay_mode';




	const SIGNATURE_PARAM__ENCRYPTION_KEY = 'encryption_key';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WebPay_Model_Request_Payment';
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


