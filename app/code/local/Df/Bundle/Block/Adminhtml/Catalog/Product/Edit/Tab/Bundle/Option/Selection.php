<?php

class Df_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option_Selection
	extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option_Selection {


	/**
	 * @override
	 * @return string
	 */
	protected function _toHtml() {

		/** @var string $result */
		$result = parent::_toHtml();

		$result .=
			"<script type='text/javascript'>
				jQuery(window).trigger ('bundle.product.edit.bundle.option.selection');
			</script>"
		;

		return $result;
	}



	/**
	 * @return string
	 */
	public function __ () {

		/** @var array $args  */
		$args = func_get_args();

		/** @var string $result  */
		$result =
			df_helper()->localization()->translation()->translateByParent ($args, $this)
		;

		return $result;
	}

}


