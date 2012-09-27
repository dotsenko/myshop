<?php

class Df_Catalog_Block_Product_List extends Mage_Catalog_Block_Product_List {


    /**
     * Translate block sentence
     *
     * @return string
     */

    public function __ ()
    {
	    $fa = func_get_args ();

	    $result =
			df_helper()->localization()->translation()->translateByParent (
				$fa, $this
			)
		;


	    if (df_module_enabled (Df_Core_Module::TWEAKS) && df_enabled (Df_Core_Feature::TWEAKS)) {
			if (
					(
							df_handle_presents (Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
						&&
							df_cfg()->tweaks()->catalog()->product()->view()->getHideAddToCart()
					)
				||
					(
							df_cfg()->tweaks()->catalog()->product()->_list()->getHideAddToCart()
						&&
							df_helper()->tweaks()->isItCatalogProductList()
					)
			) {
				$textToTranslate = df_a ($fa, 0);
				if (is_string ($textToTranslate)) {
					switch ($textToTranslate) {
						case 'Out of stock':
							$result = '';
							break;
					}
				}
			}

		    if (
					df_helper()->tweaks()->isItCatalogProductList()
				&&
					df_cfg()->tweaks()->catalog()->product()->_list()->getReplaceAddToCartWithMore ()
		    ) {
				$textToTranslate = df_a ($fa, 0);
				if (is_string ($textToTranslate)) {
					switch ($textToTranslate) {
						case 'Add to Cart':
							$result = parent::__ ('More...');
							break;
					}
				}
		    }

	    }

	    return $result;
    }



	/**
	 * Retrieve url for add product to cart
	 * Will return product view page URL if product has required options
	 *
	 * @param Mage_Catalog_Model_Product $product
	 * @param array $additional
	 * @return string
	 */
	public function getAddToCartUrl($product, $additional = array()) {
		return
			(
					df_module_enabled (Df_Core_Module::TWEAKS)
				&&
					df_enabled (Df_Core_Feature::TWEAKS)
				&&
					(
							df_handle_presents (Df_Catalog_Const::LAYOUT_HANDLE__CATEGORY_VIEW)
						||
							df_handle_presents (Df_Cms_Const::LAYOUT_HANDLE__PAGE)
					)
				&&
					df_cfg()->tweaks()->catalog()->product()->_list()->getReplaceAddToCartWithMore()
			)
		?
			parent::getProductUrl ($product, $additional)
		:
			parent::getAddToCartUrl ($product, $additional)
		;
	}

}