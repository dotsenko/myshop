<?php

class Df_Sales_Helper_Settings_OrderGrid_ProductColumn extends Df_Core_Helper_Settings {




	/**
	 * @return boolean
	 */
	public function getEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_grid__product_column/enabled'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return int
	 */
	public function getOrdering () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_sales/order_grid__product_column/ordering'
				)
			)
		;

		df_result_integer ($result);

		return $result;
	}





	/**
	 * @return int
	 */
	public function getNameWidth () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_sales/order_grid__product_column/name_width'
				)
			)
		;

		df_result_integer ($result);
		df_result_between ($result, 0, 100);

		return $result;
	}





	/**
	 * @return int
	 */
	public function getSkuWidth () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_sales/order_grid__product_column/sku_width'
				)
			)
		;

		df_result_integer ($result);
		df_result_between ($result, 0, 100);

		return $result;
	}






	/**
	 * @return int
	 */
	public function getQtyWidth () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_sales/order_grid__product_column/qty_width'
				)
			)
		;

		df_result_integer ($result);
		df_result_between ($result, 0, 100);

		return $result;
	}







	/**
	 * @return boolean
	 */
	public function showAllProducts () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_grid__product_column/show_all_products'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return int
	 */
	public function getMaxProductsToShow () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_sales/order_grid__product_column/max_products_to_show'
				)
			)
		;

		df_result_integer ($result);

		return $result;
	}





	/**
	 * @return boolean
	 */
	public function needShowName () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_grid__product_column/show_name'
			)
		;

		df_result_boolean ($result);

		return $result;
	}






	/**
	 * @return boolean
	 */
	public function needChopName () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_grid__product_column/chop_name'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return int
	 */
	public function getProductNameMaxLength () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_sales/order_grid__product_column/product_name_max_length'
				)
			)
		;

		df_result_integer ($result);

		return $result;
	}





	/**
	 * @return boolean
	 */
	public function needShowSku () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_grid__product_column/show_sku'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return boolean
	 */
	public function needShowQty () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_sales/order_grid__product_column/show_qty'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return string
	 */
	public function getOrderBy () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_sales/order_grid__product_column/order_by'
			)
		;

		if (is_null ($result)) {
			$result =
				Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection::ORDER_BY__NAME
			;
		}

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return string
	 */
	public function getOrderDirection () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_sales/order_grid__product_column/order_direction'
			)
		;

		if (is_null ($result)) {
			$result =
				Df_Admin_Model_Config_Source_OrderingDirection::VALUE__ASC
			;
		}

		df_result_string ($result);

		return $result;
	}







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Helper_Settings_OrderGrid_ProductColumn';
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

