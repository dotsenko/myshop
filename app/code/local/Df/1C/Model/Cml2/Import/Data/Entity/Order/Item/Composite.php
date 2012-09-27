<?php

/**
 * Это класс моделирует сложную строку заказа:
 * строку, включающую в себя все простые строки заказа для данного товара.
 *
 * Шаблон проектирования Composite:
 *
 * «The composite pattern describes that a group of objects are to be treated
 	 in the same way as a single instance of an object.
 	 The intent of a composite is to "compose" objects into tree structures
 	 to represent part-whole hierarchies.
 	 Implementing the composite pattern
 	 lets clients treat individual objects and compositions uniformly.»
 *
 * @link http://en.wikipedia.org/wiki/Composite_pattern
 */
class Df_1C_Model_Cml2_Import_Data_Entity_Order_Item_Composite
	extends Df_1C_Model_Cml2_Import_Data_Entity_Order_Item {


	/**
	 * @override
	 * @param string $paramName
	 * @param string|int|array|float $defaultValue [optional]
	 * @return mixed
	 */
	public function getEntityParam ($paramName, $defaultValue = null) {

		df_param_string ($paramName, 0);

		/** @var string|int|array|float $result  */
		$result = $this->getFirstItem()->getEntityParam ($paramName, $defaultValue);

		return $result;
	}





	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Order_Item
	 */
	private function getFirstItem () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_Order_Item $result  */
		$result = df_a ($this->getSimpleItems(), 0);

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_Order_Item);

		return $result;
	}



	/**
	 * Перекрываем этот метод лишь для того,
	 * чтобы не проводить ненужные вычисления свойства $_product
	 *
	 * @override
	 * @return Df_Catalog_Model_Product
	 */
	public function getProduct () {

		/** @var Df_Catalog_Model_Product $result */
		$result = $this->getFirstItem()->getProduct();

		df_assert ($result instanceof Df_Catalog_Model_Product);

		return $result;
	}




	/**
	 * @return array
	 */
	private function getSimpleItems () {
		return $this->cfg (self::PARAM__SIMPLE_ITEMS);
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__SIMPLE_ITEMS, new Df_Zf_Validate_Array()
			)
		;
	}



	const PARAM__SIMPLE_ITEMS = 'simple_items';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Order_Item_Composite';
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
