<?php


class Df_Catalog_Block_Frontend_Product_View_Sku extends Df_Core_Block_Template {
	
	
	
	
	/**
	 * @return string
	 */
	public function getOutput () {
	
		if (!isset ($this->_output)) {
	
			/** @var string $result  */
			$result = 
				implode (
					': '
					,
					df_clean (
						array (
							$this->getFormattedLabel ()
							,
							$this->getFormattedValue ()
						)
					)
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_output = $result;
	
		}
	
	
		df_result_string ($this->_output);
	
		return $this->_output;
	
	}
	
	
	/**
	* @var string
	*/
	private $_output;	
	
	
	
	
	/**
	 * @return string
	 */
	private function getFormattedLabel () {
	
		if (!isset ($this->_formattedLabel)) {
	
			/** @var string $result  */
			$result =
					!df_cfg()->tweaks()->catalog()->product()->view()->sku()->isLabelEnabled ()
				?	
					Df_Core_Const::T_EMPTY
				:
					df_cfg()->tweaks()->catalog()->product()->view()->sku()->getLabelFont()->format (
						df_mage()->catalogHelper()->__('Sku')
						,
						implode (
							'-'
							,
							array (
								'rm'
								,
								'product'
								,
								$this->getProduct()->getId ()
								,
								'sku'
								,
								'label'
							)
						)
					)
			;
	
	
			df_assert_string ($result);
	
			$this->_formattedLabel = $result;
	
		}
	
	
		df_result_string ($this->_formattedLabel);
	
		return $this->_formattedLabel;
	
	}
	
	
	/**
	* @var string
	*/
	private $_formattedLabel;	
	
	
	
	
	/**
	 * @return string
	 */
	private function getFormattedValue () {
	
		if (!isset ($this->_formattedValue)) {
	
			/** @var string $result  */
			$result =
				df_cfg()->tweaks()->catalog()->product()->view()->sku()->getSkuFont()->format (
					$this->getSku()
					,
					implode (
						'-'
						,
						array (
							'rm'
							,
							'product'
							,
							$this->getProduct()->getId ()
							,
							'sku'
							,
							'value'
						)
					)
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_formattedValue = $result;
	
		}
	
	
		df_result_string ($this->_formattedValue);
	
		return $this->_formattedValue;
	
	}
	
	
	/**
	* @var string
	*/
	private $_formattedValue;		
	
	
	
	

	/**
	 * @return string
	 */
	public function getSku () {

		/** @var string $result  */
		$result = $this->getProduct()->getSku();

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string|null
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;
	}



	/**
	 * @override
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result =
				df_enabled (Df_Core_Feature::TWEAKS)
			&&
				df_module_enabled (Df_Core_Module::TWEAKS)
			&&
				df_cfg()->tweaks()->catalog()->product()->view()->sku()->isEnabled()
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @return Mage_Catalog_Model_Product
	 */
	private function getProduct () {

		/** @var Mage_Catalog_Model_Product $result  */
		$result = Mage::registry('product');

		df_assert ($result instanceof Mage_Catalog_Model_Product);

		return $result;

	}




	const DEFAULT_TEMPLATE = 'df/catalog/product/view/sku.phtml';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Block_Frontend_Product_View_Sku';
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


