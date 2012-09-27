<?php

class Df_Tax_Model_Config_Source_DisplayType extends Df_Core_Model_Abstract {

	
	/**
	 * @return array
	 */
	public function toOptionArray () {
	
		if (!isset ($this->_optionArray)) {
	
			/** @var array $result  */
			$result =
				array (
					array (
						'value' => Mage_Tax_Model_Config::DISPLAY_TYPE_EXCLUDING_TAX
						,
						'label' => Mage::helper('tax')->__('Display Excluding Tax')
					)
					,
					array (
						'value' => Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX
						,
						'label' => Mage::helper('tax')->__('Display Including Tax')
					)
					,
					array (
						'value' => Mage_Tax_Model_Config::DISPLAY_TYPE_BOTH
						,
						'label' => Mage::helper('tax')->__('Display Including and Excluding Tax')
					)
				)
			;
	
			df_assert_array ($result);
	
			$this->_optionArray = $result;
		}
	
	
		df_result_array ($this->_optionArray);
	
		return $this->_optionArray;
	}
	
	
	/**
	* @var array
	*/
	private $_optionArray;	




}


