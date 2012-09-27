<?php

abstract class Df_Dataflow_Model_Registry_Collection extends Df_Core_Model_Abstract {


	
	/**
	 * @abstract
	 * @return Varien_Data_Collection
	 */
	abstract protected function createCollection ();




	/**
	 * @param Varien_Object $entity
	 * @return Df_Dataflow_Model_Registry_Collection
	 */
	public function addEntity (Varien_Object $entity) {

		$this
			->addEntityToExternalIdMap ($entity)
			->addEntityToLabelMap ($entity)
		;

		return $this;

	}





	/**
	 * @param string $externalId
	 * @return Varien_Object|null
	 */
	public function findByExternalId ($externalId) {

		df_param_string ($externalId, 0);

		/** @var Varien_Object|null $result  */
		$result = df_a ($this->getMapFromExternalIdToEntity(), $externalId);

		if (!is_null ($result)) {
			df_assert ($result instanceof Varien_Object);
		}

		return $result;
	}





	/**
	 * @param string $label
	 * @return Varien_Object|null
	 */
	public function findByLabel ($label) {

		df_param_string ($label, 0);

		/** @var Varien_Object|null $result  */
		$result = df_a ($this->getMapFromLabelToEntity(), $label);

		if (!is_null ($result)) {
			df_assert ($result instanceof Varien_Object);
		}

		return $result;

	}





	/**
	 * @param Varien_Object $entity
	 * @return string|null
	 */
	protected function getEntityExternalId (Varien_Object $entity) {

		/** @var string|null $result  */
		$result = $entity->getData (Df_1C_Const::ENTITY_1C_ID);

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;
	}





	/**
	 * @param Varien_Object $entity
	 * @return string|null
	 */
	protected function getEntityLabel (Varien_Object $entity) {
		return null;
	}




	
	/**
	 * @return Varien_Data_Collection
	 */
	protected function getCollectionRm () {
	
		if (!isset ($this->_collectionRm)) {
	
			/** @var Varien_Data_Collection $result  */
			$result = $this->createCollection ();
	
			df_assert ($result instanceof Varien_Data_Collection);
	
			$this->_collectionRm = $result;
	
		}
	
	
		df_assert ($this->_collectionRm instanceof Varien_Data_Collection);
	
		return $this->_collectionRm;
	
	}
	
	
	/**
	* @var Varien_Data_Collection
	*/
	private $_collectionRm;





	/**
	 * @param Varien_Object $entity
	 * @return Df_Dataflow_Model_Registry_Collection
	 */
	private function addEntityToExternalIdMap (Varien_Object $entity) {

		$this->getMapFromExternalIdToEntity ();

		/** @var string|null $externalId  */
		$externalId = $this->getEntityExternalId ($entity);

		if (!df_empty ($externalId)) {

			df_assert_string ($externalId);

			$this->_mapFromExternalIdToEntity [$externalId] = $entity;
		}

		return $this;
	}




	/**
	 * @param Varien_Object $entity
	 * @return Df_Dataflow_Model_Registry_Collection
	 */
	private function addEntityToLabelMap (Varien_Object $entity) {

		$this->getMapFromLabelToEntity();

		/** @var string|null $label  */
		$label = $this->getEntityLabel ($entity);

		if (!df_empty ($label)) {

			df_assert_string ($label);

			$this->_mapFromLabelToEntity [$label] = $entity;
		}

		return $this;
	}





	/**
	 * @return Varien_Object[]
	 */
	private function getMapFromExternalIdToEntity () {

		if (!isset ($this->_mapFromExternalIdToEntity)) {

			/** @var Varien_Object[] $result  */
			$result = array ();

			foreach ($this->getCollectionRm() as $entity) {

				/** @var Varien_Object $entity */
				df_assert ($entity instanceof Varien_Object);

				/** @var string|null $externalId  */
				$externalId = $this->getEntityExternalId ($entity);

				if (!is_null ($externalId)) {
					df_assert_string ($externalId);
					$result [$externalId] = $entity;
				}
			}

			df_assert_array ($result);

			$this->_mapFromExternalIdToEntity = $result;
		}


		df_result_array ($this->_mapFromExternalIdToEntity);

		return $this->_mapFromExternalIdToEntity;
	}


	/**
	* @var Varien_Object[]
	*/
	private $_mapFromExternalIdToEntity;





	/**
	 * @return Varien_Object[]
	 */
	private function getMapFromLabelToEntity () {

		if (!isset ($this->_mapFromLabelToEntity)) {

			/** @var Varien_Object[] $result  */
			$result = array ();


			foreach ($this->getCollectionRm() as $entity) {

				/** @var Varien_Object $entity */
				df_assert ($entity instanceof Varien_Object);


				/** @var string|null $label */
				$label = $this->getEntityLabel ($entity);


				if (!df_empty ($label)) {
					df_assert_string ($label);
					$result [$label] = $entity;
				}

			}


			df_assert_array ($result);

			$this->_mapFromLabelToEntity = $result;

		}


		df_result_array ($this->_mapFromLabelToEntity);

		return $this->_mapFromLabelToEntity;

	}


	/**
	* @var Varien_Object[]
	*/
	private $_mapFromLabelToEntity;






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Registry_Collection';
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

