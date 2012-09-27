<?php

class Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom_Option
	extends Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom {



	/**
	 * @override
	 * @return string
	 */
	public function getExternalId () {

		/** @var string $result  */
		$result = $this->getEntityParam ('ИдЗначения');

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	public function getValue () {

		/** @var string $result  */
		$result =
				is_null ($this->getOption())
			?
				Df_Core_Const::T_EMPTY
			:
				$this->getOption()->getId()
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	public function getValueForDataflow () {

		/** @var string $result  */
		$result =
				is_null ($this->getOption())
			?
				Df_Core_Const::T_EMPTY
			:
				$this->getOption()->getData ('value')
		;

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return Mage_Eav_Model_Entity_Attribute_Option|null
	 */
	private function getOption () {

		if (!isset ($this->_option) && !$this->_optionIsNull) {

			/** @var Mage_Eav_Model_Entity_Attribute_Option|null $result  */
			$result = null;

			df_assert (!is_null ($this->getExternalId()));


			/** @var Mage_Eav_Model_Entity_Attribute_Source_Table $source  */
			$source = $this->getAttribute()->getSource();

			df_assert ($source instanceof Mage_Eav_Model_Entity_Attribute_Source_Table);


			/** @var Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection|Mage_Eav_Model_Mysql4_Entity_Attribute_Option_Collection $options */
			$options = Mage::getResourceModel('eav/entity_attribute_option_collection');

			df_helper()->eav()->assert()->entityAttributeOptionCollection ($options);


			$options->setPositionOrder ('asc');
			$options->setAttributeFilter ($this->getAttribute()->getId());
			$options->setStoreFilter($this->getAttribute()->getStoreId());

			$options->addFieldToFilter(Df_1C_Const::ENTITY_1C_ID, $this->getExternalId());


			df_assert_between (count ($options), 1, 1);

			$result = $options->fetchItem();



			if (!is_null ($result)) {
				df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute_Option);
			}
			else {
				$this->_optionIsNull = true;
			}

			$this->_option = $result;

		}


		if (!is_null ($this->_option)) {
			df_assert ($this->_option instanceof Mage_Eav_Model_Entity_Attribute_Option);
		}


		return $this->_option;

	}


	/**
	* @var Mage_Eav_Model_Entity_Attribute_Option|null
	*/
	private $_option;

	/**
	 * @var bool
	 */
	private $_optionIsNull = false;






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom_Option';
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


