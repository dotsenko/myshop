<?php


/**
 * Возвращает коллекцию товаров-элементов
 * для данной коллекции товаров системного типа Configurable
 */
class Df_Catalog_Model_Filter_Product_Collection_Configurable_Dependent
	extends Df_Core_Model_Abstract
	implements Zend_Filter_Interface {



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Filter_Product_Collection_Configurable_Dependent';
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
     *
     * @param  mixed $value
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return Df_Varien_Data_Collection
     */
    public function filter ($value) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_collection ($value, Df_Catalog_Const::PRODUCT_CLASS, 0);
		/*************************************/

		/** @var array|Traversable $value */


		$result = new Df_Varien_Data_Collection ();
		/** @var Df_Varien_Data_Collection $result  */


		$filterDependent =
			df_model (
				Df_Catalog_Model_Filter_Product_Configurable_Dependent::getNameInMagentoFormat()
			)
		;
		/** @var Df_Catalog_Model_Filter_Product_Configurable_Dependent $filterDependent */


		foreach ($value as $product) {
			/** @var Mage_Catalog_Model_Product $product */

			df_assert ($product instanceof Mage_Catalog_Model_Product);

			$result
				->addItems (
					$filterDependent->filter ($product)
				)
			;

		}


		df_result_collection ($result, Df_Catalog_Const::PRODUCT_CLASS);
		df_assert ($result instanceof Df_Varien_Data_Collection);

		return $result;
	}


}


