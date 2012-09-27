<?php

class Df_1C_Model_Cml2_Import_Data_Entity_Product extends Df_1C_Model_Cml2_Import_Data_Entity {



	/**
	 * @return Mage_Eav_Model_Entity_Attribute_Set
	 */
	public function getAttributeSet () {

		if (!isset ($this->_attributeSet)) {

			/** @var Mage_Eav_Model_Entity_Attribute_Set $result  */
			$result =
				df_helper()->dataflow()->registry()->attributeSets()->findByLabel (
					$this->getAppliedTypeName ()
				)
			;

			if (is_null ($result)) {
				/**
				 * Добавляем в систему новый прикладной тип товара
				 */
				$result =
					Df_Catalog_Model_Installer_AttributeSet::create (
						$this->getAppliedTypeName()
					)
				;

				df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute_Set);


				/**
				 * Добавляем к прикладному типу товаров
				 * свойство для учёта внешнего идентификатора товара в 1С:Управление торговлей
				 */

				df_helper()->_1c()->cml2()->attributeSet()
					->addExternalIdToAttributeSet (
						$result->getId()
					)
				;

				$this->getAttributeSets()->addEntity ($result);

				df_assert (
					!is_null (
						$this->getAttributeSets()->findByLabel (
							$this->getAppliedTypeName ()
						)
					)
				);
			}


			df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute_Set);

			$this->_attributeSet = $result;

		}


		df_assert ($this->_attributeSet instanceof Mage_Eav_Model_Entity_Attribute_Set);

		return $this->_attributeSet;

	}


	/**
	* @var Mage_Eav_Model_Entity_Attribute_Set
	*/
	private $_attributeSet;





	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom
	 */
	public function getAttributeValuesCustom () {

		if (!isset ($this->_attributeValuesCustom)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom
							::PARAM__SIMPLE_XML => $this->getSimpleXmlElement()
						,
						Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom
							::PARAM__PRODUCT => $this
					)
				)
			;


			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom);

			$this->_attributeValuesCustom = $result;

		}


		df_assert ($this->_attributeValuesCustom instanceof Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom);

		return $this->_attributeValuesCustom;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom
	*/
	private $_attributeValuesCustom;





	/**
	 * @return Mage_Catalog_Model_Category
	 */
	public function getCategory () {

		/** @var Mage_Catalog_Model_Category $result  */
		$result =
			df_helper()->dataflow()->registry()->categories()->findByExternalId (
				$this->getCategoryExternalId()
			)
		;

		if (!($result instanceof Mage_Catalog_Model_Category)) {
			df_error (
				sprintf (
					'Не могу найти в системе товарный раздел с внешним идентификатором «%s»'
					,
					$this->getCategoryExternalId()
				)
			);
		}

		return $result;

	}





	/**
	 * @return string
	 */
	public function getDescription () {

		/** @var string $result  */
		$result = $this->getEntityParam ('Описание', $this->getNameFull());

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_Images
	 */
	public function getImages () {

		if (!isset ($this->_images)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_Images $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_Images::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_Images
							::PARAM__SIMPLE_XML => $this->getSimpleXmlElement()
					)
				)
			;


			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_Images);

			$this->_images = $result;

		}


		df_assert ($this->_images instanceof Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_Images);

		return $this->_images;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_Images
	*/
	private $_images;





	/**
	 * @return string
	 */
	public function getNameFull () {

		/** @var string $result  */
		$result = $this->getEntityParam ('ПолноеНаименование');

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string|null
	 */
	public function getSku () {

		/** @var string|null $result  */
		$result = $this->getEntityParam ('Артикул');

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}





	/**
	 * @return float
	 */
	public function getWeight () {

		/** @var float $result  */
		$result = 0.0;

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_System $propertyWeight */
		$propertyWeight =
			$this->getAttributeValuesSystem()->findByName ('Вес')
		;

		if (!is_null ($propertyWeight)) {

			df_assert ($propertyWeight instanceof Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_System);

			$result =  floatval ($propertyWeight->getValue());

		}



		df_result_float ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getAppliedTypeName () {

		/** @var string $result  */
		$result = null;

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_System $propertyTypeApplied|null */
		$propertyTypeApplied =
			$this->getAttributeValuesSystem()->findByName ('ВидНоменклатуры')
		;

		if (!is_null ($propertyTypeApplied)) {

			df_assert ($propertyTypeApplied instanceof Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_System);

			$result = $propertyTypeApplied->getValue();
		}


		if (df_empty ($result)) {
			$result = df_helper()->catalog()->product()->getDefaultAttributeSet()->getAttributeSetName();
		}


		df_result_string ($result);

		return $result;
	}





	/**
	 * @return Df_Dataflow_Model_Registry_Collection_AttributeSets
	 */
	private function getAttributeSets () {
		return df_helper()->dataflow()->registry()->attributeSets();
	}





	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_System
	 */
	private function getAttributeValuesSystem () {

		if (!isset ($this->_attributeValuesSystem)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_System $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_System::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_System
							::PARAM__SIMPLE_XML => $this->getSimpleXmlElement()
					)
				)
			;


			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_System);

			$this->_attributeValuesSystem = $result;

		}


		df_assert ($this->_attributeValuesSystem instanceof Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_System);

		return $this->_attributeValuesSystem;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_System
	*/
	private $_attributeValuesSystem;


	



	/**
	 * @return string
	 */
	private function getCategoryExternalId () {

		/** @var string $result  */
		$result =
			df_a (
				$this->getEntityParam ('Группы')
				,
				'Ид'
			)
		;

		df_result_string ($result);

		return $result;

	}

	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Product';
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

