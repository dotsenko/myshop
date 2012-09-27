<?php

class Df_Dataflow_Model_Registry_Collection_Products extends Df_Dataflow_Model_Registry_Collection {



	/**
	 * @override
	 * @param Varien_Object $entity
	 * @return Df_Dataflow_Model_Registry_Collection
	 */
	public function addEntity (Varien_Object $entity) {

		if (df_empty ($this->getEntityExternalId ($entity))) {
			df_error (
				'Добавляемому в реестр товару должен быть присвоен внешний идентификатор'
			);
		}

		parent::addEntity ($entity);

		return $this;

	}




	/**
	 * @override
	 * @return Varien_Data_Collection
	 */
	protected function createCollection () {

		/** @var Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $result */
		$result =
			Mage::getResourceModel (
				Df_Catalog_Const::PRODUCT_COLLECTION_CLASS_MF
			)
		;

		df_helper()->catalog()->assert()->productCollection ($result);


		$result->setStore(df_helper()->_1c()->cml2()->getStoreProcessed());

		$result
			->addAttributeToSelect (
				array (
					Df_1C_Const::ENTITY_1C_ID
					,
					'name'
					,
					'price'
				)
			)
		;

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Registry_Collection_Products';
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

