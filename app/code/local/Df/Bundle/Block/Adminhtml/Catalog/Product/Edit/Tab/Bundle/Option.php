<?php

class Df_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option
	extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option {


	/**
	 * @override
	 * @return Df_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option
	 */
	protected function _prepareLayout() {

		parent::_prepareLayout();

		/** @var Mage_Adminhtml_Block_Widget_Button $buttonDelete */
		$buttonDelete = $this->getChild ('option_delete_button');

		if (false === $buttonDelete) {
			$buttonDelete = null;
		}

		if (!is_null ($buttonDelete)) {
			$buttonDelete
				->setData (
					'label'
					,
					Mage::helper('bundle')->__('Delete Option')
				)
			;
		}

		return $this;
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


