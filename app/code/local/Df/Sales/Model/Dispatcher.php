<?php


class Df_Sales_Model_Dispatcher extends Df_Core_Model_Abstract {


	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function sales_convert_order_to_quote (
		Varien_Event_Observer $observer
	) {

		try {

			if (
					df_enabled (Df_Core_Feature::CHECKOUT)
				&&
					df_cfg()->checkout()->patches()->fixSalesConvertOrderToQuote()
			) {

				/** @var Mage_Sales_Model_Order $sourceOrder  */
				$sourceOrder = $observer->getData ('order');

				df_assert ($sourceOrder instanceof Mage_Sales_Model_Order);


				/** @var Mage_Sales_Model_Quote $targetQuote  */
				$targetQuote = $observer->getData ('quote');

				df_assert ($targetQuote instanceof Mage_Sales_Model_Quote);


				$targetQuote
					->addData (
						array (
							Df_Sales_Const::ORDER_PARAM__CUSTOMER_LASTNAME =>
								$sourceOrder->getData (
									Df_Sales_Const::ORDER_PARAM__CUSTOMER_LASTNAME
								)
							,
							Df_Sales_Const::ORDER_PARAM__CUSTOMER_FIRSTNAME =>
								$sourceOrder->getData (
									Df_Sales_Const::ORDER_PARAM__CUSTOMER_FIRSTNAME
								)
							,
							Df_Sales_Const::ORDER_PARAM__CUSTOMER_MIDDLENAME =>
								$sourceOrder->getData (
									Df_Sales_Const::ORDER_PARAM__CUSTOMER_MIDDLENAME
								)
						)
					)
				;


			}

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}




	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function df_adminhtml_block_sales_order_grid__prepare_collection (
		Varien_Event_Observer $observer
	) {

		try {

			df_handle_event (
				Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection
					::getNameInMagentoFormat ()
				,
				Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareCollection
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
	public function df_adminhtml_block_sales_order_grid__prepare_columns_after (
		Varien_Event_Observer $observer
	) {

		try {

			df_handle_event (
				Df_Sales_Model_Handler_AdminOrderGrid_AddProductColumn
					::getNameInMagentoFormat ()
				,
				Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareColumnsAfter
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
	public function sales_order_grid_collection_load_before (
		Varien_Event_Observer $observer
	) {

		try {

			df_handle_event (
				Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection
					::getNameInMagentoFormat ()
				,
				Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore
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
	public function sales_order_status_history_save_before (
		Varien_Event_Observer $observer
	) {

		try {

			df_handle_event (
				Df_Sales_Model_Handler_OrderStatusHistory_SetVisibleOnFrontParam
					::getNameInMagentoFormat ()
				,
				Df_Sales_Model_Event_OrderStatusHistory_SaveBefore
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
	public function sales_quote_address_save_before (Varien_Event_Observer $observer) {
		$quoteAddress = $observer->getData ('data_object');
		if ($quoteAddress) {
			/** @var Mage_Sales_Model_Quote_Address $quoteAddress */

			$street = $quoteAddress->getData (self::STREET);
			if ($street) {
				if (is_array ($street)) {
					$quoteAddress->setData (self::STREET, implode("\n", $street));
				}
			}
		}
	}


	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function core_copy_fieldset_customer_address_to_quote_address (Varien_Event_Observer $observer) {

		if (
				df_cfg()->checkout()->patches()->getFixAddress1501()
			&&
				df_enabled (Df_Core_Feature::CHECKOUT)
		) {

			$quoteAddress = $observer->getEvent()->getData ("target");
			/** @var Mage_Sales_Model_Quote_Address $quoteAddress */

			$street = $quoteAddress->getData (self::STREET);
			if ($street) {
				if (is_string ($street)) {
					$quoteAddress->setData (self::STREET, explode("n", $street));
				}
			}

		}

	}

	const STREET = 'street';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Dispatcher';
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