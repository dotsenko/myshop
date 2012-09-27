<?php

class Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable
	extends Df_1C_Model_Cml2_Import_Processor_Product_Type {



	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Processor
	 */
	public function process () {

		if ($this->getEntityOffer()->isTypeConfigurableParent()) {

			/**
			 * Сначала импортируем настраиваемые варианты в виде простых товаров
			 */
			$this->importChildren();

			/**
			 * Затем создаём настраиваемый товар
			 */
			$this->importParent();

		}

		return $this;

	}




	/**
	 * @override
	 * @return float
	 */
	protected function getPrice () {

		if (!isset ($this->_price)) {

			/** @var float $result  */
			$result = null;

			foreach ($this->getEntityOffer()->getConfigurableChildren() as $offer) {

				/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $offer */
				df_assert ($offer instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);

				/** @var float|null $currentPrice  */
				$currentPrice = $offer->getProduct()->getPrice();

				if (!is_null ($currentPrice)) {
					if (
							is_null ($result)
						||
							$result > floatval ($currentPrice)
					) {
						$result = floatval ($currentPrice);
					}
				}
			}

			df_assert_float ($result);

			$this->_price = $result;

		}

		df_result_float ($this->_price);

		return $this->_price;

	}


	/**
	* @var float
	*/
	private $_price;




	/**
	 * @return array
	 */
	private function getProductDataNewOrUpdateAttributeValueIdsCustom () {

		/** @var array $result  */
		$result = array ();


		foreach ($this->getEntityProduct()->getAttributeValuesCustom() as $attributeValue) {

			if (!is_null ($attributeValue->getAttribute())) {

				/** @var Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom $attributeValue */
				df_assert ($attributeValue instanceof Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom);

				$result [$attributeValue->getAttributeName()] =
					$attributeValue->getValueForObject()
				;

			}

		}


		df_result_array ($result);

		return $result;

	}






	/**
	 * @override
	 * @return string
	 */
	protected function getSku () {

		if (!isset ($this->_sku)) {

			/** @var string $result  */
			$result = null;

 			if (!is_null ($this->getExistingMagentoProduct())) {
				$result = $this->getExistingMagentoProduct()->getSku();
			}
			else {
				$result = $this->getEntityProduct()->getSku();

				if (is_null($result)) {
					df_helper()->_1c()
						->log (
							sprintf (
								'У товара %s в 1С отсутствует артикул.'
								,
								$this->getEntityProduct()->getName()
							)
						)
					;

					$result = $this->getEntityOffer()->getExternalId();
				}


				if (df_helper()->catalog()->product()->getIdBySku($result)) {
					/**
					 * Вдруг товар с данным артикулом уже присутствует в системе?
					 */

					df_helper()->_1c()
						->log (
							sprintf (
								'Товар с артикулом %s уже присутствует в магазине.'
								,
								$result
							)
						)
					;


					df_assert (
						$result !== $this->getEntityOffer()->getExternalId()
					);

					$result = $this->getEntityOffer()->getExternalId();
				}

			}


			df_assert_string ($result);

			$this->_sku = $result;

		}


		df_result_string ($this->_sku);

		return $this->_sku;

	}


	/**
	* @var string
	*/
	private $_sku;






	/**
	 * @override
	 * @return string
	 */
	protected function getType () {
		return Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE;
	}


	
	
	/**
	 * @return array
	 */
	private function getUsedProductAttributeIds () {
	
		if (!isset ($this->_usedProductAttributeIds)) {
	
			/** @var array $result  */
			$result = array ();


			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer|bool $firstChild  */
			$firstChild = df_array_first ($this->getEntityOffer()->getConfigurableChildren());

			if (false !== $firstChild) {

				df_assert ($firstChild instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);


				foreach ($firstChild->getOptionValues() as $optionValue) {

					/** @var Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_OptionValue $optionValue */
					df_assert ($optionValue instanceof Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_OptionValue);


					/** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute */
					$attribute = $optionValue->getAttribute();

					df_assert ($attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute);


					$result [$attribute->getName()] = $attribute->getId();
					
				}

			}

	
			df_assert_array ($result);
	
			$this->_usedProductAttributeIds = $result;
	
		}
	
	
		df_result_array ($this->_usedProductAttributeIds);
	
		return $this->_usedProductAttributeIds;
	
	}
	
	
	/**
	* @var array
	*/
	private $_usedProductAttributeIds;	
	




	/**
	 * @return Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable
	 */
	private function importChildren () {

		foreach ($this->getEntityOffer()->getConfigurableChildren() as $offer) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $offer */
			df_assert ($offer instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);


			/** @var Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable_Child $processor */
			$processor =
				df_model (
					Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable_Child::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable_Child
							::PARAM__ENTITY => $offer
					)
				)
			;

			df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable_Child);


			$processor->process();

		}

		return $this;

	}
	
	



	/**
	 * @return Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable
	 */
	private function importParent () {

		if (is_null ($this->getExistingMagentoProduct())) {
			$this->importParentNew();
		}
		else {
			$this->importParentUpdate();
		}
		return $this;

	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable
	 */
	private function importParentNew () {

		$this->getImporter()->import();

		/** @var Mage_Catalog_Model_Product $product */
		$product = $this->getImporter()->getProduct();

		df_assert ($product instanceof Mage_Catalog_Model_Product);


		$product
			->addData (
				array (
					'can_save_configurable_attributes' => true
					,
					'can_save_custom_options' => true
				)
			)
		;


		/** @var Mage_Catalog_Model_Product_Type_Configurable $productTypeConfigurable */
		$productTypeConfigurable = $product->getTypeInstance();

		df_assert ($productTypeConfigurable instanceof Mage_Catalog_Model_Product_Type_Configurable);


		// This array is is an array of attribute ID's
		// which the configurable product swings around
		// (i.e; where you say when you create a configurable product in the admin area
		// what attributes to use as options)
		// $_attributeIds is an array which maps the attribute(s) used for configuration
		// to their numerical counterparts.
		// (there's probably a better way of doing this, but i was lazy, and it saved extra db calls);
		// $_attributeIds = array("size" => 999, "color", => 1000, "material" => 1001); // etc..

		$productTypeConfigurable
			->setUsedProductAttributeIds (
				$this->getUsedProductAttributeIds ()
			)
		;

		// Now we need to get the information back in Magento's own format,
		// and add bits of data to what it gives us..
		$attributes_array = $productTypeConfigurable->getConfigurableAttributesAsArray();
		foreach($attributes_array as $key => $attribute_array) {
			$attributes_array[$key]['use_default'] = 1;
			$attributes_array[$key]['position'] = 0;

			if (isset($attribute_array['frontend_label'])) {
				$attributes_array[$key]['label'] = $attribute_array['frontend_label'];
			}
			else {
				$attributes_array[$key]['label'] = $attribute_array['attribute_code'];
			}
		}

		// Add it back to the configurable product..
		$product->setData ('configurable_attributes_data', $attributes_array);



		// Remember that $simpleProducts array we created earlier? Now we need that data..
		$dataArray = array();

		foreach ($this->getEntityOffer()->getConfigurableChildren() as $offer) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $offer */
			df_assert ($offer instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);


			/** @var Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_OptionValue|null $firstOption  */
			$firstOption = null;

			foreach ($offer->getOptionValues() as $optionValue) {
				$firstOption = $optionValue;
				break;
			}

			if (!is_null ($firstOption)) {
				df_assert ($firstOption instanceof Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_OptionValue);

				/** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute */
				$attribute = $firstOption->getAttribute();

				df_assert ($attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute);

				$dataArray[$offer->getProduct()->getId()] =
					array (
						'attribute_id' => $attribute->getId(),
						'label' => df_string ($firstOption->getData ('value')),
						'is_percent' => false,
						'pricing_value' => $offer->getProduct()->getPrice()
					)
				;

			}
		}

		// This tells Magento to associate the given simple products to this configurable product..
		$product->setData ('configurable_products_data', $dataArray);



		// Set stock data. Yes, it needs stock data. No qty, but we need to tell it to manage stock, and that it's actually
		// in stock, else we'll end up with problems later..
		$product
			->setData (
				'stock_data'
				,
				array (
					'use_config_manage_stock' => 1,
					'is_in_stock' => 1,
					'is_salable' => 1
				)
			)
		;

		df_helper()->catalog()->product()
			->save (
				$product
				,
				$isMassUpdate = true
			)
		;

		$product = df_helper()->catalog()->product()->reload ($product);


		df_helper()->_1c()
			->log (
				sprintf (
					'Создан товар «%s».'
					,
					$product->getName()
				)
			)
		;


		df_helper()->dataflow()->registry()->products()->addEntity ($product);


		return $this;

	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable
	 */
	private function importParentUpdate () {

		$this->getExistingMagentoProduct()
			->addData (
				array_merge (
					$this->getProductDataNewOrUpdateAttributeValueIdsCustom()
					,
					$this->getProductDataNewOrUpdateBase()
					,
					$this->getProductDataUpdateOnly()
				)
			)
		;


		/**
		 * Код выше уже установил товару значение свойства category_ids,
		 * но в данном контексте — неправильно, в виде строки.
		 * Устанавливаем по-правильному.
		 */
		$this->getExistingMagentoProduct()
			->setCategoryIds (
				array ($this->getEntityProduct()->getCategory()->getId())
			)
		;


		df_helper()->catalog()->product()
			->save (
				$this->getExistingMagentoProduct()
				,
				$isMassUpdate = true
			)
		;

		df_helper()->dataflow()->registry()->products()
			->addEntity (
				df_helper()->catalog()->product()->reload (
					$this->getExistingMagentoProduct()
				)
			)
		;



		df_helper()->_1c()
			->log (
				sprintf (
					'Обновлён товар «%s».'
					,
					$this->getExistingMagentoProduct()->getName()
				)
			)
		;


		return $this;

	}





	/**
	 * @override
	 * @return int
	 */
	protected function getVisibility () {
		return Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable';
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


