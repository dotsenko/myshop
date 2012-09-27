<?php

class Df_Dataflow_Model_Registry_Collection_AttributeSets extends Df_Dataflow_Model_Registry_Collection {


	/**
	 * @override
	 * @param string $label
	 * @return Mage_Eav_Model_Entity_Attribute_Set|null
	 */
	public function findByLabel ($label) {

		df_param_string ($label, 0);

		/** @var Mage_Eav_Model_Entity_Attribute_Set|null $result  */
		$result = parent::findByLabel ($label);

		if (!is_null ($result)) {
			df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute_Set);
		}

		return $result;
	}






	/**
	 * @override
	 * @return Varien_Data_Collection
	 */
	protected function createCollection () {

		/** @var Mage_Eav_Model_Resource_Entity_Attribute_Set_Collection|Mage_Eav_Model_Mysql4_Entity_Attribute_Set_Collection $result  */
		$result =
			Mage::getResourceModel (
				Df_Eav_Const::CLASS_MF__ENTITY_ATTRIBUTE_SET_COLLECTION
			)
		;

		df_helper()->eav()->assert()->entityAttributeSetCollection ($result);


		$result
			->setEntityTypeFilter (
				df_helper()->catalog()->eav()->getProductEntity()->getTypeId()
			)
		;

		return $result;

	}





	/**
	 * @override
	 * @param Varien_Object $entity
	 * @return string|null
	 */
	protected function getEntityLabel (Varien_Object $entity) {

		df_assert ($entity instanceof Mage_Eav_Model_Entity_Attribute_Set);
		/** @var Mage_Eav_Model_Entity_Attribute_Set $entity */

		/** @var string|null $result  */
		$result = $entity->getAttributeSetName();

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Registry_Collection_AttributeSets';
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

