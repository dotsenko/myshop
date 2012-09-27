<?php


class Df_Catalog_Model_Convert_Adapter_Product extends Mage_Catalog_Model_Convert_Adapter_Product {


	/**
	 * Dataflow import
	 *
	 * @param array $importData
	 * @return bool
	 */
    public function saveRow(array $importData) {
	    return
	            // Используем наши улучшения импорта только для товаров,
			    // которые ипортируются в лицензированные магазины
 	            df_enabled (Df_Core_Feature::DATAFLOW, $this->getStoreId_Df ($importData))
			?
				$this->dfSaveRow ($importData)
			:
		       parent::saveRow ($importData)
	    ;
    }



	private function getStoreId_Df (array $importData) {
		return df_a ($importData, "store", $this->getBatchParams('store'));
	}



	/**
	 * Dataflow import
	 *
	 * @throws Exception
	 * @param array $importData
	 * @return bool
	 */
    public function dfSaveRow(array $importData) {

        $product = $this->getProductModel()
            ->reset();

	    $store = false;
        if (empty($importData['store'])) {
            if (!is_null($this->getBatchParams('store'))) {
                $store = $this->getStoreById($this->getBatchParams('store'));
            } else {
                $message = df_mage()->catalogHelper()->__(
                    'Skipping import row, required field "%s" is not defined.',
                    'store'
                );
                Mage::throwException($message);
            }
        }
        else {
            $store = $this->getStoreByCode($importData['store']);
        }

        if ($store === false) {
            $message = df_mage()->catalogHelper()->__(
                'Skipping import row, store "%s" field does not exist.',
                $importData['store']
            );
            Mage::throwException($message);
        }

        if (empty($importData['sku'])) {
            $message = df_mage()->catalogHelper()->__('Skipping import row, required field "%s" is not defined.', 'sku');
            Mage::throwException($message);
        }
        $product->setStoreId($store->getId());
        $productId = $product->getIdBySku($importData['sku']);

        if ($productId) {
            $product->load($productId);
        }
        else {
            $productTypes = $this->getProductTypes();
            $productAttributeSets = $this->getProductAttributeSets();

            /**
             * Check product define type
             */
            if (empty($importData['type']) || !isset($productTypes[strtolower($importData['type'])])) {
                $value = isset($importData['type']) ? $importData['type'] : '';
                $message = df_mage()->catalogHelper()->__(
                    'Skip import row, is not valid value "%s" for field "%s"',
                    $value,
                    'type'
                );
                Mage::throwException($message);
            }
            $product->setTypeId($productTypes[strtolower($importData['type'])]);
            /**
             * Check product define attribute set
             */
            if (empty($importData['attribute_set']) || !isset($productAttributeSets[$importData['attribute_set']])) {
                $value = isset($importData['attribute_set']) ? $importData['attribute_set'] : '';
                $message = df_mage()->catalogHelper()->__(
                    'Skip import row, the value "%s" is invalid for field "%s"',
                    $value,
                    'attribute_set'
                );
                Mage::throwException($message);
            }
            $product->setAttributeSetId($productAttributeSets[$importData['attribute_set']]);

            foreach ($this->_requiredFields as $field) {
                $attribute = $this->getAttribute($field);
                if (!isset($importData[$field]) && $attribute && $attribute->getIsRequired()) {
                    $message = df_mage()->catalogHelper()->__(
                        'Skipping import row, required field "%s" for new products is not defined.',
                        $field
                    );
                    Mage::throwException($message);
                }
            }
        }

        $this->setProductTypeInstance($product);

        if (isset($importData['category_ids'])) {
            $product->setCategoryIds($importData['category_ids']);
        }


        if (df_enabled (Df_Core_Feature::DATAFLOW_CATEGORIES, $this->getStoreId_Df ($importData)) && df_cfg()->dataflow()->products()->getEnhancedCategorySupport()) {
			// BEGIN PATCH: Import categories in various formats


			/** @var Df_Dataflow_Model_Importer_Product_Categories $categoriesImporter  */
			$categoriesImporter =
				df_model (
					Df_Dataflow_Model_Importer_Product_Categories::getNameInMagentoFormat()
					,
					array (
						Df_Dataflow_Model_Importer_Product_Specialized
							::PARAM_PRODUCT => $product
						,
						Df_Dataflow_Model_Importer_Product_Specialized
							::PARAM_IMPORTED_ROW => $importData
						,
						Df_Dataflow_Model_Importer_Product_Categories
							::PARAM_STORE => $store
					)
				)
			;

			df_assert ($categoriesImporter instanceof Df_Dataflow_Model_Importer_Product_Categories);

			$categoriesImporter
				->process ()
			;
			// END PATCH: Import categories in various formats
        }


        foreach ($this->_ignoreFields as $field) {
            if (isset($importData[$field])) {
                unset($importData[$field]);
            }
        }

        if ($store->getId() != 0) {
            $websiteIds = $product->getWebsiteIds();
            if (!is_array($websiteIds)) {
                $websiteIds = array();
            }
            if (!in_array($store->getWebsiteId(), $websiteIds)) {
                $websiteIds[] = $store->getWebsiteId();
            }
            $product->setWebsiteIds($websiteIds);
        }

        if (isset($importData['websites'])) {
            $websiteIds = $product->getWebsiteIds();
            if (!is_array($websiteIds) || !$store->getId()) {
                $websiteIds = array();
            }
            $websiteCodes = explode(',', $importData['websites']);
            foreach ($websiteCodes as $websiteCode) {
                try {
                    $website = Mage::app()->getWebsite(trim($websiteCode));
                    if (!in_array($website->getId(), $websiteIds)) {
                        $websiteIds[] = $website->getId();
                    }
                }
                catch (Exception $e) {}
            }
            $product->setWebsiteIds($websiteIds);
            unset($websiteIds);
        }





		/********************************************************************
		 * Заплатка для локали.
		 * Стандартный программный код приводит к проблеме:
		 * если в импортируемом файле значения опций записаны в фомате одной локали,
		 * а при импорте установлена другая локаль, то значения не будут распознаны.
		 *
		 * Данная заплатка позволяет администратору устанавливать локаль для опций
		 * в профиле Magento Dataflow.
		 *
		 * Пример:
		 *
			<action type="vichy_dataflow/import_products_parser" method="parse">
				<var name="adapter">catalog/convert_adapter_product</var>
				<var name="method">parse</var>
				<var name="locale">en_US</var>
			</action>
		 */


		/** @var string $originalLocaleCode */
		$originalLocaleCode = df_mage()->core()->translate()->getLocale ();

		/** @var Exception $exception  */
		$exception = NULL;

		df_assert_string ($originalLocaleCode);


		/** @var string|null $localeCodeFromBatchParams  */
		$localeCodeFromBatchParams = df_a ($this->getBatchParams(), 'locale');

		if (!is_null ($localeCodeFromBatchParams)) {
			df_assert_string ($localeCodeFromBatchParams);

			df_mage()->core()->translate()
				->setLocale ($localeCodeFromBatchParams)
				->init('adminhtml', true)
			;
		}


		try {

			foreach ($importData as $field => $value) {
				if (in_array($field, $this->_inventoryFields)) {
					continue;
				}
				if (in_array($field, $this->_imageFields)) {
					continue;
				}
				if (is_null($value)) {
					continue;
				}


				$attribute = $this->getAttribute($field);
				if (!$attribute) {
					continue;
				}

				$isArray = false;
				$setValue = $value;

				if ($attribute->getFrontendInput() == 'multiselect') {
					$value = explode(self::MULTI_DELIMITER, $value);
					$isArray = true;
					$setValue = array();
				}

				if ($value && $attribute->getBackendType() == 'decimal') {
					$setValue = $this->getNumber($value);
				}

				if ($attribute->usesSource()) {

					/**
					 * Данный программный код приводит к проблеме:
					 * если в импортируемом файле значения опции записаны в фомате одной локали,
					 * а при импорте установлена другая локаль, то значения не будут распознаны.
					 */
					$options = $attribute->getSource()->getAllOptions(false);

					if ($isArray) {
						foreach ($options as $item) {
							if (in_array($item['label'], $value)) {
								$setValue[] = $item['value'];
							}
						}
					} else {
						$setValue = false;
						foreach ($options as $item) {
							if ($item['label'] == $value) {
								$setValue = $item['value'];
							}
						}
					}
				}

				$product->setData($field, $setValue);
			}

		}

		/***************************
		 * Заключительная часть заплатки для локали
		 */

		catch (Exception $e) {
			$exception = $e;
		}


		if ($originalLocaleCode != df_mage()->core()->translate()->getLocale()) {

			df_mage()->core()->translate()
				->setLocale ($originalLocaleCode)
				->init('adminhtml', true)
			;

		}


		if (!is_null ($exception)) {
			throw $exception;
		}


		/***************************
		 * Конец заплатки для локали
		 */




        if (!$product->getVisibility()) {
            $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE);
        }

