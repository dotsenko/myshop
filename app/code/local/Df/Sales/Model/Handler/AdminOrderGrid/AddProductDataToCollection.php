<?php


class Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		if (
				df_cfg()->sales()->orderGrid()->productColumn()->getEnabled()
			&&
				df_enabled (Df_Core_Feature::SALES)
		) {

			try {
				$this->getEvent()->getCollection()
					->getSelect()->getAdapter()->query (
						'SET SESSION group_concat_max_len = 20000;'
					)
				;
			}
			catch (Exception $e) {
				df_handle_entry_point_exception ($e, false);
			}



			$this->getEvent()->getCollection()
				->join(
					'sales/order_item'
					,
					'`sales/order_item`.order_id=`main_table`.entity_id'
					,
					array (
						self::COLLECTION_ITEM_PARAM__DF_SKUS =>
							$this->createConcatExpression ('sku')
						,
						self::COLLECTION_ITEM_PARAM__DF_NAMES =>
							$this->createConcatExpression ('name')
						,
						self::COLLECTION_ITEM_PARAM__DF_QTYS =>
							$this->createConcatExpression ('qty_ordered')
						,
						self::COLLECTION_ITEM_PARAM__DF_PRODUCT_IDS =>
							$this->createConcatExpression ('product_id')
						,
						self::COLLECTION_ITEM_PARAM__DF_ORDER_ITEM_IDS =>
							$this->createConcatExpression ('item_id')

						,
						self::COLLECTION_ITEM_PARAM__DF_TOTALS =>
							$this->createConcatExpression ('row_total')

						,
						self::COLLECTION_ITEM_PARAM__DF_PARENTS =>
							new Zend_Db_Expr(
								sprintf (
									'group_concat(
										IFNULL(`sales/order_item`.%s, 0) SEPARATOR "%s"
									)'
									,
									'parent_item_id'
									,
									Df_Core_Const::T_UNIQUE_SEPARATOR
								)
							)
					)
				)
			;

			$this->getEvent()->getCollection()->getSelect()
				->group ('entity_id')
			;

		}

	}



	/**
	 * @param string $fieldName
	 * @return Zend_Db_Expr
	 */
	private function createConcatExpression ($fieldName) {

		df_param_string ($fieldName, 0);

		/** @var Zend_Db_Expr $result  */
		$result =
			new Zend_Db_Expr(
				sprintf (
					'group_concat(`sales/order_item`.%s SEPARATOR "%s")'
					,
					$fieldName
					,
					Df_Core_Const::T_UNIQUE_SEPARATOR
				)
			)
		;

		df_assert ($result instanceof Zend_Db_Expr);

		return $result;

	}





	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareCollection
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareCollection $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareCollection);

		return $result;
	}






	/**
	 * Класс события (для валидации события)
	 *
	 * @override
	 * @return string
	 */
	protected function getEventClass () {

		/** @var string $result  */
		$result = Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareCollection::getClass();

		df_result_string ($result);

		return $result;

	}





	const COLLECTION_ITEM_PARAM__DF_ORDER_ITEM_IDS = 'df_order_item_ids';
	const COLLECTION_ITEM_PARAM__DF_PRODUCT_IDS = 'df_product_ids';
	const COLLECTION_ITEM_PARAM__DF_SKUS = 'df_skus';
	const COLLECTION_ITEM_PARAM__DF_NAMES = 'df_names';
	const COLLECTION_ITEM_PARAM__DF_QTYS = 'df_qtys';
	const COLLECTION_ITEM_PARAM__DF_PARENTS = 'df_parents';
	const COLLECTION_ITEM_PARAM__DF_TOTALS = 'df_totals';








	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Handler_AdminOrderGrid_AddProductDataToCollection';
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


