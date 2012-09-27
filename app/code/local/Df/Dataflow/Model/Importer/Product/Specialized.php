<?php



abstract class Df_Dataflow_Model_Importer_Product_Specialized extends Df_Core_Model_Abstract {



	/**
	 * @return Df_Dataflow_Model_Importer_Product_Specialized
	 */
	abstract public function process ();





	/**
	 * @return Df_Catalog_Model_Product
	 */
	protected function getProduct () {

		/** @var Df_Catalog_Model_Product $result  */
		$result = $this->cfg (self::PARAM_PRODUCT);

		df_assert ($result instanceof Df_Catalog_Model_Product);

		return $result;

	}






	/**
	 * @return array
	 */
	protected function getImportedRow () {

		/** @var array $result  */
		$result = $this->cfg (self::PARAM_IMPORTED_ROW);

		df_assert_array ($result);

		return $result;

	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (
				self::PARAM_PRODUCT, Df_Catalog_Const::DF_PRODUCT_CLASS
			)
	        ->addValidator (
				self::PARAM_IMPORTED_ROW, new Df_Zf_Validate_Array ()
			)
		;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Product_Specialized';
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



	const PARAM_PRODUCT = 'product';
	const PARAM_IMPORTED_ROW = 'importedRow';



}