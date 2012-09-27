<?php

class Df_1C_Model_Cml2_Import_Data_Collection_Categories
	extends Df_1C_Model_Cml2_Import_Data_Collection {


	/**
	 * @override
	 * @return string
	 */
	protected function getItemClassMf () {
		return Df_1C_Model_Cml2_Import_Data_Entity_Category::getNameInMagentoFormat();
	}




	/**
	 * @override
	 * @return array
	 */
	protected function getXmlPathAsArray () {

		/** @var array $result */
		$result =
			$this->cfg (
				self::PARAM__XML_PATH_AS_ARRAY
				,
				array (
					'Группы'
					,
					'Группа'
				)
			)
		;

		df_result_array ($result);

		return $result;

	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__XML_PATH_AS_ARRAY, new Df_Zf_Validate_Array(), false)
		;
	}



	const PARAM__XML_PATH_AS_ARRAY = 'xml_path_as_array';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Collection_Categories';
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
