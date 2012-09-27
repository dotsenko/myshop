<?php


/**
 * Удаляет из коллекции товары, которые являются составными элементами настраиваемого товара
 */
class Df_Catalog_Model_Filter_Product_Collection_DependentProductRemover
	extends Df_Core_Model_Abstract
	implements Zend_Filter_Interface {





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Filter_Product_Collection_DependentProductRemover';
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
	 * Удаляет из коллекции товары, которые являются составными элементами настраиваемого товара
	 *
     * @param  array|Traversable $value
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return Df_Catalog_Model_Product_Collection
     */
    public function filter ($value) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_collection ($value, Df_Catalog_Const::PRODUCT_CLASS, 0);
		/*************************************/

		/** @var array|Traversable $value */


		$result =
			Df_Varien_Data_Collection::createFromCollection(
				$value
				,
				Df_Catalog_Model_Product_Collection::getClass()
			)
				->subtract (
					$this->getRejectingFilter ()->filter ($value)
				)
			;
		;
		/** @var Df_Catalog_Model_Product_Collection $result */

		df_assert ($result instanceof Df_Catalog_Model_Product_Collection);

		return $result;
	}





	/**
	 * @return Zend_Filter_Interface
	 */
	public function getRejectingFilter () {

		if (!$this->_rejectingFilter) {


			$filterConfigurable =
				df_model (Df_Catalog_Model_Filter_Product_Collection_Configurable::getNameInMagentoFormat())
			;
			/** @var Df_Catalog_Model_Filter_Product_Collection_Configurable $filterConfigurable  */



			$filterDependent =
				df_model (Df_Catalog_Model_Filter_Product_Collection_Configurable_Dependent::getNameInMagentoFormat())
			;
			/** @var Df_Catalog_Model_Filter_Product_Collection_Configurable_Dependent $filterDependent  */



			$filterInvisible =
				df_model (
					Df_Catalog_Model_Filter_Product_Collection_Visibility::getNameInMagentoFormat()
					,
					array (
						Df_Catalog_Model_Validate_Product_Visibility::VALID_VISIBILITY_STATES =>
							array (
								Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE
							)
					)
				)
			;
			/** @var Df_Catalog_Model_Filter_Product_Collection_Visibility $filterInvisible  */


			$result = new Zend_Filter ();
			/** @var Zend_Filter $itemsToRemove */


			$result
				/**
				 * Обратите внимание, что метод Zend_Filter::appendFilter()
				 * отсутствует в Zend Framework версии 1.9.6,
				 * которая входит в состав Magento 1.4.0.1
				 */
				->addFilter ($filterConfigurable)
				->addFilter ($filterDependent)
				/**
				 * На всякий случай добавляем проверку на невидимость в каталоге.
				 * Если товар-элемент настраиваемого товара виден в каталоге и как независимый товар,
				 * то его мы не удаляем из списка товаров-подарков.
				 */
				->addFilter ($filterInvisible)
			;

			df_assert ($result instanceof Zend_Filter_Interface);

			$this->_rejectingFilter = $result;

		}

		return $this->_rejectingFilter;
	}





	/**
	 * @var Zend_Filter_Interface
	 */
	private $_rejectingFilter;

}

