<?php

class Df_1C_Model_Cml2_Registry_Import extends Df_Core_Model_Abstract {



	/**
	 * @return Df_1C_Model_Cml2_Registry_Import_Collections
	 */
	public function collections () {

		/** @var Df_1C_Model_Cml2_Registry_Import_Collections $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Model_Cml2_Registry_Import_Collections $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Registry_Import_Collections::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_Registry_Import_Collections);

		}

		return $result;

	}






	/**
	 * @param string $area
	 * @return Df_1C_Model_Cml2_Registry_Import_Files
	 */
	public function files ($area) {

		df_param_string ($area, 0);

		if (!isset ($this->_files [$area])) {

			/** @var Df_1C_Model_Cml2_Registry_Import_Files $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Registry_Import_Files::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Registry_Import_Files::PARAM__AREA => $area
					)
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_Registry_Import_Files);

			$this->_files [$area] = $result;

		}


		df_assert ($this->_files [$area] instanceof Df_1C_Model_Cml2_Registry_Import_Files);

		return $this->_files [$area];

	}


	/**
	* @var Df_1C_Model_Cml2_Registry_Import_Files[]
	*/
	private $_files = array ();



	/**
	 * @return Mage_Catalog_Model_Category
	 */
	public function getRootCategory () {

		if (!isset ($this->_rootCategory)) {

			/** @var Mage_Catalog_Model_Category $result  */
			$result =
				df_model (
					Df_Catalog_Const::CATEGORY_CLASS
				)
			;

			df_assert ($result instanceof Mage_Catalog_Model_Category);


			$result->load ($this->getRootCategoryId ());


			df_assert ($result instanceof Mage_Catalog_Model_Category);

			$this->_rootCategory = $result;

		}


		df_assert ($this->_rootCategory instanceof Mage_Catalog_Model_Category);

		return $this->_rootCategory;

	}


	/**
	* @var Mage_Catalog_Model_Category
	*/
	private $_rootCategory;




	/**
	 * @return int
	 */
	private function getRootCategoryId () {

		if (!isset ($this->_rootCategoryId)) {

			/** @var int $result  */
			$result =
				intval (
					df_helper()->_1c()->cml2()->getStoreProcessed()->getRootCategoryId()
				)
			;

			df_assert_integer ($result);


			if (0 === $result) {

				df_error (
					'В обрабатываемом магазине должен присутствовать корневой товарный раздел'
				);

			}


			df_assert_integer ($result);

			$this->_rootCategoryId = $result;

		}


		df_result_integer ($this->_rootCategoryId);

		return $this->_rootCategoryId;

	}


	/**
	* @var int
	*/
	private $_rootCategoryId;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Registry_Import';
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

