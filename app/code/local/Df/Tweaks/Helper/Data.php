<?php


class Df_Tweaks_Helper_Data extends Mage_Core_Helper_Abstract {


	
	
	/**
	 * @return Df_Tweaks_Helper_Config
	 */
	public function config () {

		/** @var Df_Tweaks_Helper_Config $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Config::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Config);

		return $result;

	}	
	




	/**
	 * @return Df_Tweaks_Helper_Customer
	 */
	public function customer () {

		/** @var Df_Tweaks_Helper_Customer $result  */
		$result =
			Mage::helper (Df_Tweaks_Helper_Customer::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Tweaks_Helper_Customer);

		return $result;

	}
	
	
	
	
	/**
	 * @return bool
	 */
	public function isItCatalogProductList () {
	
		if (!isset ($this->_itCatalogProductList)) {
	
			/** @var bool $result  */
			$result = 
					df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__CATEGORY_VIEW)
				||
					/**
					 * На случай вывода списка товаров через синтаксис {{}}:
					 * {{block type="catalog/product_list" column_count="4" category_id="6" template="catalog/product/list.phtml"}}
					 */
					df_handle_presents (Df_Cms_Const::LAYOUT_HANDLE__PAGE)
				||
					df_handle_presents (Df_CatalogSearch_Const::LAYOUT_HANDLE__RESULT_INDEX)
			;
	
	
			df_assert_boolean ($result);
	
			$this->_itCatalogProductList = $result;
	
		}
	
	
		df_result_boolean ($this->_itCatalogProductList);
	
		return $this->_itCatalogProductList;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_itCatalogProductList;		
	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Data';
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