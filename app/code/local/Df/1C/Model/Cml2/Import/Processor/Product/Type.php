<?php

abstract class Df_1C_Model_Cml2_Import_Processor_Product_Type extends Df_1C_Model_Cml2_Import_Processor_Product {


	/**
	 * @abstract
	 * @return float
	 */
	abstract protected function getPrice ();



	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getSku ();



	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getType ();



	/**
	 * @abstract
	 * @return int
	 */
	abstract protected function getVisibility ();





	/**
	 * @return Df_Dataflow_Model_Importer_Product
	 */
	protected function getImporter () {

		if (!isset ($this->_importer)) {

			/** @var array $rowAsArray  */
			$rowAsArray = array ();


			if (!is_null ($this->getExistingMagentoProduct())) {
				$rowAsArray =
					array_merge (
						$rowAsArray
						,
						$this->getProductDataUpdateOnly()
					)
				;
			}
			else {
				$rowAsArray =
					array_merge (
						$rowAsArray
						,
						$this->getProductDataNewOnly()
					)
				;
			}


			$rowAsArray =
				array_merge (
					$rowAsArray
					,
					$this->getProductDataNewOrUpdate()
				)
			;



			/** @var Df_Dataflow_Model_Import_Product_Row $row  */
			$row =
				df_model (
					Df_Dataflow_Model_Import_Product_Row::getNameInMagentoFormat()
					,
					array (
						Df_Dataflow_Model_Import_Product_Row::PARAM_ORDERING => 1
						,
						Df_Dataflow_Model_Import_Product_Row::PARAM_ROW_AS_ARRAY => $rowAsArray
					)
				)
			;


			df_assert ($row instanceof Df_Dataflow_Model_Import_Product_Row);



			/** @var Df_Dataflow_Model_Importer_Product $result  */
			$result =
				df_model (
					Df_Dataflow_Model_Importer_Product::getNameInMagentoFormat()
					,
					array (
						Df_Dataflow_Model_Importer_Product::PARAM_ROW => $row
					)
				)
			;


			df_assert ($result instanceof Df_Dataflow_Model_Importer_Product);

			$this->_importer = $result;

		}


		df_assert ($this->_importer instanceof Df_Dataflow_Model_Importer_Product);

		return $this->_importer;

	}


	/**
	* @var Df_Dataflow_Model_Importer_Product
	*/
	private $_importer;




	/**
	 * @return array
	 */
	protected function getProductDataNewOrUpdateBase () {

		/** @var array $result  */
		$result =
			array (
				Df_1C_Const::ENTITY_1C_ID => $this->getEntityOffer()->getExternalId()

				,
				'sku' => $this->getSku()

				,
				'category_ids' => df_string ($this->getEntityProduct()->getCategory()->getId())

				,
				'name' => $this->getEntityOffer()->getName()


				/**
				 * Поле является обязательным при импорте нового товара
				 */
				,
				'price' =>
					df_string (
						$this->getPrice()
					)

				,
				'product_name' => $this->getEntityOffer()->getName()

				,
				'weight' => df_string ($this->getEntityProduct()->getWeight())

				,
				'description' => $this->getEntityProduct()->getDescription()

				,
				'short_description' => $this->getEntityProduct()->getDescription()

				,
				'qty' =>
					df_string (
						$this->getEntityOffer()->getQuantity()
					)

				,
				'is_in_stock' =>
					df_string (
						intval (
							0 !== $this->getEntityOffer()->getQuantity()
						)
					)
			)
		;

		df_result_array ($result);

		return $result;

	}





	/**
	 * @return array
	 */
	protected function getProductDataUpdateOnly () {

		/** @var array $result  */
		$result =
			array (
				'store' => $this->getExistingMagentoProduct()->getStore()->getCode()
			)
		;


		df_result_array ($result);

		return $result;

	}





