<?php

class Df_Tax_Model_Config_Source_AddressType extends Df_Core_Model_Abstract {


	/**
	 * @return array
	 */
	public function toOptionArray() {
		return array(
			/**
			 * В отличие от стандартного класса Mage_Adminhtml_Model_System_Config_Source_Tax_Basedon
			 * данный класс использует для перевода модуль Tax, а не Adminhtml
			 */
			array('value'=>'shipping', 'label'=>Mage::helper('tax')->__('Shipping Address')),
			array('value'=>'billing', 'label'=>Mage::helper('tax')->__('Billing Address')),
			array('value'=>'origin', 'label'=>Mage::helper('tax')->__("Shipping Origin")),
		);
	}


}


