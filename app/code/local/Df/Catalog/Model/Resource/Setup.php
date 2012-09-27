<?php

class Df_Catalog_Model_Resource_Setup extends Mage_Catalog_Model_Resource_Eav_Mysql4_Setup {



	/**
	 * @return Df_Catalog_Model_Resource_Setup
	 */
	public function cleanQueryCache () {
		$this->_setupCache = array ();
		return $this;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Resource_Setup';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return 'df_catalog/setup';
	}


}

