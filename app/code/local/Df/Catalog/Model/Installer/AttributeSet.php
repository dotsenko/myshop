<?php

class Df_Catalog_Model_Installer_AttributeSet extends Df_Core_Model_Abstract {


	/**
	 * @return Mage_Eav_Model_Entity_Attribute_Set
	 */
	public function getResult () {
	
		if (!isset ($this->_result)) {
	
			/** @var Mage_Eav_Model_Entity_Attribute_Set $result  */
			$result = df_model ('eav/entity_attribute_set');

			df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute_Set);


			$result
				->setEntityTypeId (
					df_helper()->catalog()->eav()->getProductEntity()->getTypeId()
				)
				->setAttributeSetName (
					$this->getName()
				)
				->validate()
			;


			try {
				$result->save();
			}
			catch (Exception $e) {
				df_error (
					sprintf (
						'Не могу создать прикладной тип товара «%s».'
						,
						$this->getName()
					)
				);
			}

			df_assert_between (intval ($result->getId()), 1);



			if (!is_null ($this->getSkeletonId())) {
				$result->initFromSkeleton ($this->getSkeletonId());
			}
			else {
				$this->addAttributesDefault ($result);
			}

	
			$this->_result = $result;
	
		}
	
	
		df_assert ($this->_result instanceof Mage_Eav_Model_Entity_Attribute_Set);
	
		return $this->_result;
	
	}
	
	
	/**
	* @var Mage_Eav_Model_Entity_Attribute_Set
	*/
	private $_result;





	/**      
	 * @param Mage_Eav_Model_Entity_Attribute_Set $attributeSet
	 * @return Df_Catalog_Model_Installer_AttributeSet
	 */
	private function addAttributesDefault (Mage_Eav_Model_Entity_Attribute_Set $attributeSet) {

		foreach ($this->getAttributesDefaultData() as $attributeCode => $attributeData) {
			/** @var string $attributeCode */
			/** @var array $attributeData */
			df_assert_string ($attributeCode);
			df_assert_array ($attributeData);


			/** @var string|null $groupName */
			$groupName = df_a ($attributeData, 'group');

			if (!is_null ($groupName)) {
				df_assert_string ($groupName);
			}


			/** @var bool|null $isUserDefined  */
			$isUserDefined = df_a ($attributeData, 'user_defined');

			if (!is_null ($isUserDefined)) {
				df_assert_boolean ($isUserDefined);
			}


			/** @var int|null $sortOrder  */
			$sortOrder = df_a ($attributeData, 'sort_order');

			if (!is_null ($sortOrder)) {
				df_assert_integer ($sortOrder);
			}


			if (!df_empty ($groupName) || df_empty ($isUserDefined)) {

				Df_Catalog_Model_Installer_AddAttributeToSet
					::processStatic (
						$attributeCode
				    	,
						$attributeSet->getId()
						,
						!df_empty ($groupName) ? $groupName : self::GROUP_NAME__GENERAL
						,
						$sortOrder
					)
				;
			}
		}

		return $this;
	}





	/**
	 * @return array
	 */
	private function getAttributesDefaultData () {

		/** @var array $result  */
		$result =
			df_a (
				df_a (
					df_helper()->catalog()->getSetup()->getDefaultEntities()
					,
					'catalog_product'
				)
				,
				'attributes'
			)
		;

		df_result_array ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getName () {
		return $this->cfg (self::PARAM__NAME);
	}




	/**
	 * @return int|null
	 */
	private function getSkeletonId () {
		return $this->cfg (self::PARAM__SKELETON_ID);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__NAME, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__SKELETON_ID, new Df_Zf_Validate_Int(), $isRequired = false
			)
		;
	}


	const GROUP_NAME__GENERAL = 'General';

	const PARAM__NAME = 'name';
	const PARAM__SKELETON_ID = 'skeleton_id';




	/**
	 * @static
	 * @param string $name
	 * @param int|null $skeletonId [optional]
	 * @return Mage_Eav_Model_Entity_Attribute_Set
	 */
	public static function create ($name, $skeletonId = null) {

		/** @var Df_Catalog_Model_Installer_AttributeSet $instaler */
		$instaler =
			df_model (
				self::getNameInMagentoFormat()
				,
				array (
					self::PARAM__NAME => $name
					,
					self::PARAM__SKELETON_ID => $skeletonId
				)
			)
		;

		df_assert ($instaler instanceof Df_Catalog_Model_Installer_AttributeSet);


		/** @var Mage_Eav_Model_Entity_Attribute_Set $result  */
		$result = $instaler->getResult();

		df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute_Set);


		return $result;
	}



	
	
	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Installer_AttributeSet';
	}




	/**
	 * @static
	 * @return int
	 */
	private static function getEntityTypeId () {
		return df_helper()->catalog()->eav()->getProductEntity()->getTypeId();
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


