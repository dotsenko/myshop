<?php


class Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products
	extends Df_Adminhtml_Block_Widget_Grid_Column_RendererDf {


	

	/**
	 * @return Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection
	 */
	public function getProducts () {
	
		if (!isset ($this->_products)) {
	
			/** @var Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection $result  */
			$result =
				df_model (
					Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection::getNameInMagentoFormat()
				)
			;
		
			df_assert ($result instanceof Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection);
	

			foreach ($this->getProductsData() as $productData) {

				/** @var array $productData */
				df_assert_array ($productData);

				/** @var Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $productBlock  */
				$productBlock =
					df_block (
						Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
							::getNameInMagentoFormat()
						,
						null
						,
						$productData
					)
				;

				df_assert ($productBlock instanceof Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product);


				$result->addItem ($productBlock);

			}


			$result
				->setOrder (
					df_cfg()->sales()->orderGrid()->productColumn()->getOrderBy()
					,
					df_cfg()->sales()->orderGrid()->productColumn()->getOrderDirection()
				)
			;


			$this->_products = $result;
	
		}
	
	
		df_assert ($this->_products instanceof Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection);
	
		return $this->_products;
	
	}
	
	
	/**
	* @var Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection
	*/
	private $_products;




	/**
	 * @return string
	 */
	public function getFieldNameWidthAsString () {

		if (!isset ($this->_fieldNameWidthAsString)) {


			/** @var string $result */
			$result =
				$this->formatWidthForCss (
						df_cfg()->sales()->orderGrid()->productColumn()->getNameWidth()
					*
						intval (
							df_cfg()->sales()->orderGrid()->productColumn()->needShowName()
						)
					*
						$this->getWidthNormalizationRatio()
				)
			;



			df_assert_string ($result);

			$this->_fieldNameWidthAsString = $result;

		}


		df_result_string ($this->_fieldNameWidthAsString);

		return $this->_fieldNameWidthAsString;

	}


	/**
	* @var string
	*/
	private $_fieldNameWidthAsString;






	/**
	 * @return string
	 */
	public function getFieldSkuWidthAsString () {

		if (!isset ($this->_fieldSkuWidthAsString)) {


			/** @var string $result */
			$result =
				$this->formatWidthForCss (
						df_cfg()->sales()->orderGrid()->productColumn()->getSkuWidth()
					*
						intval (
							df_cfg()->sales()->orderGrid()->productColumn()->needShowSku()
						)
					*
						$this->getWidthNormalizationRatio()
				)
			;



			df_assert_string ($result);

			$this->_fieldSkuWidthAsString = $result;

		}


		df_result_string ($this->_fieldSkuWidthAsString);

		return $this->_fieldSkuWidthAsString;

	}


	/**
	* @var string
	*/
	private $_fieldSkuWidthAsString;






	/**
	 * @return string
	 */
	public function getFieldQtyWidthAsString () {

		if (!isset ($this->_fieldQtyWidthAsString)) {


			/** @var string $result */
			$result =
				$this->formatWidthForCss (
						df_cfg()->sales()->orderGrid()->productColumn()->getQtyWidth()
					*
						intval (
							df_cfg()->sales()->orderGrid()->productColumn()->needShowQty()
						)
					*
						$this->getWidthNormalizationRatio()
				)
			;



			df_assert_string ($result);

			$this->_fieldQtyWidthAsString = $result;

		}


		df_result_string ($this->_fieldQtyWidthAsString);

		return $this->_fieldQtyWidthAsString;

	}


	/**
	* @var string
	*/
	private $_fieldQtyWidthAsString;







	/**
	 * @param float $widthAsFloat
	 * @return string
	 */
	private function formatWidthForCss ($widthAsFloat) {

		df_param_float ($widthAsFloat, 0);

		/** @var string $result  */
		$result =
			number_format (
				$widthAsFloat
				,
				2
				,
				'.'
				,
				''
			)
		;


		df_result_string ($result);

		return $result;

	}







	/**
	 * @return float
	 */
	private function getWidthNormalizationRatio () {

		if (!isset ($this->_widthNormalizationRatio)) {

			/** @var float $result  */
			$result =
				100.0 / floatval ($this->getTotalWidthPercent ())
			;


			df_assert_float ($result);

			$this->_widthNormalizationRatio = $result;

		}


		df_result_float ($this->_widthNormalizationRatio);

		return $this->_widthNormalizationRatio;

	}


	/**
	* @var float
	*/
	private $_widthNormalizationRatio;






	/**
	 * @return int
	 */
	private function getTotalWidthPercent () {

		if (!isset ($this->_totalWidthPercent)) {


			/** @var int $result  */
			$result =
						df_cfg()->sales()->orderGrid()->productColumn()->getNameWidth()
					*
						intval (
							df_cfg()->sales()->orderGrid()->productColumn()->needShowName()
						)

				+

						df_cfg()->sales()->orderGrid()->productColumn()->getSkuWidth()
					*
						intval (
							df_cfg()->sales()->orderGrid()->productColumn()->needShowSku()
						)

				+

						df_cfg()->sales()->orderGrid()->productColumn()->getQtyWidth()
					*
						intval (
							df_cfg()->sales()->orderGrid()->productColumn()->needShowQty()
						)
			;


			df_assert_integer ($result);

			$this->_totalWidthPercent = $result;

		}


		df_result_integer ($this->_totalWidthPercent);

		return $this->_totalWidthPercent;

	}


	/**
	* @var int
	*/
	private $_totalWidthPercent;







	/**
	 * @return array
	 */
	private function getProductsData () {
	
		if (!isset ($this->_productsData)) {


			/** @var array $map  */
			$map =
				array (
							Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection
								::COLLECTION_ITEM_PARAM__DF_NAMES
						=>
							Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
								::PARAM__PRODUCT_NAME
					,
							Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection
								::COLLECTION_ITEM_PARAM__DF_SKUS
						=>
							Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
								::PARAM__PRODUCT_SKU
					,
							Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection
								::COLLECTION_ITEM_PARAM__DF_QTYS
						=>
							Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
								::PARAM__PRODUCT_QTY

					,
							Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection
								::COLLECTION_ITEM_PARAM__DF_TOTALS
						=>
							Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
								::PARAM__ROW_TOTAL

					,
							Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection
								::COLLECTION_ITEM_PARAM__DF_PRODUCT_IDS
						=>
							Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
								::PARAM__PRODUCT_ID


					,
							Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection
								::COLLECTION_ITEM_PARAM__DF_ORDER_ITEM_IDS
						=>
							Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
								::PARAM__ORDER_ITEM_ID


					,
							Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection
								::COLLECTION_ITEM_PARAM__DF_PARENTS
						=>
							self::COLLECTION_ITEM_PARAM__PARENT_ID
				)
			;


			/** @var array $sourceKeys  */
			$sourceKeys = array_keys ($map);

			df_assert_array ($sourceKeys);



			/** @var array $targetKeys  */
			$targetKeys = array_values ($map);

			df_assert_array ($targetKeys);



			/** @var array $parsedValues  */
			$parsedValues =
				df_array_combine (
					$targetKeys
					,
					array_map (
						array ($this, 'parseConcatenatedValues')
						,
						$sourceKeys
					)
				)
			;

			df_assert_array ($parsedValues);

	
			/** @var array $result  */
			$result =
				array ()
			;


			/** @var int $numProducts  */
			$numProducts =
				count (
					df_a (
						$parsedValues
						,
						Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
							::PARAM__PRODUCT_NAME
					)
				)
			;

			df_assert_integer ($numProducts);



			for ($productOrdering = 0; $productOrdering < $numProducts; $productOrdering++) {

				/** @var array $product  */
				$product =
					array ()
				;

				foreach ($targetKeys as $key) {

					/** @var string $key */
					df_assert_string ($key);


					/** @var array $attributeValues  */
					$attributeValues = df_a ($parsedValues, $key);

					df_assert_array ($attributeValues);


					$product [$key] = df_a ($attributeValues, $productOrdering);

				}

				$result
						[
							intval (
								df_a (
									$product
									,
									Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
										::PARAM__ORDER_ITEM_ID
								)
							)
						]
					=
						$product
				;

			}

			$result = $this->removeParents ($result);
	
			df_assert_array ($result);
	
			$this->_productsData = $result;
	
		}
	
	
		df_result_array ($this->_productsData);
	
		return $this->_productsData;
	
	}

	
	
	/**
	* @var array
	*/
	private $_productsData;






	/**
	 * @param array $products
	 * @return array
	 */
	private function removeParents (array $products) {

		/** @var array $idsToRemove  */
		$idsToRemove =
			array ()
		;



		/** @var array $result  */
		$result =
			array ()
		;


		foreach ($products as $id => $product) {

			/** @var int $id */
			df_assert_integer ($id);

			/** @var array $product */
			df_assert_array ($product);


			/** @var int $parentId  */
			$parentId =
				intval (
					df_a (
						$product
						,
						self::COLLECTION_ITEM_PARAM__PARENT_ID
					)
				)
			;

			df_assert_integer ($parentId);

			if (0 !== $parentId) {

				$idsToRemove []= $parentId;

			}
		}



		foreach ($products as $id => $product) {

			/** @var int $id */
			df_assert_integer ($id);

			/** @var array $product */
			df_assert_array ($product);


			if (!in_array ($id, $idsToRemove)) {

				$result [$id]= $product;

			}
		}

		df_result_array ($result);

		return $result;

	}






	/**
	 * @param string $key
	 * @return array
	 */
	public function parseConcatenatedValues ($key) {

		df_param_string ($key, 0);

		/** @var array $result  */
		$result =
			df_parse_csv (
				$this->getRowParam (
					$key
				)
				,
				Df_Core_Const::T_UNIQUE_SEPARATOR
			)
		;

		df_result_array ($result);

		return $result;
	}




	/**
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result =
				parent::needToShow()
			&&
				(0 < count ($this->getProducts()))
		;

		df_result_boolean ($result);

		return $result;

	}
	
	
	
	
	
	/**
	 * @return string|null
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result =
			self::DEFAULT_TEMPLATE
		;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}


	const COLLECTION_ITEM_PARAM__PARENT_ID = 'parent_id';

	const DEFAULT_TEMPLATE = 'df/sales/widget/grid/column/renderer/products.phtml';
	
	
	
	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products';
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


