<?php

class Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom
	extends Df_1C_Model_Cml2_Import_Data_Entity {



	/**
	 * @return Mage_Catalog_Model_Resource_Eav_Attribute
	 */
	public function getAttribute () {
	
		if (!isset ($this->_attribute)) {
	
			/** @var Mage_Catalog_Model_Resource_Eav_Attribute $result  */
			$result = 	
				df_helper()->dataflow()->registry()->attributes()->findByExternalId (
					$this->getAttributeExternalId ()
				)
			;

			if (is_null ($result)) {

				/**
				 * Вот здесь-то мы можем добавить в Magento нестандартные свойства товаров,
				 * учёт которых ведётся в 1С: Управление торговлей.
				 */

				$result = $this->createMagentoAttribute();


				df_helper()->_1c()
					->log (
						sprintf (
							'Создано свойство «%s»'
							,
							$result->getName()
						)
					)
				;


				df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);

				df_helper()->dataflow()->registry()->attributes()->addEntity ($result);

			}


			df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);


			df_helper()->_1c()
				->log (
					sprintf (
						'Добавляем к типу %s свойство %s'
						,
						$this->getProduct()->getAttributeSet()->getAttributeSetName()
						,
						$result->getName()
					)
				)
			;



			/**
			 * Мало, чтобы свойство присутствовало в системе:
			 * надо добавить его к прикладному типу товара.
			 */

			df_helper()->_1c()
				->create1CAttributeGroupIfNeeded (
					$this->getProduct()->getAttributeSet()->getId()
				)
			;

			Df_Catalog_Model_Installer_AddAttributeToSet
				::processStatic (
					$result->getAttributeCode()
					,
					$this->getProduct()->getAttributeSet()->getId()
					,
					Df_1C_Const::PRODUCT_ATTRIBUTE_GROUP_NAME
				)
			;

	
			$this->_attribute = $result;
		}
	
		df_assert ($this->_attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute);
	
		return $this->_attribute;
	}
	
	
	/**
	* @var Mage_Catalog_Model_Resource_Eav_Attribute
	*/
	private $_attribute;	
	


	/**
	 * @return string
	 */
	public function getAttributeExternalId () {

		/** @var string $result  */
		$result = $this->getEntityParam ('Ид');

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	public function getAttributeName () {

		/** @var string $result  */
		$result = $this->getAttribute()->getName();

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	public function getExternalId () {

		/** @var string $result  */
		$result =
			implode (
				'::'
				,
				array (
					$this->getAttributeExternalId()
					,
					$this->getValue()
				)
			)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	public function getValue () {

		/** @var string $result  */
		$result = $this->getEntityParam ('Значение');

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getValueForDataflow () {

		/** @var string $result  */
		$result =
			$this->getAttributeEntity()->convertValueToMagentoFormat (
				$this->getValue()
			)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getValueForObject () {

		/** @var string $result  */
		$result =
			$this->getAttributeEntity()->convertValueToMagentoFormat (
				$this->getValue()
			)
		;

		df_result_string ($result);

		return $result;

	}


	
	
	
	/**
	 * @return bool
	 */
	public function isAttributeExistAndBelongToTheProductType () {
	
		if (!isset ($this->_attributeExistAndBelongToTheProductType)) {
	
			/** @var bool $result  */
			$result = false;


			/** @var Mage_Eav_Model_Entity_Attribute $attribute  */
			$attribute =
				df_helper()->dataflow()->registry()->attributes()->findByExternalId (
					$this->getAttributeExternalId ()
				)
			;

			if (!is_null ($attribute)) {

				df_assert ($attribute instanceof Mage_Eav_Model_Entity_Attribute);


				/**
				 * Смотрим, принадлежит ли свойство типу товара
				 */

				/** @var Mage_Eav_Model_Resource_Entity_Attribute_Collection $attributes */
				$attributes = Mage::getResourceModel('eav/entity_attribute_collection');

				$attributes
					->setEntityTypeFilter(
						df_helper()->catalog()->eav()->getProductEntity()->getTypeId()
					)
				;

				$attributes->addSetInfo();

				$attributes->addFieldToFilter ('attribute_code', $attribute->getAttributeCode());


				$attributes->load ();


				/** @var Mage_Eav_Model_Entity_Attribute $attributeInfo  */
				$attributeInfo = null;

				foreach ($attributes as $attributeInfoCurrent) {
					$attributeInfo = $attributeInfoCurrent;
					break;
				}

				df_assert ($attributeInfo instanceof Mage_Eav_Model_Entity_Attribute);


				/** @var array $setsInfo  */
				$setsInfo = $attributeInfo->getData('attribute_set_info');

				df_assert_array ($setsInfo);

				$result =
					in_array (
						$this->getProduct()->getAttributeSet()->getId()
						,
						array_keys (
							$setsInfo
						)
					)
				;
			}
	
			df_assert_boolean ($result);
	
			$this->_attributeExistAndBelongToTheProductType = $result;
		}
	
		df_result_boolean ($this->_attributeExistAndBelongToTheProductType);
	
		return $this->_attributeExistAndBelongToTheProductType;
	}
	
	
	/**
	* @var bool
	*/
	private $_attributeExistAndBelongToTheProductType;		






	/**
	 * @return Mage_Catalog_Model_Resource_Eav_Attribute
	 */
	private function createMagentoAttribute () {

		/** @var string $attributeCode */
		$attributeCode =
			$this->getRegistry()->attribute()
				->generateImportedAttributeCodeByLabel (
					$this->getAttributeEntity()->getName()
				)
		;

		df_assert_string ($attributeCode);


		/** @var array $attributeData */
		$attributeData =
			array (
				'entity_type_id' => df_helper()->eav()->getProductEntityTypeId()
				,
				'attribute_code' => $attributeCode
				,
				'attribute_model' => null
				,
				'backend_model' => $this->getAttributeEntity()->getBackendModel()
				,
				'backend_type' => $this->getAttributeEntity()->getBackendType()
				,
				'backend_table' => null
				,
				'frontend_model' => null
				,
				'frontend_input' =>  $this->getAttributeEntity()->getFrontendInput()
				,
				'frontend_label' => $this->getAttributeEntity()->getName()
				,
				'frontend_class' => null
				,
				'source_model' => $this->getAttributeEntity()->getSourceModel()
				,
				'is_required' => 0
				,
				'is_user_defined' => 1
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
				'is_visible_on_front' =>
					intval (df_cfg()->_1c()->products()->attributes()->showOnProductPage())
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
				,
				Df_1C_Const::ENTITY_1C_ID => $this->getAttributeExternalId()

			)
		;

		/** @var Mage_Catalog_Model_Resource_Eav_Attribute $result */
		$result =
			df_helper()->dataflow()->registry()->attributes()
				->findByCodeOrCreate (
					$attributeCode
					,
					$attributeData
				)
		;

		df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);



		df_assert (0 < intval ($result->getId()));
		df_assert (!is_null ($result->getData(Df_1C_Const::ENTITY_1C_ID)));



		df_helper()->_1c()
			->log (
				sprintf (
					'Добавлено свойство «%s».'
					,
					$this->getAttributeEntity()->getName()
				)
			)
		;


		return $result;

	}






	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Attribute
	 */
	private function getAttributeEntity () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_Attribute $result  */
		$result =
			$this->getRegistry()->import()->collections()->getAttributes()->findByExternalId (
				$this->getAttributeExternalId()
			)
		;

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_Attribute);

		return $result;

	}





	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Product
	 */
	private function getProduct () {
		return $this->cfg (self::PARAM__PRODUCT);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__PRODUCT, Df_1C_Model_Cml2_Import_Data_Entity_Product::getClass()
			)
		;
	}



	const PARAM__PRODUCT = 'product';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom';
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

