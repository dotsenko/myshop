<?php

class Df_Tax_Model_Config_Source_DisplayTypeYesNo extends Df_Core_Model_Abstract {

	
	/**
	 * @return array
	 */
	public function toOptionArray () {
	
		if (!isset ($this->_optionArray)) {
	
			/** @var array $result  */
			$result =
				array (
					array (
						'value' => 0
						,
						'label' => Mage::helper('tax')->__('Display Excluding Tax')
					)
					,
					array (
						'value' => 1
						,
						'label' => Mage::helper('tax')->__('Display Including Tax')
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