	/**
	 * @return array
	 */
	private function getProductDataNewOnly () {

		/** @var array $result  */
		$result =
			array (
				'websites' =>
					df_string (
						df_helper()->_1c()->cml2()->getStoreProcessed()->getWebsite()->getId()
					)

				,
				'attribute_set' =>
					//df_helper()->catalog()->product()->getDefaultAttributeSet()->getAttributeSetName()
					$this->getEntityProduct()->getAttributeSet()->getAttributeSetName()


				,
				'type' => $this->getType()

				,
				'product_type_id' => $this->getType()

				,
				'store' =>
					df_helper()->_1c()->cml2()->getStoreProcessed()->getCode()
					//Mage::app()->getStore()->getCode()

				,
				'store_id' =>
					df_string (df_helper()->_1c()->cml2()->getStoreProcessed()->getId())
					//df_string (Mage::app()->getStore()->getId())

				,
				'has_options' => df_string (0)

				,
				'meta_title' => null

				,
				'meta_description' => null

				,
				'image' => null

				,
				'small_image' => null

				,
				'thumbnail' => null

				,
				'url_key' => null

				,
				'url_path' => null

				,
				'image_label' => null

				,
				'small_image_label'	=> null

				,
				'thumbnail_label' => null

				,
				'country_of_manufacture' => null


//					,
//					'status' => df_string (Mage_Catalog_Model_Product_Status::STATUS_ENABLED)

				,
				'visibility' => $this->getVisibilityAsString()

				,
				'tax_class_id' => Mage::helper('tax')->__('None')

				,
				'meta_keyword' => null


//					,
//					'min_qty' => 0

				,
				'use_config_min_qty' => df_string (1)

				,
				'is_qty_decimal' => null

//					,
//					'backorders' => null

				,
				'use_config_backorders' => df_string (1)

//					,
//					'min_sale_qty' => null

				,
				'use_config_min_sale_qty' => df_string (1)

//					,
//					'max_sale_qty' => null

				,
				'use_config_max_sale_qty' => df_string (1)


				,
				'low_stock_date' => null

//					,
//					'notify_stock_qty' => null

				,
				'use_config_notify_stock_qty' => df_string (1)

				,
				'manage_stock' => df_string (1)

				,
				'use_config_manage_stock' => df_string (1)

				,
				'stock_status_changed_auto' => null

				,
				'use_config_qty_increments' => df_string (1)

//					,
//					'qty_increments' => null

				,
				'use_config_enable_qty_inc' => df_string (1)

//					,
//					'enable_qty_increments' => null

				,
				'is_decimal_divided' => null

				,
				'stock_status_changed_automatically' => null

				,
				'use_config_enable_qty_increments' => df_string (1)
			)
		;

		df_result_array ($result);

		return $result;
	}




	/**
	 * @return array
	 */
	private function getProductDataNewOrUpdate () {

		/** @var array $result  */
		$result =
			array_merge (
				$this->getProductDataNewOrUpdateBase()
				,
				$this->getProductDataNewOrUpdateAttributeValuesCustom()
				,
				$this->getProductDataNewOrUpdateOptionValues()
			)
		;

		df_result_array ($result);

		return $result;

	}





	/**
	 * @return array
	 */
	private function getProductDataNewOrUpdateAttributeValuesCustom () {

		/** @var array $result  */
		$result = array ();

		foreach ($this->getEntityProduct()->getAttributeValuesCustom() as $attributeValue) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom $attributeValue */
			df_assert ($attributeValue instanceof Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom);

			if (
				/**
				 * 1C для каждого товара указывает не только значения свойств, относящихся к товару,
				 * но и значения свойств, к товару никак не относящихся, при этом значения — пустые, например:
				 *
					<ЗначенияСвойства>
						<Ид>b79b0fe0-c8a5-11e1-a928-4061868fc6eb</Ид>
						<Значение/>
					</ЗначенияСвойства>
				 *
				 * Мы не обрабатываем эти свойства, потому что их обработка приведёт к добавлению
				 * к прикладному типу товара данного свойства, а нам это не нужно, потому что
				 * свойство не имеет отношения к прикладному типу товара.
				 */
				!(
						df_empty ($attributeValue->getEntityParam('Значение'))
					&&
						df_empty ($attributeValue->getEntityParam('ИдЗначения'))
					&&
						!$attributeValue->isAttributeExistAndBelongToTheProductType()
				)
			)  {

				$result [$attributeValue->getAttributeName()] =
					$attributeValue->getValueForDataflow()
				;
			}
		}

		df_result_array ($result);

		return $result;
	}





	/**
	 * @return array
	 */
	private function getProductDataNewOrUpdateOptionValues () {

		/** @var array $result  */
		$result = array ();


		if (0 < count ($this->getEntityOffer()->getOptionValues())) {


			df_helper()->_1c()
				->create1CAttributeGroupIfNeeded (
					$this->getEntityProduct()->getAttributeSet()->getId()
				)
			;

		}


		/**
		 * Импорт значений настраиваемых опций
		 */
		foreach ($this->getEntityOffer()->getOptionValues() as $optionValue) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_OptionValue $optionValue */
			df_assert ($optionValue instanceof Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_OptionValue);


			/** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute */
			$attribute = $optionValue->getAttribute();

			df_assert ($attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute);


			Df_Catalog_Model_Installer_AddAttributeToSet
				::processStatic (
					$attribute->getAttributeCode()
					,
					$this->getEntityProduct()->getAttributeSet()->getId()
					,
					Df_1C_Const::PRODUCT_ATTRIBUTE_GROUP_NAME
				)
			;


			/** @var Mage_Eav_Model_Entity_Attribute_Option $option */
			$option = $optionValue->getOption();


			$result [$attribute->getName()] = df_string ($option->getData ('value'));

		}


		df_result_array ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getVisibilityAsString () {

		/** @var string $result  */
		$result =
			df_a (
				Mage_Catalog_Model_Product_Visibility::getOptionArray()
				,
				$this->getVisibility()
			)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Product_Type';
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


