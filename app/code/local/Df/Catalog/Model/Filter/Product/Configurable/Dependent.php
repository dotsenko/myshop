<?php


/**
 * Возвращает коллекцию товаров-элементов
 * для данного товара системного типа Configurable
 */
class Df_Catalog_Model_Filter_Product_Configurable_Dependent
	extends Df_Core_Model_Abstract
	implements Zend_Filter_Interface {






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Filter_Product_Configurable_Dependent';
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

		df_assert ($value instanceof Mage_Catalog_Model_Product);
		/** @var Mage_Catalog_Model_Product $value */

		$dependentProducts =
			$this->getConfigurableTypeInstance()
				->getUsedProducts (null, $value)
		;
		/** @var array $dependentProducts */


		$result = Df_Varien_Data_Collection::createFromCollection ($dependentProducts);
		/** @var Df_Varien_Data_Collection $result  */


		df_result_collection ($result, Df_Catalog_Const::PRODUCT_CLASS);
		df_assert ($result instanceof Df_Varien_Data_Collection);

		return $result;
	}




	/**
	 * @return Mage_Catalog_Model_Product_Type_Configurable
	 */
	private function getConfigurableTypeInstance () {
		if (!isset ($this->_configurableTypeInstance)) {
			$result = df_model (Df_Catalog_Const::PRODUCT_TYPE_CONFIGURABLE_CLASS_MF);

			df_assert ($result instanceof Mage_Catalog_Model_Product_Type_Configurable);

			$this->_configurableTypeInstance = $result;
		}

		return $this->_configurableTypeInstance;
	}


	/**
	 * @var Mage_Catalog_Model_Product_Type_Configurable
	 */
	private $_configurableTypeInstance;


}


