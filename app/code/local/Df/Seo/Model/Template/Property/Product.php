<?php


abstract class Df_Seo_Model_Template_Property_Product
	extends Df_Seo_Model_Template_Property {




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Template_Property_Product';
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
	 * @return Df_Seo_Model_Template_Adapter_Product
	 */
	public function getAdapter () {
		return parent::getAdapter ();
	}



	/**
	 * @return Mage_Catalog_Model_Product
	 */
	public function getProduct () {
		return $this->getAdapter ()->getProduct ();
	}


	/**
	 * @return Mage_Catalog_Model_Resource_Product|Mage_Catalog_Model_Resource_Eav_Mysql4_Product
	 */
	public function getProductResource () {
		return $this->getProduct ()->getResource();
	}


	const PARAM_ADAPTER = 'adapter';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (self::PARAM_ADAPTER, Df_Seo_Model_Template_Adapter_Product::getClass())
		;
	}

}