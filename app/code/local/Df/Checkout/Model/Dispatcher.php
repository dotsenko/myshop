<?php


class Df_Checkout_Model_Dispatcher extends Df_Core_Model_Abstract {



	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function checkout_type_multishipping_create_orders_single (
		Varien_Event_Observer $observer
	) {

		try {

			df_handle_event (
				Df_Checkout_Model_Handler_SaveOrderComment
					::getNameInMagentoFormat ()
				,
				Df_Checkout_Model_Event_CheckoutTypeMultishipping_CreateOrdersSingle
					::getNameInMagentoFormat ()
				,
				$observer
			);

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}





	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function checkout_type_onepage_save_order (
		Varien_Event_Observer $observer
	) {

		try {

			df_handle_event (
				Df_Checkout_Model_Handler_SaveOrderComment
					::getNameInMagentoFormat ()
				,
				Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrder
					::getNameInMagentoFormat ()
				,
				$observer
			);

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}




	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function checkout_type_onepage_save_order_after (
		Varien_Event_Observer $observer
	) {

		try {

			df_handle_event (
				Df_Checkout_Model_Handler_SendGeneratedPasswordToTheCustomer
					::getNameInMagentoFormat ()
				,
				Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrderAfter
					::getNameInMagentoFormat ()
				,
				$observer
			);

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}








	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Dispatcher';
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


