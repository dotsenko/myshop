<?php


class Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product
	extends Df_Core_Block_Template {



	/**
	 * @return string
	 */
	public function getProductNameToDisplay () {

		/** @var string $result  */
		$result =
				!df_cfg()->sales()->orderGrid()->productColumn()->needChopName()
			?
				$this->getProductName()
			:
				df_text()->chop (
					$this->getProductName()
					,
					df_cfg()->sales()->orderGrid()->productColumn()->getProductNameMaxLength()
				)
		;

		df_result_string ($result);

		return $result;

	}



	
	

	/**
	 * @return string
	 */
	public function getProductName () {

		/** @var string $result  */
		$result =
			$this->cfg (self::PARAM__PRODUCT_NAME)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getProductSku () {

		/** @var string $result  */
		$result =
			$this->cfg (self::PARAM__PRODUCT_SKU)
		;

		df_result_string ($result);

		return $result;

	}



	/**
	 * @return int
	 */
	public function getProductQty () {

		/** @var int $result  */
		$result =
			intval (
				$this->cfg (self::PARAM__PRODUCT_QTY)
			)
		;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getProductId () {

		/** @var int $result  */
		$result =
			intval (
				$this->cfg (self::PARAM__PRODUCT_ID)
			)
		;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getOrderItemId () {

		/** @var int $result  */
		$result =
			intval (
				$this->cfg (self::PARAM__ORDER_ITEM_ID)
			)
		;

		df_result_integer ($result);

		return $result;

	}



	/**
	 * @return float
	 */
	public function getRowTotal () {

		/** @var int $result  */
		$result =
			floatval (
				$this->cfg (self::PARAM__ROW_TOTAL)
			)
		;

		df_result_float ($result);

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



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__PRODUCT_NAME, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__PRODUCT_SKU, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__PRODUCT_QTY
				,
				/**
				 * string, а не int, потому что мы получаем данные из SQL
				 */
				new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__ORDER_ITEM_ID , new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__PRODUCT_ID , new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__ROW_TOTAL , new Df_Zf_Validate_String()
			)
		;
	}




	const DEFAULT_TEMPLATE = 'df/sales/widget/grid/column/renderer/products/product.phtml';

	const PARAM__PRODUCT_ID = 'product_id';
	const PARAM__ORDER_ITEM_ID = 'order_item_id';
	const PARAM__PRODUCT_NAME = 'product_name';
	const PARAM__PRODUCT_QTY = 'product_qty';
	const PARAM__PRODUCT_SKU = 'product_sku';
	const PARAM__ROW_TOTAL = 'row_total';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products_Product';
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


