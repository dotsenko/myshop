<?php

class Df_Tax_Model_Config_Source_ApplyDiscountOnPrices extends Df_Core_Model_Abstract {


	/**
	 * @return array
	 */
	public function toOptionArray() {
		return
			array (
				array (
					'value' => 0
					,
					'label' => Mage::helper('tax')->__('Before Tax')
				)
				,
				array (
					'value' => 1
					,
					'label' => Mage::helper('tax')->__('After Tax')
				)
			)
		;
	}

}


