<?php

class Df_Catalog_Model_Resource_Setup_AddAttribute extends Df_Catalog_Model_Resource_Setup {


	/**
	 * Этот метод отличается от родительского метода addAttribute тем,
	 * что не привязывает свойство ни к одному прикладному типу товаров
	 *
	 * @param string $attributeCode
	 * @param array $attributeData
	 * @return Mage_Eav_Model_Entity_Attribute
	 */
	public function addAttributeRm ($attributeCode, array $attributeData) {

		df_param_string ($attributeCode, 0);

		$attributeData =
			array_merge (
				array(
					'entity_type_id' => self::getEntityTypeIdRm()
					,
					'attribute_code' => $attributeCode
				)
				,
				$attributeData
			)
		;

		$this->_validateAttributeData ($attributeData);

		/** @var int|null $sortOrder */
		$sortOrder = df_a ($attributeData, 'sort_order');

		$attributeId = $this->getAttribute (self::getEntityTypeIdRm(), $attributeCode, 'attribute_id');


		if (!$attributeId) {
			$this->_insertAttribute ($attributeData);
		}
		else {
			$this
				->updateAttribute (
					self::getEntityTypeIdRm()
					,
					$attributeId
					,
					$attributeData
					,
					$value = null
					,
					$sortOrder
				)
			;
		}


		/** @var array|null $options  */
		$options = df_a ($attributeData, 'option');

		if (!is_null ($options)) {
			df_assert_array ($options);
			$options ['attribute_id'] =
				$this->getAttributeId (
					self::getEntityTypeIdRm()
					,
					$attributeCode
				)
			;
			$this->addAttributeOption ($options);
		}


		df_helper()->eav()->cleanCache();


		/** @var Mage_Catalog_Model_Resource_Product $productResource  */
		$productResource = Mage::getResourceModel('catalog/product');

		$productResource->loadAllAttributes();



		/** @var Mage_Catalog_Model_Resource_Eav_Attribute $result  */
		$result =
			df_model (
				'catalog/resource_eav_attribute'
			)
		;

		df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);


		$result->loadByCode (self::getEntityTypeIdRm(), $attributeCode);

		return $result;

	}




	/**
	 * @override
	 * @param array $attr
	 * @return array
	 */
	protected function _prepareValues ($attr) {
		return $attr;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Resource_Setup_AddAttribute';
	}




	/**
	 * @static
	 * @return int
	 */
	private static function getEntityTypeIdRm () {
		return df_helper()->catalog()->eav()->getProductEntity()->getTypeId();
	}




	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return 'df_catalog/setup_addAttribute';
	}




	/**
	 * @return Df_Catalog_Model_Resource_Setup_AddAttribute
	 */
	public static function singleton () {

		/** @var Df_Catalog_Model_Resource_Setup_AddAttribute $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Catalog_Model_Resource_Setup_AddAttribute $result  */
			$result =
				Mage::getResourceModel(
					Df_Catalog_Model_Resource_Setup_AddAttribute::getNameInMagentoFormat()
					,
					'df_catalog_setup'
				)
			;

			df_assert ($result instanceof Df_Catalog_Model_Resource_Setup_AddAttribute);

		}

		return $result;

	}



}


