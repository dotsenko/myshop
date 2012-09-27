<?php

class Df_Banner_Model_Resource_Banneritem_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this->_init('df_banner/banneritem');
	}


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Banner_Model_Resource_Banneritem_Collection';
	}



	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return 'df_banner/banneritem_collection';
	}


}