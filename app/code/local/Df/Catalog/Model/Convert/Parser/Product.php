<?php


class Df_Catalog_Model_Convert_Parser_Product extends Mage_Catalog_Model_Convert_Parser_Product {


	/**
	 * @param  $value
	 * @return array
	 */
	public function prepareForSerialization ($value) {
		return
				is_array ($value) && !empty ($value)
			?
				df_array_combine (
					array_keys (
						$value
					)
					,
					array_map (
						array ($this, "prepareForSerialization")
						,
						$value
					)
				)
			:
				(
						(is_object ($value) && method_exists ($value, "toArray"))
					?
						$value->toArray ()
					:
						$value
				)
		;
	}


	/**
	 * @var Mage_Catalog_Model_Product
	 */
	private $_currentProduct;

	/**
	 * Dataflow export
	 *
	 * @return Df_Catalog_Model_Convert_Parser_Product
	 */
    public function unparse()
    {
		// Используем наши улучшения экспорта только для товаров,
		// которые экспорта из лицензированных магазинов
	    if (!df_enabled (Df_Core_Feature::DATAFLOW, $this->getStoreId())) {
			parent::unparse ();
        }
        else {
			try {
				$this->unparseRm();
			}
			catch (Exception $e) {
				$this->addException ($e);
				df_handle_entry_point_exception ($e, false);
			}
        }

        return $this;
    }



	/**
	 * @param  Mage_Catalog_Model_Product $product
	 * @param  array $row
	 * @return Df_Catalog_Model_Convert_Parser_Product
	 */
	private function unparseCustomOptions (Mage_Catalog_Model_Product $product, array &$row) {
		if (!df_empty ($product->getOptions ())) {
			$optionsForSerialization = array ();
			foreach ($product->getOptions () as $optionKey => $option) {

				$optionsForSerialization [$optionKey]=
					$option
						->setData (
							"_values"
							,
							$this->prepareForSerialization (
								$option->getValues ()
							)
						)
						->toArray ()
				;

				$optionsForSerialization [$optionKey]=
					$option
						->setData (
							"_options"
							,
							$this->prepareForSerialization (
								$option->getOptions ()
							)
						)
						->toArray ()
				;
			}

			$row['df_custom_options'] =
				sprintf (
					//"<![CDATA[%s]]>"
					"%s"
					,
					/**
					 * Метод Zend_Json::prettyPrint отсутствует в Magento 1.4.0.1
					 * Однако, мы можем поддержать его в более поздних версиях
					 * через свой нестандартный фильтр
					 */
					df_json_pretty_print (
						df_text()->adjustCyrillicInJson (
							Zend_Json::encode(
								$optionsForSerialization
							)
						)
					)
				)
			;
		}
		return $this;
	}




