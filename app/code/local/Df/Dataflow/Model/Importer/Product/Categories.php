<?php


class Df_Dataflow_Model_Importer_Product_Categories
	extends Df_Dataflow_Model_Importer_Product_Specialized {




	/**
	 * @override
	 * @throws Exception
	 * @return Df_Dataflow_Model_Importer_Product_Categories
	 */
	public function process () {
		try {
			if (!df_empty ($this->getImportedValue ())) {
				if (count ($this->getCategoryIds ())) {
					$this
						->getProduct ()
						->setCategoryIds (
							$this->getCategoryIds ()
						)
					;
				}
			}
		}
		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}
		return $this;
	}






	/**
	 * @param  array $path
	 * @return array
	 */
	public function getCategoriesByPath ($path) {

		df_param_array ($path, 0);


		/** @var Df_Dataflow_Model_Category_Path $categoryPath  */
		$categoryPath =
			df_model (
				Df_Dataflow_Model_Category_Path::getNameInMagentoFormat()
				,
				array (
					Df_Dataflow_Model_Category_Path
						::PARAM_PATH_AS_NAMES_ARRAY => $path
				    ,
				    Df_Dataflow_Model_Category_Path
						::PARAM_STORE => $this->getStore ()
				)
			)
		;

		df_assert ($categoryPath instanceof Df_Dataflow_Model_Category_Path);


		/** @var array $result  */
		$result =
			$categoryPath->getCategories ()
		;

		df_result_array ($result);

		return $result;
	}








	/**
	 * @return array
	 */
	private function getCategoryIds () {

		if (!isset ($this->_categoryIds)) {

			/** @var array $result  */
			$result = array ();

			foreach ($this->getCategories () as $category) {
				/** @var Mage_Catalog_Model_Category $category  */
				df_assert ($category instanceof Mage_Catalog_Model_Category);

				$result []= $category->getId ();
			}

		    $result = array_unique ($result);

			df_assert_array ($result);

			$this->_categoryIds = $result;
		}

		df_result_array ($this->_categoryIds);

	    return $this->_categoryIds;
	}



	/**
	 * @var array
	 */
	private $_categoryIds;








	/**
	 * @return array
	 */
	private function getCategories () {

		if (!isset ($this->_categories)) {

			/** @var array $result  */
			$result = array ();

			foreach ($this->getPaths () as $path) {
				/** @var array $path  */
				df_assert_array ($path);


				$result =
					array_merge (
						$result
						,
						df_clean (
							$this->getCategoriesByPath ($path)
						)
					)
				;
			}

			df_assert_array ($result);

			$this->_categories = $result;

		}

		df_result_array ($this->_categories);

	    return $this->_categories;
	}


	/**
	 * @var array
	 */
	private $_categories;







	/**
	 * @return array
	 */
	private function getPaths () {

		if (!isset ($this->_paths)) {

			/** @var array $result  */
			$result = array ();

			foreach ($this->getParsers () as $parser) {
				/** @var Df_Dataflow_Model_Importer_Product_Categories_Parser $parser */
				df_assert ($parser instanceof Df_Dataflow_Model_Importer_Product_Categories_Parser);

				$result = $parser->getPaths ();
				if (!df_empty ($result)) {
					break;
				}
			}

			df_assert_array ($result);

			$this->_paths = $result;

		}

		df_result_array ($this->_paths);

	    return $this->_paths;
	}



	/**
	 * @var array
	 */
	private $_paths;







	/**
	 * @return array
	 */
	protected function getParsers () {

		if (!isset ($this->_parsers)) {

			/** @var array $result  */
			$result =
				array_map (
					'df_model'
					,
					array (
						Df_Dataflow_Model_Importer_Product_Categories_Format_Simple::getNameInMagentoFormat()
						,
						Df_Dataflow_Model_Importer_Product_Categories_Format_Json::getNameInMagentoFormat()
					    ,
					    Df_Dataflow_Model_Importer_Product_Categories_Format_Null::getNameInMagentoFormat()
					)
				)
			;

			df_assert_array ($result);

		    foreach ($result as $parser) {
				/** @var Df_Dataflow_Model_Importer_Product_Categories_Parser $parser */
				df_assert ($parser instanceof Df_Dataflow_Model_Importer_Product_Categories_Parser);


				$parser
					->setData (
						array (
							Df_Dataflow_Model_Importer_Product_Categories_Parser
								::PARAM_IMPORTED_VALUE => $this->getImportedValue ()
						)
					)
				;
		    }


			df_assert_array ($result);

			$this->_parsers = $result;

		}

		df_result_array ($this->_parsers);

		return $this->_parsers;
	}



	/**
	 * @var array
	 */
	private $_parsers;







	/**
	 * @return string|null
	 */
	private function getImportedValue () {

		/** @var string|null $result  */
		$result = df_a ($this->getImportedRow (), self::IMPORTED_KEY);

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;
	}





	/**
	 * @return Mage_Core_Model_Store
	 */
	protected function getStore () {

		/** @var Mage_Core_Model_Store $result  */
		$result = $this->cfg (self::PARAM_STORE);

		df_assert ($result instanceof Mage_Core_Model_Store);

		return $result;
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (
				self::PARAM_STORE, Df_Core_Const::STORE_CLASS
			)
		;
	}





	const PARAM_STORE = 'store';
	const IMPORTED_KEY = 'df_categories';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Product_Categories';
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