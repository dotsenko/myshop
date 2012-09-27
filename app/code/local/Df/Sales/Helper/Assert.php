<?php


class Df_Sales_Helper_Assert extends Mage_Core_Helper_Abstract {



	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return Df_Sales_Helper_Assert
	 */
	public function orderCollection (Varien_Data_Collection_Db $collection) {

			df_assert (
				df_helper()->sales()->check()->orderCollection ($collection)
			)
		;

		return $this;

	}



	
	
	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return Df_Sales_Helper_Assert
	 */
	public function orderGridCollection (Varien_Data_Collection_Db $collection) {

			df_assert (
				df_helper()->sales()->check()->orderGridCollection ($collection)
			)
		;

		return $this;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Helper_Assert';
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