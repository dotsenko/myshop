<?php


abstract class Df_Dataflow_Model_Importer_Product_Options_Format_Abstract
	extends Df_Core_Model_Abstract {





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Product_Options_Format_Abstract';
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
	 * @abstract
	 * @return Df_Dataflow_Model_Importer_Product_Options_Format_Abstract
	 */
	public abstract function process ();

	/**
	 * @return string
	 */
	protected abstract function getPattern ();


	/**
	 * @param  string $key
	 * @return bool
	 */
	public function canProcess ($key) {
		$matches = array ();
		return 0 < preg_match ($this->getPattern (), $key, $matches);
	}





	/**
	 * @return Df_Catalog_Model_Product
	 */
	protected function getProduct () {

		$result = $this->getData (self::PARAM_PRODUCT);

		df_assert ($result instanceof Df_Catalog_Model_Product);

		return $result;

	}





	/**
	 * @return string
	 */
	protected function getImportedKey () {
		return $this->cfg (self::PARAM_IMPORTED_KEY);
	}


	/**
	 * @return string|null
	 */
	protected function getImportedValue () {
		return $this->cfg (self::PARAM_IMPORTED_VALUE);
	}


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
	        ->addValidator (self::PARAM_IMPORTED_KEY, new Zend_Validate_NotEmpty ())
	        ->addValidator (self::PARAM_IMPORTED_KEY, new Df_Zf_Validate_String ())
	        ->addValidator (self::PARAM_IMPORTED_VALUE, new Df_Zf_Validate_String ())
	        ->validateClass (self::PARAM_PRODUCT, self::PARAM_PRODUCT_TYPE)
		;
	}





	const PARAM_PRODUCT = 'product';
	const PARAM_PRODUCT_TYPE = 'Mage_Catalog_Model_Product';


	const PARAM_IMPORTED_KEY = 'importedKey';
	const PARAM_IMPORTED_VALUE = 'importedValue';


}