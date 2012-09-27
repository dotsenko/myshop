<?php


/**
 * Оставляет в коллекции только товары системного типа Configurable
 */
class Df_Catalog_Model_Filter_Product_Collection_Configurable
	extends Df_Core_Model_Filter_Collection {


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Filter_Product_Collection_Configurable';
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






	/**
	 * Должна возвращать класс элементов коллекции
	 *
	 * @return string
	 */
	protected function getItemClass () {
		return Df_Catalog_Const::PRODUCT_CLASS;
	}



	/**
	 * @return Df_Catalog_Model_Validate_Product_Configurable
	 */
	protected function createValidator () {

		$result =
			df_model (
				Df_Catalog_Model_Validate_Product_Configurable::getNameInMagentoFormat()
			)
		;
		/** @var Df_Catalog_Model_Validate_Product_Configurable $validator */

		df_assert ($result instanceof Df_Catalog_Model_Validate_Product_Configurable);

		return $result;
	}

}