        $stockData = array();
        $inventoryFields = isset($this->_inventoryFieldsProductTypes[$product->getTypeId()])
            ? $this->_inventoryFieldsProductTypes[$product->getTypeId()]
            : array();
        foreach ($inventoryFields as $field) {
            if (isset($importData[$field])) {
                if (in_array($field, $this->_toNumber)) {
                    $stockData[$field] = $this->getNumber($importData[$field]);
                }
                else {
                    $stockData[$field] = $importData[$field];
                }
            }
        }
        $product->setStockData($stockData);

        $product->setIsMassupdate(true);
        $product->setExcludeUrlRewrite(true);

        $product->save();





        // BEGIN PATCH: import images

	    /** @var $product Df_Catalog_Model_Product */
        $product = df_model("df_catalog/product")->load ($product->getId ());

	    /** @var $galleryImporter Df_Dataflow_Model_Importer_Product_Gallery */
	    $galleryImporter =
			df_model (
				Df_Dataflow_Model_Importer_Product_Gallery::getNameInMagentoFormat ()
				,
				array (
					Df_Dataflow_Model_Importer_Product_Gallery::PARAM_PRODUCT => $product
					,
					Df_Dataflow_Model_Importer_Product_Gallery::PARAM_IMPORTED_ROW => $importData
				)
			)
		;

