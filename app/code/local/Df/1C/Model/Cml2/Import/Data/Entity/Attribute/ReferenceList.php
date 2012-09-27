<?php

class Df_1C_Model_Cml2_Import_Data_Entity_Attribute_ReferenceList
	extends Df_1C_Model_Cml2_Import_Data_Entity_Attribute {


	/**
	 * @override
	 * @return string
	 */
	public function getBackendModel() {
		return Df_Core_Const::T_EMPTY;
	}



	/**
	 * @override
	 * @return string
	 */
	public function getBackendType() {
		return 'int';
	}



	/**
	 * @override
	 * @return string
	 */
	public function getFrontendInput() {
		return 'select';
	}



	/**
	 * @override
	 * @return string
	 */
	public function getSourceModel() {
		return Df_Core_Const::T_EMPTY;
	}



	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_ReferenceListPart_Items
	 */
	public function getItems () {

		if (!isset ($this->_items)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_ReferenceListPart_Items $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_ReferenceListPart_Items
						::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_ReferenceListPart_Items
							::PARAM__SIMPLE_XML	=> $this->getSimpleXmlElement()
					)
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_ReferenceListPart_Items);

			$this->_items = $result;

		}


		df_assert ($this->_items instanceof Df_1C_Model_Cml2_Import_Data_Collection_ReferenceListPart_Items);

		return $this->_items;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_ReferenceListPart_Items
	*/
	private $_items;




	/**
	 * @return array
	 */
	public function getOptionsInMagentoFormat () {

		if (!isset ($this->_optionsInMagentoFormat)) {

			/** @var array $values  */
			$values = array ();


			/** @var int $optionIndex  */
			$optionIndex = 0;


			foreach ($this->getItems() as $item) {

				/** @var Df_1C_Model_Cml2_Import_Data_Entity_ReferenceListPart_Item $item */
				df_assert ($item instanceof Df_1C_Model_Cml2_Import_Data_Entity_ReferenceListPart_Item);

				/** @var string $optionName */
				$optionName =
					implode (
						'_'
						,
						array (
							'option'
							,
							$optionIndex
						)
					)
				;


				$values [$optionName] =
					array (
						$item->getName()
					)
				;

				$optionIndex++;

			}



			/** @var string[] $optionNames  */
			$optionNames = array_keys ($values);

			df_assert_array ($optionNames);


			/** @var array $optionStubs  */
			$optionStubs =
				df_array_combine (
					$optionNames
					,
					df_array_fill (
						0, count ($optionNames), null
					)
				)
			;

			df_assert_array ($optionStubs);


			/** @var array $result  */
			$result =
				array (
					'value' => $values
					,
					'order' => $optionStubs
					,
					'delete' => $optionStubs
				)
			;


			df_assert_array ($result);

			$this->_optionsInMagentoFormat = $result;

		}


		df_result_array ($this->_optionsInMagentoFormat);

		return $this->_optionsInMagentoFormat;

	}


	/**
	* @var array
	*/
	private $_optionsInMagentoFormat;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Attribute_ReferenceList';
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
