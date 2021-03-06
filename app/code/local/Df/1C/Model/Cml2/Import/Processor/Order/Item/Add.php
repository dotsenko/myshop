<?php

/**
 * Добавляет к заказу (PARAM__ENTITY_ORDER) новую строку (PARAM__ENTITY)
 * на основании пришедших из 1С:Управление торговлей данных
 */
class Df_1C_Model_Cml2_Import_Processor_Order_Item_Add
	extends Df_1C_Model_Cml2_Import_Processor_Order_Item {


	/**
	 * @return Df_1C_Model_Cml2_Import_Processor_Order_Item_Add
	 */
	public function process () {

		return $this;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Order_Item_Add';
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
