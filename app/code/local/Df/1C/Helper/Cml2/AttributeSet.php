<?php

class Df_1C_Helper_Cml2_AttributeSet extends Mage_Core_Helper_Data {



	/**
	 * @param int $attributeSetId
	 * @return Df_1C_Helper_Cml2_AttributeSet
	 */
	public function addExternalIdToAttributeSet ($attributeSetId) {

		df_param_integer ($attributeSetId, 0);
		df_param_between ($attributeSetId, 0, 1);

		/** @var Mage_Catalog_Model_Resource_Eav_Attribute $attributeExternalId  */
		$attributeExternalId =
			df_helper()->dataflow()->registry()->attributes()
				->findByCodeOrCreate (
					Df_1C_Const::ENTITY_1C_ID
					,
					array (
						'entity_type_id' => df_helper()->eav()->getProductEntityTypeId()
						,
						'attribute_code' => Df_1C_Const::ENTITY_1C_ID
						,
						'attribute_model' => null
						,
						'backend_model' => Df_Core_Const::T_EMPTY
						,
						'backend_type' => 'varchar'
						,
						'backend_table' => null
						,
						'frontend_model' => null
						,
						'frontend_input' => 'text'
						,
						'frontend_label' => '1С ID'
						,
						'frontend_class' => null
						,
						'source_model' => Df_Core_Const::T_EMPTY
						,
						'is_required' => 0
						,
						'is_user_defined' => 0
						,
						'default_value' => null
						,
						'is_unique' => 0
						,
						'note' => null
						,
						'frontend_input_renderer' => null
						,
						'is_global' => 1
						,
						'is_visible' => 1
						,
						'is_searchable' => 1
						,
						'is_filterable' => 1
						,
						'is_comparable' => 1
						,
						'is_visible_on_front' => 0
						,
						'is_html_allowed_on_front' => 0
						,
						'is_used_for_price_rules' => 0
						,
						'is_filterable_in_search' => 1
						,
						'used_in_product_listing' => 0
						,
						'used_for_sort_by' => 0
						,
						'is_configurable' => 1
						,
						'is_visible_in_advanced_search' => 1
						,
						'position' => 0
						,
						'is_wysiwyg_enabled' => 0
						,
						'is_used_for_promo_rules' => 0
					)
					,
					100
				)
		;

		df_assert ($attributeExternalId instanceof Mage_Catalog_Model_Resource_Eav_Attribute);


		df_helper()->_1c()
			->create1CAttributeGroupIfNeeded (
				$attributeSetId
			)
		;


		Df_Catalog_Model_Installer_AddAttributeToSet
			::processStatic (
				$attributeExternalId->getAttributeCode()
				,
				$attributeSetId
				,
				Df_1C_Const::PRODUCT_ATTRIBUTE_GROUP_NAME
				,
				$sortOrder = 100
			)
		;

		/**
		 * Вот в таких ситуациях, когда у нас меняется структура прикладного типа товаров,
		 * нам нужно сбросить глобальный кэш EAV.
		 */

		df_helper()->eav()->cleanCache();

		return $this;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Helper_Cml2_AttributeSet';
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


