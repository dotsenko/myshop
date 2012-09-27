<?php


class Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region
	extends Df_Checkout_Block_Frontend_Ergonomic_Address_Field {
	
	
	
	/**
	 * @return Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Dropdown
	 */
	public function getControlDropdown () {
	
		if (!isset ($this->_controlDropdown)) {
	
			/** @var Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Dropdown $result  */
			$result = 
				df_block (
					Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Dropdown::getNameInMagentoFormat()
					,
					null
					,
					$this->getData()
				)
			;
	
	
			df_assert ($result instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Dropdown);
	
			$this->_controlDropdown = $result;
	
		}
	
	
		df_assert ($this->_controlDropdown instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Dropdown);
	
		return $this->_controlDropdown;
	
	}
	
	
	/**
	* @var Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Dropdown
	*/
	private $_controlDropdown;		
	

	
	
	
	/**
	 * @return Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region_Text
	 */
	public function getControlText () {
	
		if (!isset ($this->_controlText)) {
	
			/** @var Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region_Text $result  */
			$result = 
				df_block (
					Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region_Text::getNameInMagentoFormat()
					,
					null
					,
					$this->getData()
				)
			;
	
	
			df_assert ($result instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region_Text);
	
			$this->_controlText = $result;
	
		}
	
	
		df_assert ($this->_controlText instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region_Text);
	
		return $this->_controlText;
	
	}
	
	
	/**
	* @var Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region_Text
	*/
	private $_controlText;	



	/**
	 * @return string|null
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		df_result_string ($result);

		return $result;
	}



	const DEFAULT_TEMPLATE = 'df/checkout/ergonomic/address/field/region.phtml';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region';
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


