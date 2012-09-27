<?php


class Df_Sales_Model_Order extends Mage_Sales_Model_Order {


	/**
	 * @param float $price
	 * @param bool $addBrackets
	 * @return string
	 */
    public function formatPrice($price, $addBrackets = false) {
        return
				(
						df_enabled(Df_Core_Feature::LOCALIZATION)
					&&
						df_area (
							df_cfg ()->localization ()->translation()->frontend()->needHideDecimals()
							,
							df_cfg ()->localization ()->translation()->admin()->needHideDecimals()
						)
				)
			?
				$this->formatPriceDf ($price, $addBrackets)
		    :
				parent::formatPrice ($price, $addBrackets)
		;
    }



	/**
	 * @override
	 * @return string|null
	 */
	public function getEmailCustomerNote () {

		/** @var string|null $result  */
		$result = parent::getEmailCustomerNote();

		if (
				df_enabled (Df_Core_Feature::SALES)
			&&
				df_cfg()->sales()->orderComments()->preserveLineBreaksInOrderEmail()
			&&
				!df_empty ($result)
		) {
			$result = nl2br ($result);

			if (df_cfg()->sales()->orderComments()->wrapInStandardFrameInOrderEmail()) {

				$result =
					df_mage()->core()->layout()
						->createBlock (
							Df_Sales_Block_Order_Email_Comments::getNameInMagentoFormat()
							,
							null
							,
							array (
								Df_Sales_Block_Order_Email_Comments::PARAM__COMMENTS => $result
							)
						)
						->toHtml()
				;

			}
		}

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}




    /**
     * Processing object before save data
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave() {

		/** @var string|null $protectCode  */
        $protectCode =
			$this
				->getData (
					Df_Sales_Const::ORDER_PARAM__PROTECT_CODE
				)
		;

		if (!is_null ($protectCode)) {
			df_assert_string ($protectCode);
		}


		parent::_beforeSave();


		if (!is_null ($protectCode)) {
			$this
				->setData (
					Df_Sales_Const::ORDER_PARAM__PROTECT_CODE
					,
					$protectCode
				)
			;
		}