		$imageData =
			$galleryImporter->getPrimaryImages ()
		;

	    // Do something only if there are some new images!
	    if (!empty ($imageData)) {
			if (df_enabled (Df_Core_Feature::DATAFLOW_IMAGES, $this->getStoreId_Df ($importData)) && df_cfg()->dataflow()->products()->getDeletePreviousImages()) {
				//remove previous images
				$product
					->deleteImages ()
				;
			}

			foreach ($imageData as $file => $fields) {
				$imagePath =
					Mage::getBaseDir('media') . DS . 'import' . trim ($file)
				;

				if (!is_file ($imagePath)) {
					throw new Exception (
						sprintf (
							"Image file %s does not exist"
							,
							$imagePath
						)
					)
					;
				}

				try {
					$product->addImageToMediaGallery($imagePath, array('thumbnail','small_image','image'), false, false);
				}
				catch (Exception $e) {
					df_handle_entry_point_exception ($e, false);
				}
			}


			if (df_enabled (Df_Core_Feature::DATAFLOW_IMAGES, $this->getStoreId_Df ($importData)) && df_cfg()->dataflow()->products()->getGallerySupport()) {
				// BEGIN PATCH 2: Import of additional images
				$galleryImporter->addAdditionalImagesToProduct ();
				// END PATCH 2: Import of additional images
			}

			$product->save ();
	    }
	    // END PATCH: import images






	    // BEGIN PATCH: Import of custom options
        if (df_enabled (Df_Core_Feature::DATAFLOW_CO, $this->getStoreId_Df ($importData)) && df_cfg()->dataflow()->products()->getCustomOptionsSupport ()) {

			$product = df_model("catalog/product")->load ($product->getId ());


			/** @var Df_Dataflow_Model_Importer_Product_Options $customOptionsImporter  */
			$customOptionsImporter =
				df_model (
					Df_Dataflow_Model_Importer_Product_Options::getNameInMagentoFormat()
					,
					array (
						Df_Dataflow_Model_Importer_Product_Specialized
							::PARAM_PRODUCT => $product

						,
						Df_Dataflow_Model_Importer_Product_Specialized
							::PARAM_IMPORTED_ROW => $importData
					)
				)
			;

			df_assert ($customOptionsImporter instanceof Df_Dataflow_Model_Importer_Product_Options);


			$customOptionsImporter->process ();

			$product->save ();

        }
	    // END PATCH: Import of custom options


        return true;
    }




}