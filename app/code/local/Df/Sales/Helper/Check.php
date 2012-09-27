<?php


class Df_Sales_Helper_Check extends Mage_Core_Helper_Abstract {




	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return bool
	 */
	public function orderCollection (Varien_Data_Collection_Db $collection) {

		/** @var bool $result  */
		$result =
				@class_exists ('Mage_Sales_Model_Resource_Order_Collection')
			?
				(
						$collection
					instanceof
							Mage_Sales_Model_Resource_Order_Collection
				)
			:
				(
						$collection
					instanceof
							Mage_Sales_Model_Mysql4_Order_Collection
				)
		;

		df_result_boolean ($result);

		return $result;

	}






	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return bool
	 */
	public function orderGridCollection (Varien_Data_Collection_Db $collection) {

		/** @var bool $result  */
		$result =
				@class_exists ('Mage_Sales_Model_Resource_Order_Grid_Collection')
			?
				(
						$collection
					instanceof
						Mage_Sales_Model_Resource_Order_Grid_Collection
				)
			:
				(
						$collection
					instanceof
						Mage_Sales_Model_Mysql4_Order_Collection
				)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Helper_Check';
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