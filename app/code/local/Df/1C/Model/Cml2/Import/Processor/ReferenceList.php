<?php

class Df_1C_Model_Cml2_Import_Processor_ReferenceList extends Df_1C_Model_Cml2_Import_Processor {



	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Processor
	 */
	public function process () {

		/**
		 * Ищем справочник с данным идентификатором
		 */

		/**
		 * Обратите внимание, что класс — именно этот даже в Magento 1.4.0.1
		 *
		 * @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute
		 */
		$attribute =
			df_helper()->dataflow()->registry()->attributes()->findByExternalId (
				$this->getEntity()->getExternalId()
			)
		;




		/** @var array $attributeData  */
		$attributeData = array ();


		if (!is_null ($attribute)) {

			df_assert ($attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute);

			$attributeData =
				array_merge (
					$attribute->getData ()
					,
					array (
						'frontend_label' => $this->getEntity()->getName()
						,
						'option' =>
							Df_Eav_Model_Entity_Attribute_Option_Calculator
								::calculateStatic (
									$attribute
									,
									df_a (
										$this->getEntity()->getOptionsInMagentoFormat()
										,
										'value'
									)
								)
					)
				)
			;

			df_helper()->_1c()
				->log (
					sprintf (
						'Обновление справочника «%s».'
						,
						$this->getEntity()->getName()
					)
				)
			;

		}

		else {

			/**
			 * Для некоторых свойств товара можно не создавать объекты-свойства,
			 * а использовать объекты-свойства из стандартной комплектации Magento
			 *
			 * @var string|null $standardCode
			 */
			$standardCode =
				df_a (
					$this->getMapFromExternalNameToStandardAttributeCode ()
					,
					$this->getEntity()->getName()
				)
			;

			if (!is_null ($standardCode)) {

				/**
				 * Убеждаемся, что стандартное свойство не удалено
				 */

				/** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute  */
				$attribute =
					df_model (
						'catalog/resource_eav_attribute'
					)
				;

				df_assert ($attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute);


				$attribute
					->loadByCode (
						df_helper()->eav()->getProductEntityTypeId()
						,
						$standardCode
					)
				;

				if (1 > intval ($attribute->getId())) {

					$attribute = null;

				}

				else if ($attribute->getData(Df_1C_Const::ENTITY_1C_ID)) {

					/**
					 * Стандартное свойство Magento уже привязано к другому свойству из 1С
					 */
					$attribute = null;

				}
			}



			if (!is_null ($attribute)) {

				/**
				 * Используем объект-свойство из стандартной комплектации
				 */
				$attributeData =
					array_merge (
						$attribute->getData ()
						,
						array (
							Df_1C_Const::ENTITY_1C_ID => $this->getEntity()->getExternalId()
							,
							'option' =>
								Df_Eav_Model_Entity_Attribute_Option_Calculator
									::calculateStatic (
										$attribute
										,
										df_a (
											$this->getEntity()
												->getOptionsInMagentoFormat()
											,
											'value'
										)
									)
						)
					)
				;

				df_helper()->_1c()
					->log (
						sprintf (
							'Обновление справочника «%s».'
							,
							$this->getEntity()->getName()
						)
					)
				;

			}

			else {

				$attributeData =
					array (
						'entity_type_id' => df_helper()->eav()->getProductEntityTypeId()
						,
						'attribute_code' =>
							$this->getRegistry()->attribute()
								->generateImportedAttributeCodeByLabel (
									$this->getEntity()->getName()
								)
						,
						'attribute_model' => null
						,
						'backend_model' => $this->getEntity()->getBackendModel()
						,
						'backend_type' => $this->getEntity()->getBackendType()
						,
						'backend_table' => null
						,
						'frontend_model' => null
						,
						'frontend_input' => $this->getEntity()->getFrontendInput()
						,
						'frontend_label' => $this->getEntity()->getName()
						,
						'frontend_class' => null
						,
						'source_model' => $this->getEntity()->getSourceModel()
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
	//					,
	//					'apply_to' =>
	//						array (
	//							'simple'
	//							, 'grouped'
	//							, 'configurable'
	//							, 'virtual'
	//							, 'bundle'
	//							, 'downloadable'
	//						)
						,
						'is_visible_in_advanced_search' => 1
						,
						'position' => 0
						,
						'is_wysiwyg_enabled' => 0
						,
						'is_used_for_promo_rules' => 0
						,
						Df_1C_Const::ENTITY_1C_ID => $this->getEntity()->getExternalId()
						,
						'option' =>
							$this->getEntity()->getOptionsInMagentoFormat()
					)
				;

				df_helper()->_1c()
					->log (
						sprintf (
							'Создание справочника «%s».'
							,
							$this->getEntity()->getName()
						)
					)
				;
			}

		}


		$attribute =
			df_helper()->dataflow()->registry()->attributes()
				->findByCodeOrCreate (
					df_a ($attributeData, 'attribute_code')
					,
					$attributeData
				)
		;


		df_assert ($attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute);


		df_assert (0 < intval ($attribute->getId()));

		if (is_null ($attribute->getData(Df_1C_Const::ENTITY_1C_ID))) {
			df_error (
				'У свойства «%s» в данной точке программы должен присутствовать внешний идентификатор.'
				,
				$attribute->getAttributeCode()
			);
		}


		/**
		 * Назначаем справочным значениям идентификаторы из 1С
		 */


		$this->assignExternalIdToOptions ($attribute);


		df_assert (
			!is_null (
				df_helper()->dataflow()->registry()->attributes()->findByExternalId (
					$attribute->getData(Df_1C_Const::ENTITY_1C_ID)
				)
			)
		);

		return $this;

	}





	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Attribute_ReferenceList|Df_1C_Model_Cml2_Import_Data_Entity
	 */
	protected function getEntity () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_Attribute_ReferenceList $result */
		$result = parent::getEntity();

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_Attribute_ReferenceList);

		return $result;
	}





	/**
	 * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Attribute
	 */
	private function assignExternalIdToOptions (Mage_Catalog_Model_Resource_Eav_Attribute $attribute) {

		/** @var Mage_Eav_Model_Entity_Attribute_Source_Table $source  */
		$source = $attribute->getSource();

		df_assert ($source instanceof Mage_Eav_Model_Entity_Attribute_Source_Table);



		/** @var Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection|Mage_Eav_Model_Mysql4_Entity_Attribute_Option_Collection $options */
		$options = Mage::getResourceModel('eav/entity_attribute_option_collection');

		df_helper()->eav()->assert()->entityAttributeOptionCollection ($options);


		$options->setPositionOrder ('asc');
		$options->setAttributeFilter ($attribute->getId());
		$options->setStoreFilter($attribute->getStoreId());


		foreach ($options as $option) {

			/** @var Mage_Eav_Model_Entity_Attribute_Option $option */
			df_assert ($option instanceof Mage_Eav_Model_Entity_Attribute_Option);

			if (is_null ($option->getData (Df_1C_Const::ENTITY_1C_ID))) {


				/** @var Df_1C_Model_Cml2_Import_Data_Entity_ReferenceListPart_Item|null $importedOption  */
				$importedOption =
					$this->getEntity()->getItems()->findByName (
						$option->getData ('value')
					)
				;


				/**
				 * Мы могли не найти внешний идентификатор опции,
				 * если опция была добавлена администратором вручную
				 */
				if (!is_null ($importedOption)) {

					df_assert ($importedOption instanceof Df_1C_Model_Cml2_Import_Data_Entity_ReferenceListPart_Item);


					/** @var string|null $optionExternalId  */
					$optionExternalId = $importedOption->getExternalId();

					df_assert_string ($optionExternalId);


					$option
						->setData (
							Df_1C_Const::ENTITY_1C_ID
							,
							$optionExternalId
						)
					;

					$option->save();
				}

			}

		}

		return $this;

	}





	/**
	 * Для некоторых свойств товара можно не создавать объекты-свойства,
	 * а использовать объекты-свойства из стандартной комплектации Magento
	 *
	 * @return array
	 */
	private function getMapFromExternalNameToStandardAttributeCode () {

		/** @var array $result  */
		$result =
			array (
				'Производитель' => 'manufacturer'
			)
		;

		df_result_array ($result);

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
				self::PARAM__ENTITY
				,
				Df_1C_Model_Cml2_Import_Data_Entity_Attribute_ReferenceList::getClass()
			)
		;
	}	





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_ReferenceList';
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


