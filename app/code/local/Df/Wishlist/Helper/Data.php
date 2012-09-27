<?php


class Df_Wishlist_Helper_Data extends Mage_Wishlist_Helper_Data {

    /**
     * Check is allow wishlist module
     *
     * @return bool
     */
    public function isAllow()
    {
	    $result = parent::isAllow ();
	    if ($result) {
		    if (df_module_enabled (Df_Core_Module::TWEAKS) && df_enabled (Df_Core_Feature::TWEAKS)) {
				if (
						(
								df_handle_presents (Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
							&&
								df_cfg()->tweaks()->catalog()->product()->view()->getHideAddToWishlist()
						)
					||
						(
								df_cfg()->tweaks()->catalog()->product()->_list()->getHideAddToWishlist()
							&&
								df_helper()->tweaks()->isItCatalogProductList()
						)
				) {
					$result = false;
				}
		    }
	    }
        return $result;
    }




    /**
     * @return string
     */
    public function __ () {

		/** @var array $args  */
        $args = func_get_args();

		df_assert_array ($args);


		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByParent ($args, $this)
		;

		df_result_string ($result);


	    return $result;
    }




    /**
	 * @param array $args
     * @return string
     */
    public function translateByParent (array $args) {

		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByParent ($args, $this)
		;

		df_result_string ($result);

	    return $result;
    }




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Wishlist_Helper_Data';
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