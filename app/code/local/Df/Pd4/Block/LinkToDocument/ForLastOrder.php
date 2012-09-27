<?php


class Df_Pd4_Block_LinkToDocument_ForLastOrder extends Df_Pd4_Block_LinkToDocument {



	/**
	 * @return Mage_Sales_Model_Order
	 */
	protected function getOrder () {
	
		if (!isset ($this->_order)) {
	
			/** @var Mage_Sales_Model_Order $result  */
			$result =
				df_model (Df_Sales_Const::ORDER_CLASS_MF)
			;
	
			df_assert ($result instanceof Mage_Sales_Model_Order);


			$result->load ($this->getLastOrderId());

			df_assert ($result->getId () == $this->getLastOrderId());



			df_assert ($result instanceof Mage_Sales_Model_Order);
	
			$this->_order = $result;
	
		}
	
	
		df_assert ($this->_order instanceof Mage_Sales_Model_Order);
	
		return $this->_order;
	
	}
	
	
	/**
	* @var Mage_Sales_Model_Order
	*/
	private $_order;
	






	/**
	 * @return string
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		df_result_string ($result);

		return $result;

	}




	
	
	/**
	 * @return int
	 */
	private function getLastOrderId () {
	
		/** @var int $result  */
		$result =
			df_helper()->checkout()->sessionSingleton()
				->getDataUsingMethod (
					Df_Checkout_Const::SESSION_PARAM__LAST_ORDER_ID
				)
		;

		df_result_integer ($result);

		df_result_between ($result, 1);
	
		return $result;
	
	}	
	
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pd4_Block_LinkToDocument_ForLastOrder';
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





	const DEFAULT_TEMPLATE = 'df/pd4/link_to_document/for_last_order.phtml';


}