		return $this;
    }




	/**
	 * @param float $price
	 * @param bool $addBrackets
	 * @return string
	 */
    private function formatPriceDf ($price, $addBrackets = false) {
        return
			$this->formatPricePrecision(
				$price
				,
				df_helper()->directory()->currency()->getPrecision ()
				,
				$addBrackets
			)
		;
    }


	const PARAM__ADJUSTMENT_NEGATIVE = 'adjustment_negative';
	const PARAM__ADJUSTMENT_POSITIVE = 'adjustment_positive';
	const PARAM__APPLIED_RULE_IDS = 'applied_rule_ids';
	const PARAM__BASE_ADJUSTMENT_NEGATIVE = 'base_adjustment_negative';
	const PARAM__BASE_ADJUSTMENT_POSITIVE = 'base_adjustment_positive';
	const PARAM__BASE_CURRENCY_CODE = 'base_currency_code';
	const PARAM__BASE_CUSTOMER_BALANCE_AMOUNT = 'base_customer_balance_amount';
	const PARAM__BASE_CUSTOMER_BALANCE_INVOICED = 'base_customer_balance_invoiced';
	const PARAM__BASE_CUSTOMER_BALANCE_REFUNDED = 'base_customer_balance_refunded';
	const PARAM__BASE_CUSTOMER_BALANCE_TOTAL_REFUNDED = 'base_customer_balance_total_refunded';
	const PARAM__BASE_DISCOUNT_AMOUNT = 'base_discount_amount';
	const PARAM__BASE_DISCOUNT_CANCELED = 'base_discount_canceled';
	const PARAM__BASE_DISCOUNT_INVOICED = 'base_discount_invoiced';
	const PARAM__BASE_DISCOUNT_REFUNDED = 'base_discount_refunded';
	const PARAM__BASE_GRAND_TOTAL = 'base_grand_total';
	const PARAM__BASE_HIDDEN_TAX_AMOUNT = 'base_hidden_tax_amount';
	const PARAM__BASE_HIDDEN_TAX_INVOICED = 'base_hidden_tax_invoiced';
	const PARAM__BASE_HIDDEN_TAX_REFUNDED = 'base_hidden_tax_refunded';
	const PARAM__BASE_REWARD_CURRENCY_AMOUNT = 'base_reward_currency_amount';
	const PARAM__BASE_REWARD_CURRENCY_AMOUNT_INVOICED = 'base_reward_currency_amount_invoiced';
	const PARAM__BASE_REWARD_CURRENCY_AMOUNT_REFUNDED = 'base_reward_currency_amount_refunded';
	const PARAM__BASE_SHIPPING_AMOUNT = 'base_shipping_amount';
	const PARAM__BASE_SHIPPING_CANCELED = 'base_shipping_canceled';
	const PARAM__BASE_SHIPPING_DISCOUNT_AMOUNT = 'base_shipping_discount_amount';
	const PARAM__BASE_SHIPPING_HIDDEN_TAX_AMNT = 'base_shipping_hidden_tax_amnt';
	const PARAM__BASE_SHIPPING_INCL_TAX = 'base_shipping_incl_tax';
	const PARAM__BASE_SHIPPING_INVOICED = 'base_shipping_invoiced';
	const PARAM__BASE_SHIPPING_REFUNDED = 'base_shipping_refunded';
	const PARAM__BASE_SHIPPING_TAX_AMOUNT = 'base_shipping_tax_amount';
	const PARAM__BASE_SHIPPING_TAX_REFUNDED = 'base_shipping_tax_refunded';
	const PARAM__BASE_SUBTOTAL = 'base_subtotal';
	const PARAM__BASE_SUBTOTAL_CANCELED = 'base_subtotal_canceled';
	const PARAM__BASE_SUBTOTAL_INCL_TAX = 'base_subtotal_incl_tax';
	const PARAM__BASE_SUBTOTAL_INVOICED = 'base_subtotal_invoiced';
	const PARAM__BASE_SUBTOTAL_REFUNDED = 'base_subtotal_refunded';
	const PARAM__BASE_TAX_AMOUNT = 'base_tax_amount';
	const PARAM__BASE_TAX_CANCELED = 'base_tax_canceled';
	const PARAM__BASE_TAX_INVOICED = 'base_tax_invoiced';
	const PARAM__BASE_TAX_REFUNDED = 'base_tax_refunded';
	const PARAM__BASE_TO_GLOBAL_RATE = 'base_to_global_rate';
	const PARAM__BASE_TO_ORDER_RATE = 'base_to_order_rate';
	const PARAM__BASE_TOTAL_CANCELED = 'base_total_canceled';
	const PARAM__BASE_TOTAL_DUE = 'base_total_due';
	const PARAM__BASE_TOTAL_INVOICED = 'base_total_invoiced';
	const PARAM__BASE_TOTAL_INVOICED_COST = 'base_total_invoiced_cost';
	const PARAM__BASE_TOTAL_OFFLINE_REFUNDED = 'base_total_offline_refunded';
	const PARAM__BASE_TOTAL_ONLINE_REFUNDED = 'base_total_online_refunded';
	const PARAM__BASE_TOTAL_PAID = 'base_total_paid';
	const PARAM__BASE_TOTAL_QTY_ORDERED = 'base_total_qty_ordered';
	const PARAM__BASE_TOTAL_REFUNDED = 'base_total_refunded';
	const PARAM__BILLING_ADDRESS_ID = 'billing_address_id';
	const PARAM__CAN_SHIP_PARTIALLY = 'can_ship_partially';
	const PARAM__CAN_SHIP_PARTIALLY_ITEM = 'can_ship_partially_item';
	const PARAM__COUPON_CODE = 'coupon_code';
	const PARAM__COUPON_RULE_NAME = 'coupon_rule_name';
	const PARAM__CREATED_AT = 'created_at';
	const PARAM__CUSTOMER_BALANCE_AMOUNT = 'customer_balance_amount';
	const PARAM__CUSTOMER_BALANCE_INVOICED = 'customer_balance_invoiced';
	const PARAM__CUSTOMER_BALANCE_REFUNDED = 'customer_balance_refunded';
	const PARAM__CUSTOMER_BALANCE_TOTAL_REFUNDED = 'customer_balance_total_refunded';
	const PARAM__CUSTOMER_DOB = 'customer_dob';
	const PARAM__CUSTOMER_EMAIL = 'customer_email';
	const PARAM__CUSTOMER_FIRSTNAME = 'customer_firstname';
	const PARAM__CUSTOMER_GENDER = 'customer_gender';
	const PARAM__CUSTOMER_GROUP_ID = 'customer_group_id';
	const PARAM__CUSTOMER_ID = 'customer_id';
	const PARAM__CUSTOMER_IS_GUEST = 'customer_is_guest';
	const PARAM__CUSTOMER_LASTNAME = 'customer_lastname';
	const PARAM__CUSTOMER_MIDDLENAME = 'customer_middlename';
	const PARAM__CUSTOMER_NOTE = 'customer_note';
	const PARAM__CUSTOMER_NOTE_NOTIFY = 'customer_note_notify';
	const PARAM__CUSTOMER_PREFIX = 'customer_prefix';
	const PARAM__CUSTOMER_SUFFIX = 'customer_suffix';
	const PARAM__CUSTOMER_TAXVAT = 'customer_taxvat';
	const PARAM__DISCOUNT_AMOUNT = 'discount_amount';
	const PARAM__DISCOUNT_CANCELED = 'discount_canceled';
	const PARAM__DISCOUNT_DESCRIPTION = 'discount_description';
	const PARAM__DISCOUNT_INVOICED = 'discount_invoiced';
	const PARAM__DISCOUNT_REFUNDED = 'discount_refunded';
	const PARAM__EDIT_INCREMENT = 'edit_increment';
	const PARAM__EMAIL_SENT = 'email_sent';
	const PARAM__ENTITY_ID = 'entity_id';
	const PARAM__EXT_CUSTOMER_ID = 'ext_customer_id';
	const PARAM__EXT_ORDER_ID = 'ext_order_id';
	const PARAM__FORCED_SHIPMENT_WITH_INVOICE = 'forced_shipment_with_invoice';
	const PARAM__GIFT_MESSAGE_ID = 'gift_message_id';
	const PARAM__GLOBAL_CURRENCY_CODE = 'global_currency_code';
	const PARAM__GRAND_TOTAL = 'grand_total';
	const PARAM__HIDDEN_TAX_AMOUNT = 'hidden_tax_amount';
	const PARAM__HIDDEN_TAX_INVOICED = 'hidden_tax_invoiced';
	const PARAM__HIDDEN_TAX_REFUNDED = 'hidden_tax_refunded';
	const PARAM__HOLD_BEFORE_STATE = 'hold_before_state';
	const PARAM__HOLD_BEFORE_STATUS = 'hold_before_status';
	const PARAM__INCREMENT_ID = 'increment_id';
	const PARAM__IS_VIRTUAL = 'is_virtual';
	const PARAM__ORDER_CURRENCY_CODE = 'order_currency_code';
	const PARAM__ORIGINAL_INCREMENT_ID = 'original_increment_id';
	const PARAM__PAYMENT_AUTH_EXPIRATION = 'payment_auth_expiration';
	const PARAM__PAYMENT_AUTHORIZATION_AMOUNT = 'payment_authorization_amount';
	const PARAM__PAYPAL_IPN_CUSTOMER_NOTIFIED = 'paypal_ipn_customer_notified';
	const PARAM__PROTECT_CODE = 'protect_code';
	const PARAM__QUOTE_ADDRESS_ID = 'quote_address_id';
	const PARAM__QUOTE_ID = 'quote_id';
	const PARAM__RELATION_CHILD_ID = 'relation_child_id';
	const PARAM__RELATION_CHILD_REAL_ID = 'relation_child_real_id';
	const PARAM__RELATION_PARENT_ID = 'relation_parent_id';
	const PARAM__RELATION_PARENT_REAL_ID = 'relation_parent_real_id';
	const PARAM__REMOTE_IP = 'remote_ip';
	const PARAM__REWARD_CURRENCY_AMOUNT = 'reward_currency_amount';
	const PARAM__REWARD_CURRENCY_AMOUNT_INVOICED = 'reward_currency_amount_invoiced';
	const PARAM__REWARD_CURRENCY_AMOUNT_REFUNDED = 'reward_currency_amount_refunded';
	const PARAM__REWARD_POINTS_BALANCE = 'reward_points_balance';
	const PARAM__REWARD_POINTS_BALANCE_REFUNDED = 'reward_points_balance_refunded';
	const PARAM__REWARD_POINTS_BALANCE_TO_REFUND = 'reward_points_balance_to_refund';
	const PARAM__REWARD_SALESRULE_POINTS = 'reward_salesrule_points';
	const PARAM__SHIPPING_ADDRESS_ID = 'shipping_address_id';
	const PARAM__SHIPPING_AMOUNT = 'shipping_amount';
	const PARAM__SHIPPING_CANCELED = 'shipping_canceled';
	const PARAM__SHIPPING_DESCRIPTION = 'shipping_description';
	const PARAM__SHIPPING_DISCOUNT_AMOUNT = 'shipping_discount_amount';
	const PARAM__SHIPPING_HIDDEN_TAX_AMOUNT = 'shipping_hidden_tax_amount';
	const PARAM__SHIPPING_INCL_TAX = 'shipping_incl_tax';
	const PARAM__SHIPPING_INVOICED = 'shipping_invoiced';
	const PARAM__SHIPPING_METHOD = 'shipping_method';
	const PARAM__SHIPPING_REFUNDED = 'shipping_refunded';
	const PARAM__SHIPPING_TAX_AMOUNT = 'shipping_tax_amount';
	const PARAM__SHIPPING_TAX_REFUNDED = 'shipping_tax_refunded';
	const PARAM__STATE = 'state';
	const PARAM__STATUS = 'status';
	const PARAM__STORE_CURRENCY_CODE = 'store_currency_code';
	const PARAM__STORE_ID = 'store_id';
	const PARAM__STORE_NAME = 'store_name';
	const PARAM__STORE_TO_BASE_RATE = 'store_to_base_rate';
	const PARAM__STORE_TO_ORDER_RATE = 'store_to_order_rate';
	const PARAM__SUBTOTAL = 'subtotal';
	const PARAM__SUBTOTAL_CANCELED = 'subtotal_canceled';
	const PARAM__SUBTOTAL_INCL_TAX = 'subtotal_incl_tax';
	const PARAM__SUBTOTAL_INVOICED = 'subtotal_invoiced';
	const PARAM__SUBTOTAL_REFUNDED = 'subtotal_refunded';
	const PARAM__TAX_AMOUNT = 'tax_amount';
	const PARAM__TAX_CANCELED = 'tax_canceled';
	const PARAM__TAX_INVOICED = 'tax_invoiced';
	const PARAM__TAX_REFUNDED = 'tax_refunded';
	const PARAM__TOTAL_CANCELED = 'total_canceled';
	const PARAM__TOTAL_DUE = 'total_due';
	const PARAM__TOTAL_INVOICED = 'total_invoiced';
	const PARAM__TOTAL_ITEM_COUNT = 'total_item_count';
	const PARAM__TOTAL_OFFLINE_REFUNDED = 'total_offline_refunded';
	const PARAM__TOTAL_ONLINE_REFUNDED = 'total_online_refunded';
	const PARAM__TOTAL_PAID = 'total_paid';
	const PARAM__TOTAL_QTY_ORDERED = 'total_qty_ordered';
	const PARAM__TOTAL_REFUNDED = 'total_refunded';
	const PARAM__UPDATED_AT = 'updated_at';
	const PARAM__WEIGHT = 'weight';
	const PARAM__X_FORWARDED_FOR ='x_forwarded_for';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Order';
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
