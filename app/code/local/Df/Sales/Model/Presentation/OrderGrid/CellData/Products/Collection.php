<?php


class Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection
		extends Df_Varien_Data_Collection {



    /**
     * Set select order
     *
	 * @override
     * @param   string $field
     * @param   string $direction [optional]
     * @return  Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection
     */
	public function setOrder ($field, $direction = self::SORT_ORDER_DESC) {

		df_param_string ($field, 0);
		df_param_string ($direction, 1);

		parent::setOrder ($field, $direction);


		/** @var string $method  */
		$method =
			implode (
				Df_Core_Const::T_EMPTY
				,
				array (
					'compareBy'
					,
					df_text()->camelize (
						$field
					)
				)
			)
		;

		df_assert_string ($method);


		df_assert (
			method_exists ($this, $method)
		)
		;


		uasort (
			$this->_items
			,
			array (
				$this, $method
			)
		)
		;


		if (self::SORT_ORDER_DESC === $direction) {
			$this->_items =
				array_reverse (
					$this->_items
					,
					true
				)
			;
		}

		return $this;
	}







	/**
	 * @param Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product1
	 * @param Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product2
	 * @return void
	 */
	public function compareByName (
		Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product1
		,
		Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product2
	) {

		/** @var int $result  */
		$result =
			strcmp (
				$product1->getProductName()
				,
				$product2->getProductName()
			)
		;

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @param Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product1
	 * @param Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product2
	 * @return void
	 */
	public function compareBySku (
		Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product1
		,
		Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product2
	) {

		/** @var int $result  */
		$result =
			strcmp (
				$product1->getProductSku()
				,
				$product2->getProductSku()
			)
		;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @param Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product1
	 * @param Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product2
	 * @return void
	 */
	public function compareByQty (
		Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product1
		,
		Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product2
	) {

		/** @var int $result  */
		$result =
				$product1->getProductQty()
			-
				$product2->getProductQty()
		;

		df_result_integer ($result);

		return $result;

	}



	/**
	 * @param Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product1
	 * @param Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product2
	 * @return void
	 */
	public function compareByRowTotal (
		Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product1
		,
		Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product $product2
	) {

		/** @var int $result  */
		$result =
				$product1->getRowTotal()
			-
				$product2->getRowTotal()
		;

		df_result_integer ($result);

		return $result;

	}



	/**
	 * @override
	 * @return string
	 */
	protected function getItemClass () {

		/** @var string $result */
		$result = Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product::getClass();

		return $result;
	}



	const ORDER_BY__NAME = 'name';
	const ORDER_BY__SKU = 'sku';
	const ORDER_BY__QTY = 'qty';
	const ORDER_BY__ROW_TOTAL = 'row_total';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection';
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


