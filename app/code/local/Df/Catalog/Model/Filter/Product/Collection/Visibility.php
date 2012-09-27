<?php


/**
 * Оставляет в коллекции только товары с заданной степенью видимости
 */
class Df_Catalog_Model_Filter_Product_Collection_Visibility
	extends Df_Core_Model_Filter_Collection {





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Filter_Product_Collection_Visibility';
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
	 * Должна возвращать класс элементов коллекции
	 *
	 * @return string
	 */
	protected function getItemClass () {
		return Df_Catalog_Const::PRODUCT_CLASS;
	}



	/**
	 * @return Df_Catalog_Model_Validate_Product_Visibility
	 */
	protected function createValidator () {

		$result =
			df_model (
				Df_Catalog_Model_Validate_Product_Visibility::getNameInMagentoFormat()
				,
				array (
					Df_Catalog_Model_Validate_Product_Visibility::VALID_VISIBILITY_STATES =>
						$this->getValidVisibilityStates ()
				)
			)
		;
		/** @var Df_Catalog_Model_Validate_Product_Visibility $validator */


		df_assert ($result instanceof Df_Catalog_Model_Validate_Product_Visibility);

		return $result;
	}



	/**
	 * @return array
	 */
	private function getValidVisibilityStates () {
		return $this->cfg (Df_Catalog_Model_Validate_Product_Visibility::VALID_VISIBILITY_STATES);
	}



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				Df_Catalog_Model_Validate_Product_Visibility::VALID_VISIBILITY_STATES
				,
				new Df_Zf_Validate_Array ()
			)
		;
	}

}


