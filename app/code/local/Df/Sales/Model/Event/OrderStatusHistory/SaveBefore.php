<?php



/**
 * Cообщение:		«sales_order_status_history_save_before»
 * Источник:		Mage_Core_Model_Abstract::_beforeSave()
 * [code]
        Mage::dispatchEvent('model_save_before', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_save_before', $this->_getEventData());
 * [/code]
 */
class Df_Sales_Model_Event_OrderStatusHistory_SaveBefore extends Df_Core_Model_Event {




	/**
	 * @return Mage_Sales_Model_Order
	 */
	public function getOrder () {

		/** @var Mage_Sales_Model_Order $result  */
		$result = $this->getOrderStatusHistory()->getOrder();

		df_assert ($result instanceof Mage_Sales_Model_Order);

		return $result;

	}




	/**
	 * @return Mage_Sales_Model_Order_Status_History
	 */
	public function getOrderStatusHistory () {

		/** @var Mage_Sales_Model_Order_Status_History $result  */
		$result = $this->getEventParam (self::EVENT_PARAM__DATA_OBJECT);

		df_assert ($result instanceof Mage_Sales_Model_Order_Status_History);

		return $result;

	}






	/**
	 * @return string
	 */
	protected function getExpectedEventSuffix () {
		return self::EXPECTED_EVENT_SUFFIX;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Event_OrderStatusHistory_SaveBefore';
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



	const EXPECTED_EVENT_SUFFIX = '_save_before';

	const EVENT_PARAM__DATA_OBJECT = 'data_object';

}


