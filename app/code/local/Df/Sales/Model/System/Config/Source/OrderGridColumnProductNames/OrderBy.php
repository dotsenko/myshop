<?php


/**
 * @singleton
 * Система создаёт объект-одиночку для потомков этого класса.
 * Не забывайте об этом при реализации кеширования результатов вычислений внутри этого класса!
 */
class Df_Sales_Model_System_Config_Source_OrderGridColumnProductNames_OrderBy
	extends Df_Admin_Model_Config_Source {


	/**
	 * @override
	 * @param bool $isMultiSelect
	 * @return array
	 */
	protected function toOptionArrayInternal ($isMultiSelect = false) {

		/** @var array $result  */
		$result = $this->getAsOptionArray();

		df_result_array ($result);

		return $result;

    }






	/**
	 * @return array
	 */
	private function getAsOptionArray () {

		if (!isset ($this->_asOptionArray)) {


			/** @var array $result  */
			$result =
				array (

					array (
						self::OPTION_KEY__LABEL => 'названиям'
						,
						self::OPTION_KEY__VALUE =>
							Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection
								::ORDER_BY__NAME
					)


					,
					array (
						self::OPTION_KEY__LABEL => 'артикулам'
						,
						self::OPTION_KEY__VALUE =>
							Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection
								::ORDER_BY__SKU
					)

					,
					array (
						self::OPTION_KEY__LABEL => 'заказанным количествам'
						,
						self::OPTION_KEY__VALUE =>
							Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection
								::ORDER_BY__QTY
					)


					,
					array (
						self::OPTION_KEY__LABEL => 'стоимостям строк заказа'
						,
						self::OPTION_KEY__VALUE =>
							Df_Sales_Model_Presentation_OrderGrid_CellData_Products_Collection
								::ORDER_BY__ROW_TOTAL
					)

				)
			;


			df_assert_array ($result);

			$this->_asOptionArray = $result;

		}


		df_result_array ($this->_asOptionArray);

		return $this->_asOptionArray;

	}


	/**
	* @var array
	*/
	private $_asOptionArray;



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_System_Config_Source_OrderGridColumnProductNames_OrderBy';
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


