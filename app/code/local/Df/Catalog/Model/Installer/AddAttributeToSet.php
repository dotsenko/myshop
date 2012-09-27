<?php

class Df_Catalog_Model_Installer_AddAttributeToSet extends Df_Core_Model_Abstract {


	/**
	 * @return Df_Catalog_Model_Installer_AddAttributeToSet
	 */
	public function process () {

		/**
		 * Этот метод добавляет группу только по необходимости (при её отсутствии)
		 */

		df_helper()->catalog()->product()
			->addGroupToAttributeSetIfNeeded (
				$this->getSetId()
				,
				$this->getGroupName()
			)
		;


		df_helper()->catalog()->getSetup()
			->addAttributeToSet (
				self::getEntityTypeId()
				,
				$this->getSetId()
				,
				$this->getGroupName()
				,
				$this->getAttributeCode()
				,
				$this->getOrdering()
			)
		;

		return $this;
	}





	/**
	 * @return string
	 */
	private function getAttributeCode () {
		return $this->cfg (self::PARAM__ATTRIBUTE_CODE);
	}





	/**
	 * @return string
	 */
	private function getGroupName () {
		return $this->cfg (self::PARAM__GROUP_NAME, self::GROUP_NAME__GENERAL);
	}





	/**
	 * @return int|null
	 */
	private function getOrdering () {
		return $this->cfg (self::PARAM__ORDERING);
	}





	/**
	 * @return int
	 */
	private function getSetId () {
		return $this->cfg (self::PARAM__SET_ID);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__GROUP_NAME, new Df_Zf_Validate_String(), $isRequired = false
			)
			->addValidator (
				self::PARAM__ATTRIBUTE_CODE, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__SET_ID, new Df_Zf_Validate_Int()
			)
			->addValidator (
				self::PARAM__ORDERING, new Df_Zf_Validate_Int(), $isRequired = false
			)
		;
	}


	const GROUP_NAME__GENERAL = 'General';

	const PARAM__ATTRIBUTE_CODE = 'attribute_code';
	const PARAM__ORDERING = 'ordering';
	const PARAM__SET_ID = 'set_id';
	const PARAM__GROUP_NAME = 'group_name';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Installer_AddAttributeToSet';
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





	/**
	 * @param string $attributeCode
	 * @param int $setId
	 * @param string $groupName
	 * @param int $ordering [optional]
	 */
	public static function processStatic ($attributeCode, $setId, $groupName, $ordering = null) {

		df_param_string ($attributeCode, 0);
		df_param_integer ($setId, 1);
		df_param_string ($groupName, 2);

		if (!is_null ($ordering)) {
			df_param_integer ($ordering, 3);
		}


		/** @var Df_Catalog_Model_Installer_AddAttributeToSet $instance  */
		$instance =
			df_model (
				self::getNameInMagentoFormat()
				,
				array (
					self::PARAM__GROUP_NAME => $groupName
					,
					self::PARAM__ATTRIBUTE_CODE => $attributeCode
					,
					self::PARAM__SET_ID => $setId
					,
					self::PARAM__ORDERING => $ordering
				)
			)
		;

		df_assert ($instance instanceof Df_Catalog_Model_Installer_AddAttributeToSet);


		$instance->process();

	}

}


