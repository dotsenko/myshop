<?php


class Df_Eav_Helper_Assert extends Mage_Core_Helper_Abstract {

	
	
	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return Df_Eav_Helper_Assert
	 */
	public function entityAttributeCollection (Varien_Data_Collection_Db $collection) {

			df_assert (
				df_helper()->eav()->check()->entityAttributeCollection ($collection)
			)
		;

		return $this;

	}




	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return Df_Eav_Helper_Assert
	 */
	public function entityAttributeSetCollection (Varien_Data_Collection_Db $collection) {

			df_assert (
				df_helper()->eav()->check()->entityAttributeSetCollection ($collection)
			)
		;

		return $this;

	}




	/**
	 * @var Varien_Data_Collection_Db $collection
	 * @return Df_Eav_Helper_Assert
	 */
	public function entityAttributeOptionCollection (Varien_Data_Collection_Db $collection) {

			df_assert (
				df_helper()->eav()->check()->entityAttributeOptionCollection ($collection)
			)
		;

		return $this;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Eav_Helper_Assert';
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