	/**
	 * @return Df_Catalog_Model_Convert_Parser_Product
	 */
	private function unparseRm () {

		$entityIds = $this->getData();

		foreach ($entityIds as $i => $entityId) {
			$product = $this->getProductModel()
				->setStoreId($this->getStoreId())
				->load($entityId);
			$this->setProductTypeInstance($product);
			$this->_currentProduct = $product;
			/* @var $product Mage_Catalog_Model_Product */

			$position = df_mage()->catalogHelper()->__('Line %d, SKU: %s', ($i+1), $product->getSku());
			$this->setPosition($position);

			$row = array(
				'store'         => $this->getStore()->getCode(),
				'websites'      => '',
				'attribute_set' => $this->getAttributeSetName($product->getEntityTypeId(), $product->getAttributeSetId()),
				'type'          => $product->getTypeId(),
				'category_ids'  => join(',', $product->getCategoryIds())
			);

			if ($this->getStore()->getCode() == Mage_Core_Model_Store::ADMIN_CODE) {
				$websiteCodes = array();
				foreach ($product->getWebsiteIds() as $websiteId) {
					$websiteCode = Mage::app()->getWebsite($websiteId)->getCode();
					$websiteCodes[$websiteCode] = $websiteCode;
				}
				$row['websites'] = join(',', $websiteCodes);
			}
			else {
				$row['websites'] = $this->getStore()->getWebsite()->getCode();
				if ($this->getVar('url_field')) {
					$row['url'] = $product->getProductUrl(false);
				}
			}

			foreach ($product->getData() as $field => $value) {
				if (in_array($field, $this->_systemFields) || is_object($value)) {
					continue;
				}

				$attribute = $this->getAttribute($field);
				if (!$attribute) {
					continue;
				}

				if ($attribute->usesSource()) {
					$option = $attribute->getSource()->getOptionText($value);
					if ($value && empty($option)) {
						$message = df_mage()->catalogHelper()->__("Invalid option ID specified for %s (%s), skipping the record.", $field, $value);
						$this->addException($message, Mage_Dataflow_Model_Convert_Exception::ERROR);
						continue;
					}
					if (is_array($option)) {
						$value = join(self::MULTI_DELIMITER, $option);
					} else {
						$value = $option;
					}
					unset($option);
				}
				elseif (is_array($value)) {
					continue;
				}

				$row[$field] = $value;
			}

			if ($stockItem = $product->getStockItem()) {
				foreach ($stockItem->getData() as $field => $value) {
					if (in_array($field, $this->_systemFields) || is_object($value)) {
						continue;
					}
					$row[$field] = $value;
				}
			}

			foreach ($this->getImageFields () as $field) {
				if (isset($row[$field])) {
					if ('no_selection' == $row[$field])  {
						$row[$field] = null;
					}
					else {
						$row[$field] = $this->adjustImagePath ($row[$field]);
					}
				}
			}




			// BEGIN PATCH: Export of additional images
		    if (df_enabled (Df_Core_Feature::DATAFLOW_IMAGES, $this->getStoreId()) && df_cfg()->dataflow()->products()->getGallerySupport()) {
				$row['df_additional_images'] =
					$this->getAdditionalImagesAsString (
						array (
							"product" => $product
							,
							"row" => $row
						)
					)
				;
		    }
			// END PATCH: Export of additional images





			// BEGIN PATCH: Export of Custom Options
		    if (df_enabled (Df_Core_Feature::DATAFLOW_CO, $this->getStoreId()) && df_cfg()->dataflow()->products()->getCustomOptionsSupport ()) {
				$this->unparseCustomOptions ($product, $row);
		    }
			// END PATCH: Export of Custom Options






			// BEGIN PATCH: Export of product categories
		    if (df_enabled (Df_Core_Feature::DATAFLOW_CATEGORIES, $this->getStoreId()) && df_cfg()->dataflow()->products()->getEnhancedCategorySupport()) {
				$row['df_categories'] =
					Mage
						::getModel (
							"df_dataflow/exporter_product_categories"
							,
							array (
								"product" => $product
							)
						)
							->process ()
				;

		    }
			// END PATCH: Export of product categories






			$this->getBatchExportModel()
				->setId(null)
				->setBatchId($this->getBatchModel()->getId())
				->setBatchData($row)
				->setStatus(1)
				->save();
			$product->reset();
		}

		return $this;
	}




	/**
	 * @param array $params
	 * @return string
	 */
	private function getAdditionalImagesAsString (array $params) {
		$product = df_a ($params, "product");
		$row = df_a ($params, "row");

		$mediaGallery = $product->getMediaGallery ();
		$images = $mediaGallery['images'];
		$imagesAsArray = array ();
		foreach (
			$images as $image
		) {
			if (!$image['disabled']) {

				/** @var string $imagePath */
				$imagePath = df_a ($image, 'file');

				df_assert_string ($imagePath);

				$imagePath = $this->adjustImagePath ($imagePath);

				if (!(in_array ($imagePath, $this->getPrimaryImages ($row)))) {
					$imagesAsArray []= $imagePath;
				}
			}
		}
		return implode (";", $imagesAsArray);
	}



	/**
	 * @param array $row
	 * @return array
	 */
	private function getPrimaryImages (array $row) {
		$result = array ();
		foreach ($this->getImageFields () as $field) {
			$image = df_a ($row, $field);
			if (!empty ($image)) {
				$result []= $this->adjustImagePath ($image);
			}
		}
		$result =
			array_unique (
				$result
			)
		;
		return $result;
	}




	/**
	 * @return array
	 */
	private function getImageFields () {

		/** @var array $result  */
		$result =
				/**
				 * Метод Mage_Catalog_Model_Product::getMediaAttributes()
				 * присутствует в том числе и в Magento 1.4.0.1,
				 * так что, вероятно, условное ветвление можно убрать.
				 */
				df_magento_version ("1.5", "<")
			?
				$this->_imageFields
			:
				array_keys (
					$this->_currentProduct->getMediaAttributes ()
				)
		;


		df_result_array ($result);

		return $result;

	}






	/**
	 * @param  string $imagePath
	 * @return string
	 */
	private function adjustImagePath ($imagePath) {

		df_param_string ($imagePath, 0);

		/** @var string $result */
		$result = Df_Core_Const::T_EMPTY;

		if (!df_empty($result)) {

			/** @var string $result */
			$result =
					('/' === $imagePath [0])
				?
					$imagePath
				:
					'/' . $imagePath
			;

		}

		df_result_string ($result);

		return $result;
	}

}
