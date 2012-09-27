<?php

abstract class Df_1C_Model_Cml2_Import_Data_Collection
	extends Df_1C_Model_Cml2_Import_Data
	implements IteratorAggregate {



	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getItemClassMf ();



	/**
	 * @abstract
	 * @return array
	 */
	abstract protected function getXmlPathAsArray ();





	/**
	 * @param string $externalId
	 * @return Df_1C_Model_Cml2_Import_Data_Entity|null
	 */
	public function findByExternalId ($externalId) {

		df_param_string ($externalId, 0);

		/** @var Df_1C_Model_Cml2_Import_Data_Entity|null $result  */
		$result =
			df_a (
				$this->getMapFromExternalIdToEntity()
				,
				$externalId
			)
		;

		if (!is_null($result)) {
			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity);
		}

		return $result;

	}




	/**
	 * @param string $name
	 * @return Df_1C_Model_Cml2_Import_Data_Entity|null
	 */
	public function findByName ($name) {

		df_param_string ($name, 0);

		/** @var Df_1C_Model_Cml2_Import_Data_Entity|null $result  */
		$result =
			df_a (
				$this->getMapFromNameToEntity()
				,
				$name
			)
		;

		if (!is_null($result)) {
			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity);
		}

		return $result;

	}




	
	
	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity[]
	 */
	public function getItems () {
	
		if (!isset ($this->_items)) {
	
			/** @var Df_1C_Model_Cml2_Import_Data_Entity[] $result  */
			$result = new ArrayObject (array ());


			foreach ($this->getImportEntitiesAsSimpleXMLElementArray () as $entityAsSimpleXMLElement) {

				/** @var Varien_Simplexml_Element $entityAsSimpleXMLElement */
				df_assert ($entityAsSimpleXMLElement instanceof Varien_Simplexml_Element);

				$result[]=	$this->createItemFromSimpleXmlElement ($entityAsSimpleXMLElement);

			}
	
	
			df_assert ($result instanceof ArrayObject);
	
			$this->_items = $result;
	
		}
	
	
		df_assert ($this->_items instanceof ArrayObject);
	
		return $this->_items;
	
	}
	
	
	/**
	* @var Df_1C_Model_Cml2_Import_Data_Entity[]
	*/
	protected $_items;





	/**
	 * @return Traversable
	 */
	public function getIterator() {

		/** @var Traversable $result */
		$result = $this->getItems();

		df_assert ($result instanceof Traversable);

		return $result;
	}





	/**
	 * @param Varien_Simplexml_Element $entityAsSimpleXMLElement
	 * @return Df_1C_Model_Cml2_Import_Data_Entity
	 */
	protected function createItemFromSimpleXmlElement (Varien_Simplexml_Element $entityAsSimpleXMLElement) {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity $result  */
		$result =
			df_model (
				$this->getItemClassMf ()
				,
				array_merge (
					array (
						Df_1C_Model_Cml2_Import_Data_Entity::PARAM__SIMPLE_XML => $entityAsSimpleXMLElement
					)
					,
					$this->getItemParamsAdditional()
				)
			)
		;

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity);

		return $result;

	}




	/**
	 * Позволяет добавлять к создаваемым элементам
	 * дополнительные, единые для всех элементов, параметры
	 *
	 * @return array
	 */
	protected function getItemParamsAdditional () {
		return array ();
	}





	/**
	 * @return Varien_Simplexml_Element[]
	 */
	protected function getImportEntitiesAsSimpleXMLElementArray () {

		if (!isset ($this->_importEntitiesAsSimpleXMLElementArray)) {

			/** @var Varien_Simplexml_Element[] $result  */
			$result =
				$this->getSimpleXmlElement()->xpath (
					implode (
						'/'
						,
						$this->getXmlPathAsArray ()
					)
				)
			;


			df_assert_array ($result);

			$this->_importEntitiesAsSimpleXMLElementArray = $result;

		}


		df_result_array ($this->_importEntitiesAsSimpleXMLElementArray);

		return $this->_importEntitiesAsSimpleXMLElementArray;

	}


	/**
	* @var Varien_Simplexml_Element[]
	*/
	private $_importEntitiesAsSimpleXMLElementArray;




	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity[]
	 */
	private function getMapFromExternalIdToEntity () {

		if (!isset ($this->_mapFromExternalIdToEntity)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity[] $result  */
			$result = array ();

			foreach ($this->getItems() as $entity) {
				/** @var Df_1C_Model_Cml2_Import_Data_Entity $entity */
				df_assert ($entity instanceof Df_1C_Model_Cml2_Import_Data_Entity);

				$result [$entity->getExternalId()] = $entity;

			}

			df_assert_array ($result);

			$this->_mapFromExternalIdToEntity = $result;

		}


		df_result_array ($this->_mapFromExternalIdToEntity);

		return $this->_mapFromExternalIdToEntity;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Entity[]
	*/
	private $_mapFromExternalIdToEntity;





	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity[]
	 */
	private function getMapFromNameToEntity () {

		if (!isset ($this->_mapFromNameToEntity)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity[] $result  */
			$result = array ();

			foreach ($this->getItems() as $entity) {
				/** @var Df_1C_Model_Cml2_Import_Data_Entity $entity */
				df_assert ($entity instanceof Df_1C_Model_Cml2_Import_Data_Entity);

				$result [$entity->getName()] = $entity;

			}

			df_assert_array ($result);

			$this->_mapFromNameToEntity = $result;

		}


		df_result_array ($this->_mapFromNameToEntity);

		return $this->_mapFromNameToEntity;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Entity[]
	*/
	private $_mapFromNameToEntity;






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Collection';
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
