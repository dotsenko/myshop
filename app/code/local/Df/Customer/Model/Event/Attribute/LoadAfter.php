<?php



/**
 * Cообщение:		«eav_entity_attribute_load_after»
 * Источник:		Mage_Core_Model_Abstract::_afterLoad()
 * [code]
		Mage::dispatchEvent($this->_eventPrefix.'_load_after', $this->_getEventData());
 * [/code]
 */
class Df_Customer_Model_Event_Attribute_LoadAfter
	extends Df_Core_Model_Event_Eav_Entity_Attribute_LoadAfter {



	/**
	 * @return Mage_Customer_Model_Attribute
	 */
	public function getAttribute () {

		/** @var Mage_Customer_Model_Attribute $result  */
		$result = parent::getAttribute();

		df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute);

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
		return 'Df_Customer_Model_Event_Attribute_LoadAfter';
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



	const EXPECTED_EVENT_SUFFIX = 'customer_entity_attribute_load_after';


}


