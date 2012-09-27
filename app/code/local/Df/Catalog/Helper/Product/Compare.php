<?php


class Df_Catalog_Helper_Product_Compare extends Mage_Catalog_Helper_Product_Compare {


    /**
     * Retrieve url for adding product to compare list
     *
     * @param   Mage_Catalog_Model_Product $product
     * @return  string|null
     */
    public function getAddUrl (
		/**
		 * Мы не можем явно указать тип параметра $product,
		 * потому что иначе интерпретатор сделает нам замечание:
		 * «Strict Notice: Declaration of Df_Catalog_Helper_Product_Compare::getAddUrl()
		 * should be compatible with that of Mage_Catalog_Helper_Product_Compare::getAddUrl()»
		 */
		$product
	) {

		df_assert ($product instanceof Mage_Catalog_Model_Product);

        $result = parent::_getUrl('catalog/product_compare/add', $this->_getUrlParams($product));

	    if (df_module_enabled (Df_Core_Module::TWEAKS) && df_enabled (Df_Core_Feature::TWEAKS)) {
			if (
					(
							df_handle_presents (Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
						&&
							df_cfg()->tweaks()->catalog()->product()->view()->getHideAddToCompare()
					)
				||
					(
							df_cfg()->tweaks()->catalog()->product()->_list()->getHideAddToCompare()
						&&
							df_helper()->tweaks()->isItCatalogProductList()
					)
			) {

				$result = NULL;
			}

	    }
	    return $result;
    }



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Product_Compare';
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