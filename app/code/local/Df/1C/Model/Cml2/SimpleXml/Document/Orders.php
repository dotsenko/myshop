<?php

class Df_1C_Model_Cml2_SimpleXml_Document_Orders extends Df_1C_Model_Cml2_SimpleXml_Document {


	/**
	 * @override
	 * @return Df_Varien_Simplexml_Element
	 */
	protected function createElement () {

		/** @var Df_Varien_Simplexml_Element $result  */
		$result = parent::createElement();

		foreach ($this->getOrders() as $order) {
			/** @var Mage_Sales_Model_Order $order */
			df_assert ($order instanceof Mage_Sales_Model_Order);

			/** @var Df_1C_Model_Cml2_Export_Processor_Order $processor */
			$processor =
				df_model (
					Df_1C_Model_Cml2_Export_Processor_Order::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Export_Processor_Order::PARAM__ORDER => $order
						,
						Df_1C_Model_Cml2_Export_Processor_Order::PARAM__SIMPLE_XML_ELEMENT => $result
					)
				)
			;

			df_assert ($processor instanceof Df_1C_Model_Cml2_Export_Processor_Order);

			$processor->process();
		}

		df_assert ($result instanceof Df_Varien_Simplexml_Element);

		return $result;
	}




	/**
	 * @return Mage_Sales_Model_Resource_Order_Collection|Mage_Sales_Model_Mysql4_Order_Collection
	 */
	private function getOrders () {

		/** @var Mage_Sales_Model_Resource_Order_Collection|Mage_Sales_Model_Mysql4_Order_Collection $result  */
		$result = $this->cfg (self::PARAM__ORDERS);

		df_helper()->sales()->assert()->orderCollection ($result);

		return $result;

	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__ORDERS, 'Varien_Data_Collection_Db'
			)
		;
	}



	const PARAM__ORDERS = 'orders';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_SimpleXml_Document_Orders';
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


