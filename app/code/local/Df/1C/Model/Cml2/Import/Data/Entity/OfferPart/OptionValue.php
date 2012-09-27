<?php

class Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_OptionValue
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

			if (!is_null ($result)) {

				/**
				 * Вот здесь, похоже, удачное место,
				 * чтобы добавить в уже присутствующий в Magento справочник
				 * значение текущей опции, если его там нет
				 */

				df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);


				$attributeData =
					array_merge (
						$result->getData ()
						,
						array (
							'option' =>
								Df_Eav_Model_Entity_Attribute_Option_Calculator
									::calculateStatic (
										$result
										,
										array (
											'option_0' => array ($this->getValue())
										)
										,
										$isModeInsert = true
										,
										$caseInsensitive = true
									)
						)
					)
				;


				/**
				 * Какое-то значение тут надо установить,
				 * потому что оно будет одним из ключей в реестре
				 * (второй ключ — название справочника)
				 */
//				if (is_null ($result->getData (Df_1C_Const::ENTITY_1C_ID))) {
//					$attributeData [Df_1C_Const::ENTITY_1C_ID] = $this->getName();
//				}


				$result =
					df_helper()->dataflow()->registry()->attributes()
						->findByCodeOrCreate (
							df_a ($attributeData, 'attribute_code')
							,
							$attributeData
						)
				;


				df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);

			}

			else {

				$attributeData =
					array (
						'entity_type_id' => df_helper()->eav()->getProductEntityTypeId()
						,
						'attribute_code' =>
							$this->getRegistry()->attribute()
								->generateImportedAttributeCodeByLabel (
									$this->getName()
								)
						,
						'attribute_model' => null
						,
						'backend_model' => null
						,
						'backend_type' => 'int'
						,
						'backend_table' => null
						,
						'frontend_model' => null
						,
						'frontend_input' => 'select'
						,
						'frontend_label' => $this->getName()
						,
						'frontend_class' => null
						,
						'source_model' => null
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
						'is_visible_on_front' => 1
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
						/**
						 * Какое-то значение тут надо установить,
						 * потому что оно будет одним из ключей в реестре
						 * (второй ключ — название справочника)
						 */
						Df_1C_Const::ENTITY_1C_ID => $this->getAttributeExternalId()

						,
						'option' =>
							array (
								'value' =>
									array (
										'option_0' => array ($this->getValue())
									)

								,
								'order' =>
									array (
										'option_0' => 0
									)
								,
								'delete' =>
									array (
										'option_0' => 0
									)
							)
					)
				;

				$result =
					df_helper()->dataflow()->registry()->attributes()
						->findByCodeOrCreate (
							df_a ($attributeData, 'attribute_code')
							,
							$attributeData
						)
				;

				df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);

			}


			/**
			 * Назначаем справочным значениям идентификаторы из 1С
			 */


			/** @var Mage_Eav_Model_Entity_Attribute_Option $option  */
			$option = $this->getOptionByAttribute ($result);

			df_assert ($option instanceof Mage_Eav_Model_Entity_Attribute_Option);


			$option
				->setData (
					Df_1C_Const::ENTITY_1C_ID
					,
					$this->getExternalId()
				)
			;

			$option->save();


			df_helper()->dataflow()->registry()->attributes()->addEntity ($result);



			df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);

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
	private function getAttributeExternalId () {

		/** @var string $result  */
		$result =
			implode (
				' - '
				,
				array (
					'RM 1С'
					,
					$this->getName()
				)
			)
		;


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
			mb_strtolower (
				implode (
					': '
					,
					array (
						$this->getName()
						,
						$this->getValue()
					)
				)
			)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return Mage_Eav_Model_Entity_Attribute_Option
	 */
	public function getOption () {

		if (!isset ($this->_option)) {

			/** @var Mage_Eav_Model_Entity_Attribute_Option $option  */
			$result = $this->getOptionByAttribute ($this->getAttribute());

			df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute_Option);

			$this->_option = $result;

		}


		df_assert ($this->_option instanceof Mage_Eav_Model_Entity_Attribute_Option);

		return $this->_option;

	}


	/**
	* @var Mage_Eav_Model_Entity_Attribute_Option
	*/
	private $_option;




	/**
	 * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
	 * @return Mage_Eav_Model_Entity_Attribute_Option
	 */
	private function getOptionByAttribute (Mage_Catalog_Model_Resource_Eav_Attribute $attribute) {

		/** @var Mage_Eav_Model_Entity_Attribute_Source_Table $source  */
		$source = $attribute->getSource();

		df_assert ($source instanceof Mage_Eav_Model_Entity_Attribute_Source_Table);



		/** @var Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection|Mage_Eav_Model_Mysql4_Entity_Attribute_Option_Collection $options */
		$options = Mage::getResourceModel('eav/entity_attribute_option_collection');

		df_helper()->eav()->assert()->entityAttributeOptionCollection ($options);


		$options->setPositionOrder ('asc');
		$options->setAttributeFilter ($attribute->getId());
		$options->setStoreFilter($attribute->getStoreId());

		$options->addFieldToFilter('tdv.value', $this->getValue());

		df_assert_between (count ($options), 1, 1);


		/** @var Mage_Eav_Model_Entity_Attribute_Option $option  */
		$result = $options->fetchItem();

		df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute_Option);

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
	 * @return int
	 */
	public function getValueId () {
	
		/** @var int $result */
		$result = intval ($this->getOption()->getData ('value'));

		df_result_integer ($result);
	
		return $result;
	
	}
	






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_OptionValue';
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

