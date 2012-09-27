<?php

class Df_1C_Model_Cml2_Import_Data_Entity_Order extends Df_1C_Model_Cml2_Import_Data_Entity {


	/**
	 * @return string
	 */
	public function getIncrementId () {
		/** @var string $result  */
		$result = $this->getEntityParam ('Номер');

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_Order_Items
	 */
	public function getItems () {

		if (!isset ($this->_items)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_Order_Items $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_Order_Items::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_Order_Items
							::PARAM__SIMPLE_XML => $this->getSimpleXmlElement()
						,
						Df_1C_Model_Cml2_Import_Data_Collection_Order_Items
							::PARAM__ENTITY_ORDER => $this
					)
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_Order_Items);

			$this->_items = $result;
		}

		df_assert ($this->_items instanceof Df_1C_Model_Cml2_Import_Data_Collection_Order_Items);

		return $this->_items;
	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_Order_Items
	*/
	private $_items;



	
	
	/**
	 * @return Df_Sales_Model_Order
	 */
	public function getOrder () {
	
		if (!isset ($this->_order)) {
	
			/** @var Df_Sales_Model_Order $result */
			$result = 	
				df_model (
					Df_Sales_Model_Order::getNameInMagentoFormat()
				)
			;
	
			df_assert ($result instanceof Df_Sales_Model_Order);


			$result->loadByIncrementId ($this->getIncrementId());

	
			$this->_order = $result;
		}
	
		df_assert ($this->_order instanceof Df_Sales_Model_Order);
	
		return $this->_order;
	}
	
	
	/**
	* @var Df_Sales_Model_Order
	*/
	private $_order;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Order';
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

