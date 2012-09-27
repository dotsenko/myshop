<?php


class Df_Pd4_Block_LinkToDocument_ForAnyOrder extends Df_Pd4_Block_LinkToDocument {


	
	/**
	 * @return Mage_Sales_Model_Order
	 */
	public function getOrder () {
	
		/** @var Mage_Sales_Model_Order $result  */
		$result = $this->_order;
	
		df_assert ($result instanceof Mage_Sales_Model_Order);
	
		return $this->_order;
	
	}




	/**
	 * @param Mage_Sales_Model_Order $order
	 * @return Df_Pd4_Block_LinkToDocument_ForAnyOrder
	 */
	public function setOrder (Mage_Sales_Model_Order $order) {

		df_assert ($order instanceof Mage_Sales_Model_Order);

		$this->_order = $order;


		return $this;

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
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pd4_Block_LinkToDocument_ForAnyOrder';
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





	const DEFAULT_TEMPLATE = 'df/pd4/link_to_document/for_any_order.phtml';


}


