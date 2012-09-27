<?php

class Df_Dataflow_Model_Registry_Collection_Categories extends Df_Dataflow_Model_Registry_Collection {



	/**
	 * @override
	 * @return Varien_Data_Collection
	 */
	protected function createCollection () {

		/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection $result  */
		$result =
			Mage::getResourceModel (
				Df_Catalog_Const::CATEGORY_COLLECTION_CLASS_MF
			)
		;

		df_helper()->catalog()->assert()->categoryCollection ($result);


		$result->setStore(df_helper()->_1c()->cml2()->getStoreProcessed());

		$result->addAttributeToSelect (Df_1C_Const::ENTITY_1C_ID);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Registry_Collection_Categories';
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

