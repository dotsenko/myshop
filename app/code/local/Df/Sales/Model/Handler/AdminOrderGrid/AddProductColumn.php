<?php


class Df_Sales_Model_Handler_AdminOrderGrid_AddProductColumn extends Df_Core_Model_Handler {




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

			$this
				->registerProductColumnRenderer ()
				->addProductColumn ()
			;

		}

	}





	/**
	 * @return Df_Sales_Model_Handler_AdminOrderGrid_AddProductColumn
	 */
	private function registerProductColumnRenderer () {

		/** @var array|null $columnRenderers  */
		$columnRenderers = $this->getData (Df_Adminhtml_Block_Widget_Grid::PARAM__COLUMN_RENDERERS);

		if (is_null ($columnRenderers)) {
			$columnRenderers = array ();
		}

		df_assert_array ($columnRenderers);


		$this->getEvent()->getGrid()
			->setData (
				Df_Adminhtml_Block_Widget_Grid::PARAM__COLUMN_RENDERERS
				,
				array_merge (
					$columnRenderers
					,
					array (
						self::COLUMN_TYPE__DF_ORDER_GRID_PRODUCTS =>
							Df_Sales_Block_Admin_Widget_Grid_Column_Renderer_Products
								::getNameInMagentoFormat()
					)
				)
			)
		;

		return $this;

	}




	/**
	 * @return Df_Sales_Model_Handler_AdminOrderGrid_AddProductColumn
	 */
	private function addProductColumn () {

		$this->getEvent()->getGrid()
			->addColumnAfter (
				'df_products'
				,
				array (
					'header' => 'Товары'
					,
					'type'  => self::COLUMN_TYPE__DF_ORDER_GRID_PRODUCTS
				)
				,
				$this->getPreviousColumnId ()
			)
		;

		return $this;

	}






	/**
	 * @return string|null
	 */
	private function getPreviousColumnId () {
	
		if (!isset ($this->_previousColumnId)) {
	
			/** @var string|null $result  */
			$result = 
				df_a (
					array_keys (
						$this->getEvent()->getGrid()->getColumns()
					)
					,

					/**
					 * Минус 2, потому что:
					 * 		* самый левый столбец с флажками не учитывается
					 * 		* администратор ведёт отчёт с 1, а система — с 0.
					 */
					df_cfg()->sales()->orderGrid()->productColumn()->getOrdering() - 2
				)
			;

	
			if (!is_null ($result)) {
				df_assert_string ($result);				
			}
	
			$this->_previousColumnId = $result;
	
		}
	
		
		if (!is_null ($this->_previousColumnId)) {
			df_result_string ($this->_previousColumnId);			
		}	
		
	
		return $this->_previousColumnId;
	
	}
	
	
	/**
	* @var string|null
	*/
	private $_previousColumnId;
	






	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareColumnsAfter
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareColumnsAfter $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareColumnsAfter);

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
		$result = Df_Core_Model_Event_Adminhtml_Block_Sales_Order_Grid_PrepareColumnsAfter::getClass();

		df_result_string ($result);

		return $result;

	}




	const COLUMN_TYPE__DF_ORDER_GRID_PRODUCTS = 'df_order_grid_products';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Handler_AdminOrderGrid_AddProductColumn';
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


