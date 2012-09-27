<?php



/**
 * Cообщение:		«catalog_category_collection_load_before»
 *
 * Источник:		Mage_Core_Model_Mysql4_Collection_Abstract::_beforeLoad()
 * 					Mage_Core_Model_Resource_Collection_Abstract::_beforeLoad()
 * [code]
		parent::_beforeLoad();
		Mage::dispatchEvent('core_collection_abstract_load_before', array('collection' => $this));
		if ($this->_eventPrefix && $this->_eventObject) {
			Mage::dispatchEvent($this->_eventPrefix.'_load_before', array(
				$this->_eventObject => $this
			));
		}
 * [/code]
 *
 * Назначение:		Позволяет выполнить дополнительную обработку коллекции товарных разделов
 * 					перед её загрузкой
 */
class Df_Catalog_Model_Event_Category_Collection_Load_Before extends Df_Core_Model_Event {



	/**
	 * @return Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	 */
	public function getCollection () {

		/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection $result  */
		$result =
			$this->getEventParam (self::EVENT_PARAM__COLLECTION)
		;

		df_helper()->catalog()->assert()->categoryCollection ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	protected function getExpectedEventPrefix () {
		return self::EXPECTED_EVENT_PREFIX;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Event_Category_Collection_Load_Before';
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



	const EXPECTED_EVENT_PREFIX = 'catalog_category_collection_load_before';
	const EVENT_PARAM__COLLECTION = 'category_collection';

}


