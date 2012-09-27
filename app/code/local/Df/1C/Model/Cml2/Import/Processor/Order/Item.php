<?php

abstract class Df_1C_Model_Cml2_Import_Processor_Order_Item extends Df_1C_Model_Cml2_Import_Processor {


	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Order_Item
	 */
	protected function getEntity () {
		return parent::getEntity();
	}


	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Order
	 */
	protected function getEntityOrder () {
		return $this->cfg (self::PARAM__ENTITY_ORDER);
	}



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__ENTITY, Df_1C_Model_Cml2_Import_Data_Entity_Order_Item::getClass()
			)
			->validateClass (
				self::PARAM__ENTITY_ORDER, Df_1C_Model_Cml2_Import_Data_Entity_Order::getClass()
			)
		;
	}



	const PARAM__ENTITY_ORDER = 'entity_order';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Order_Item';
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


