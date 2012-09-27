<?php

class Df_Dataflow_Model_Registry_Collection_Attributes extends Df_Dataflow_Model_Registry_Collection {


	/**
	 * @override
	 * @param Varien_Object $entity
	 * @return Df_Dataflow_Model_Registry_Collection
	 */
	public function addEntity (Varien_Object $entity) {

		parent::addEntity ($entity);
		
		df_assert ($entity instanceof Mage_Catalog_Model_Resource_Eav_Attribute);
		/** @var Mage_Catalog_Model_Resource_Eav_Attribute $entity */

		$this->addEntityToCodeMap ($entity);

		return $this;

	}
	
	
	
	
	/**
	 * @override
	 * @param string $code
	 * @return Mage_Catalog_Model_Resource_Eav_Attribute|null
	 */
	public function findByCode ($code) {

		df_param_string ($code, 0);

		/** @var Mage_Catalog_Model_Resource_Eav_Attribute|null $result  */
		$result = df_a ($this->getMapFromCodeToEntity(), $code);

		if (!is_null ($result)) {
			df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);
		}

		return $result;
	}





	/**
	 * @override
	 * @param string $code
	 * @param array $attributeData
	 * @return Mage_Catalog_Model_Resource_Eav_Attribute
	 */
	public function findByCodeOrCreate ($code, array $attributeData) {

		df_param_string ($code, 0);

		/** @var Mage_Catalog_Model_Resource_Eav_Attribute $result  */
		$result = $this->findByCode ($code);

		if (!is_null ($result)) {
			$attributeData =
				array_merge (
					$result->getData()
					,
					$attributeData
				)
			;
		}

		$result =
			Df_Catalog_Model_Resource_Setup_AddAttribute::singleton()
				->addAttributeRm (
					$code
					,
					$attributeData
				)
		;

		df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);

		$this->addEntity ($result);

		return $result;
	}





	/**
	 * @override
	 * @param string $label
	 * @return Mage_Catalog_Model_Resource_Eav_Attribute|null
	 */
	public function findByLabel ($label) {

		df_param_string ($label, 0);

		/** @var Mage_Catalog_Model_Resource_Eav_Attribute|null $result  */
		$result = parent::findByLabel ($label);

		if (!is_null ($result)) {
			df_assert ($result instanceof Mage_Catalog_Model_Resource_Eav_Attribute);
		}

		return $result;
	}




	/**
	 * @override
	 * @return Varien_Data_Collection
	 */
	protected function createCollection () {

		/** @var Mage_Catalog_Model_Resource_Product_Attribute_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Collection $result  */
		$result =
			Mage::getResourceModel (
				'catalog/product_attribute_collection'
			)
		;

		df_helper()->catalog()->assert()->productAttributeCollection ($result);

		/**
		 * addFieldToSelect (Df_1C_Const::ENTITY_1C_ID)
		 * нам не нужно (ибо это поле — не из основной таблицы, а из дополнительной)
		 * и даже приводит к сбою (по той же причине)
		 */

		/**
		 * Пока используем это вместо $result->addHasOptionsFilter(),
		 * потому что addHasOptionsFilter отбраковывает пустые справочники
		 */
		//$result->setFrontendInputTypeFilter ('select');

		return $result;

	}





	/**
	 * @override
	 * @param Varien_Object $entity
	 * @return string|null
	 */
	protected function getEntityLabel (Varien_Object $entity) {

		df_assert ($entity instanceof Mage_Catalog_Model_Resource_Eav_Attribute);
		/** @var Mage_Catalog_Model_Resource_Eav_Attribute $entity */

		/** @var string|null $result  */
		$result = $entity->getFrontendLabel();

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;
	}
	
	
	
	
	/**
	 * @param Mage_Catalog_Model_Resource_Eav_Attribute $entity
	 * @return Df_Dataflow_Model_Registry_Collection
	 */
	private function addEntityToCodeMap (Mage_Catalog_Model_Resource_Eav_Attribute $entity) {

		$this->getMapFromCodeToEntity();

		/** @var string $code  */
		$code = $entity->getAttributeCode();

		df_assert_string ($code);

		$this->_mapFromCodeToEntity [$code] = $entity;

		return $this;
	}	
	

	


	/**
	 * @return Varien_Object[]
	 */
	private function getMapFromCodeToEntity () {

		if (!isset ($this->_mapFromCodeToEntity)) {

			/** @var Varien_Object[] $result  */
			$result = array ();

			foreach ($this->getCollectionRm() as $entity) {

				/** @var Mage_Catalog_Model_Resource_Eav_Attribute $entity */
				df_assert ($entity instanceof Mage_Catalog_Model_Resource_Eav_Attribute);

				/** @var string|null $code */
				$code = $entity->getAttributeCode();

				if (!df_empty ($code)) {
					df_assert_string ($code);
					$result [$code] = $entity;
				}
			}

			df_assert_array ($result);

			$this->_mapFromCodeToEntity = $result;
		}

		df_result_array ($this->_mapFromCodeToEntity);

		return $this->_mapFromCodeToEntity;
	}


	/**
	* @var Varien_Object[]
	*/
	private $_mapFromCodeToEntity;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Registry_Collection_Attributes';
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

