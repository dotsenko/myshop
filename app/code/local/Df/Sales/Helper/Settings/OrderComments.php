<?php

class Df_Sales_Helper_Settings_OrderComments extends Df_Core_Helper_Settings {




	/**
	 * @return boolean
	 */
	public function adminOrderCreate_commentIsVisibleOnFront() {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_comments/admin_order_create__comment_is_visible_on_front'
			)
		;

		df_result_boolean ($result);

		return $result;
	}






	/**
	 * @return boolean
	 */
	public function preserveLineBreaksInAdminOrderView () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_comments/preserve_line_breaks_in_admin_order_view'
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @return boolean
	 */
	public function preserveLineBreaksInCustomerAccount () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_comments/preserve_line_breaks_in_customer_account'
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @return boolean
	 */
	public function preserveLineBreaksInOrderEmail () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_comments/preserve_line_breaks_in_order_email'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function wrapInStandardFrameInOrderEmail () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_comments/wrap_in_standard_frame_in_order_email'
			)
		;

		df_result_boolean ($result);

		return $result;
	}






	/**
	 * @return boolean
	 */
	public function preserveSomeTagsInAdminOrderView () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_comments/preserve_some_tags_in_admin_order_view'
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @return array
	 */
	public function getTagsToPreserveInAdminOrderView () {

		/** @var array $result  */
		$result =
			df_parse_csv (
				df_string (
					Mage::getStoreConfig (
						'df_sales/order_comments/tags_to_preserve_in_admin_order_view'
					)
				)
			)
		;

		df_result_array ($result);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Helper_Settings_OrderComments';
	}


	/**
	 * Например, для класса Df_checkoutRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_checkout_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()->getModelNameInMagentoFormat (
				self::getClass()
			)
		;
	}


}