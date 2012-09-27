<?php


class Df_Seo_Model_Template_Property_Product_Default
	extends Df_Seo_Model_Template_Property_Product {






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Template_Property_Product_Default';
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












	/**
	 * @return string
	 */
	public function getValue () {
		return
				!$this->getProductResource ()->getAttribute (
					$this->getName ()
				)
			?
				NULL
			:
				(
						df_empty (
							$this->getAttributeText ()
						)
					?
						$this->getProduct ()->getData ($this->getName ())
					:
						$this->getAttributeText ()
				)
		;
	}



	private $_attributeText;

	private function getAttributeText () {
		if (!isset ($this->_attributeText)) {
			$this->_attributeText =
				$this->getProduct ()->getAttributeText(
					$this->getName ()
				)
			;
		}
		return $this->_attributeText;
	}


}