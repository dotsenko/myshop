<?php


class Df_Customer_Helper_Check extends Mage_Core_Helper_Abstract {




	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return bool
	 */
	public function formAttributeCollection (Varien_Data_Collection_Db $collection) {


		/** @var bool $result  */
		$result =
				@class_exists ('Mage_Customer_Model_Resource_Form_Attribute_Collection')
			?
				(
						$collection
					instanceof
						Mage_Customer_Model_Resource_Form_Attribute_Collection
				)
			:
				(
						$collection
					instanceof
						Mage_Customer_Model_Entity_Form_Attribute_Collection
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
		return 'Df_Customer_Helper_Check';
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