<?php

class Df_Catalog_Block_Product_Price extends Mage_Catalog_Block_Product_Price {


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
	 * @override
	 * @return string|null
	 */
	public function getTemplate () {

		/** @var string $result  */
		$result =
					df_module_enabled (Df_Core_Module::TWEAKS)
				&&
					df_enabled (Df_Core_Feature::TWEAKS)
				&&
					(
							(
									df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
								&&
									df_cfg()->tweaks()->catalog()->product()->view ()->getHidePrice ()
							)
						||
							(
									df_cfg()->tweaks()->catalog()->product()->_list()->getHidePrice()
								&&
									df_helper()->tweaks()->isItCatalogProductList()
							)
					)
			?
				null
			:
				parent::getTemplate()
		;


		if (!is_null($result)) {
			df_result_string ($result);
		}


		return $result;

	}



}


