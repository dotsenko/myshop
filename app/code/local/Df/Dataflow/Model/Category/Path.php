<?php


class Df_Dataflow_Model_Category_Path extends Df_Core_Model_Abstract {



	/**
	 * The path can be mirrored to several categories
	 * (e.g., with same names but in different tree branches)
	 *
	 * @return array
	 */
	public function getCategories () {

		/** @var array $result  */
		$result =
			$this->findCategories ();
		;

		df_assert_array ($result);


		if (empty ($result)) {
			$result = $this->createCategories ();
		}


		df_result_array ($result);

		return $result;
	}





	/**
	 * @return array
	 */
	private function createCategories () {


		/** @var array $relevancies  */
		$relevancies =
			$this->getTheMostRelevantExistedCategoriesToInsertToInsertNewPath ()
		;

		df_assert_array ($relevancies);


		/** @var array $result  */
		$result = array ();


		if (empty ($relevancies)) {

			$relevancies []=
				array (
					self::INTERNAL_PARAM__IDENTICAL_PART_LENGTH => 0
				)
			;
		}


		foreach ($relevancies as $relevancy) {

			/** @var array $relevancy  */
			df_assert_array ($relevancy);


			/** @var int $identicalPartLength  */
			$identicalPartLength = df_a ($relevancy, self::INTERNAL_PARAM__IDENTICAL_PART_LENGTH);

			df_assert_integer ($identicalPartLength);


			/** @var Mage_Catalog_Model_Category|null $nodeToGrow  */
			$nodeToGrow = null;

			/** @var array $pathToAdd  */
			$pathToAdd = array ();



			if (0 === $identicalPartLength) {
				// Add category to the root

				$pathToAdd = $this->getPathAsNamesArray ();
			}

			else {

				/** @var array $identicalPart  */
				$identicalPart = df_a ($relevancy, self::INTERNAL_PARAM__IDENTICAL_PART);

				df_assert_array ($identicalPart);


				$nodeToGrow = df_a ($identicalPart, $identicalPartLength - 1);

				if (!is_null ($nodeToGrow)) {
					df_assert ($nodeToGrow instanceof Mage_Catalog_Model_Category);
				}


				/** @var array $pathToAdd  */
				$pathToAdd =
					array_slice (
						$this->getPathAsNamesArray ()
						,
						$identicalPartLength
						,
						$this->getNumParts () - $identicalPartLength
					)
				;

				df_assert_array ($pathToAdd);

			}


			foreach ($pathToAdd as $name) {

				/** @var string $name  */
				df_assert_string ($name);


				/**
				 * Перед созданием и сохранением товарного раздела
				 * надо обязательно надо установить текущим магазином административный,
				 * иначе возникают неприятные проблемы.
				 *
				 * В частности, для успешного сохранения товарного раздела
				 * надо отключить на время сохранения режим денормализации.
				 * Так вот, в стандартном программном коде Magento автоматически отключает
				 * режим денормализации при создании товарного раздела из административного магазина
				 * (в конструкторе товарного раздела).
				 *
				 * А если сохранять раздел, чей конструктор вызван при включенном режиме денормализации —
				 * то произойдёт сбой:
				 *
				 * SQLSTATE[23000]: Integrity constraint violation:
				 * 1452 Cannot add or update a child row:
				 * a foreign key constraint fails
				 * (`catalog_category_flat_store_1`,
				 * CONSTRAINT `FK_CAT_CTGR_FLAT_STORE_1_ENTT_ID_CAT_CTGR_ENTT_ENTT_ID`
				 * FOREIGN KEY (`entity_id`) REFERENCES `catalog_category_entity` (`en)
				 *
				 * @var Mage_Catalog_Model_Category $newCategory
				 */
				$newCategory =
					df_helper()->catalog()->category()->createAndSave (
						array (
							Df_Catalog_Const::CATEGORY_PARAM_PATH =>
									!$nodeToGrow
								?
									$this->getDefaultSystemPath ()
								:
									$nodeToGrow->getDataUsingMethod (
										Df_Catalog_Const::CATEGORY_PARAM_PATH
									)
							,
							Df_Catalog_Const::CATEGORY_PARAM_NAME => $name
							,
							Df_Catalog_Const::CATEGORY_PARAM_IS_ACTIVE => 1
							,
							Df_Catalog_Const::CATEGORY_PARAM_IS_ANCHOR => 1
							,
							Df_Catalog_Const::CATEGORY_PARAM_DISPLAY_MODE =>
								Mage_Catalog_Model_Category::DM_MIXED
						)
						,
						$this->getStore ()->getId()
					)
				;

				df_assert ($newCategory instanceof Mage_Catalog_Model_Category);

				$nodeToGrow = $newCategory;

				df_assert ($nodeToGrow instanceof Mage_Catalog_Model_Category);

			}

			if (!is_null ($result)) {
				$result []= $nodeToGrow;
			}
		}

		df_assert_array ($result);

	    return $result;
	}





	/**
	 * @return string
	 */
	private function getDefaultSystemPath () {

		/** @var int $rootId  */
		$rootId = $this->getStore ()->getRootCategoryId();

		df_assert_integer ($rootId);


        if (0 === $rootId) {

			/** @var Mage_Core_Model_Store $store  */
	        $store =  Mage::app()->getStore(Mage_Core_Model_App::DISTRO_STORE_ID);

			df_assert ($store instanceof Mage_Core_Model_Store);


			$rootId = $store->getRootCategoryId();

        }


		/** @var string $result  */
		$result =
			implode (
				self::PARTS_SEPARATOR
				,
				df_clean (
					array (
						  self::FIRST_PART_FOR_ROOT
					      ,
					      $rootId
					)
				)
			)
		;

		df_result_string ($result);

		return $result;
	}






	/**
	 * @return array
	 */
	private function getTheMostRelevantExistedCategoriesToInsertToInsertNewPath () {

		/** @var array $result  */
		$result = array ();

		/** @var int $identicalPartLength  */
		$identicalPartLength = 0;


		/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection $categoriesWithSameName  */
		$categoriesWithSameName = $this->findCategoriesByName ($this->getLocalRootName());

		df_helper()->catalog()->assert()->categoryCollection ($categoriesWithSameName);

		foreach ($categoriesWithSameName as $category) {
			/** @var Mage_Catalog_Model_Category $category  */
			df_assert ($category instanceof Mage_Catalog_Model_Category);


			/** @var array $currentIdenticalPart  */
			$currentIdenticalPart =
				$this->getIdenticalPartBetweenRootAndNewPath (
					$category
				)
			;

			df_assert_array ($currentIdenticalPart);


			/** @var int $currentIdenticalPartLength  */
			$currentIdenticalPartLength = count ($currentIdenticalPart);

			df_assert_integer ($currentIdenticalPartLength);


			if ($identicalPartLength <= $currentIdenticalPartLength) {

				$identicalPartLength = $currentIdenticalPartLength;

				df_assert_integer ($identicalPartLength);


				$result []=
					array (
						self::INTERNAL_PARAM__IDENTICAL_PART_LENGTH => $identicalPartLength
					    ,
						self::INTERNAL_PARAM__CATEGORY => $category
						,
						self::INTERNAL_PARAM__IDENTICAL_PART => $currentIdenticalPart
					)
				;
			}
		}

		df_assert_array ($result);

	    return $result;
	}






	/**
	 * @param  Mage_Catalog_Model_Category $root
	 * @return array
	 */
	private function getIdenticalPartBetweenRootAndNewPath (Mage_Catalog_Model_Category $root) {

		df_assert ($root instanceof Mage_Catalog_Model_Category);

		/** @var array $result  */
		$result = array ();

		if ($root->getName () === df_a ($this->getPathAsNamesArray (), 0)) {

			$result []= $root;

			/** @var int $depth  */
			$depth = 1;


			/** @var Mage_Catalog_Model_Category $currentNode  */
			$currentNode = $root;

			df_assert ($currentNode instanceof Mage_Catalog_Model_Category);



			while ($depth < $this->getNumParts ()) {

				/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection $children  */
				$children = $currentNode->getChildrenCategories ();

				df_helper()->catalog()->assert()->categoryCollection ($children);


				/** @var Mage_Catalog_Model_Category|null $relevantChild  */
				$relevantChild = null;

				foreach ($children as $child) {
					/** @var Mage_Catalog_Model_Category $child */
					df_assert ($child instanceof Mage_Catalog_Model_Category);

					if ($child->getName () === df_a ($this->getPathAsNamesArray (), $depth)) {

						$relevantChild = $child;

						$result []= $relevantChild;

						break;
					}
				}

				if (is_null ($relevantChild)) {
					break;
				}
				else {
					$currentNode = $relevantChild;
					df_assert ($currentNode instanceof Mage_Catalog_Model_Category);
				}
			    $depth++;
			}
		}

		df_result_array ($result);

	    return $result;
	}






	/**
	 * @param array $haystack
	 * @param array $needle
	 * @return bool
	 */
	private function isContains (array $haystack, array $needle) {

		df_param_array ($haystack, 0);
		df_param_array ($needle, 1);

		/** @var bool $result  */
		$result = true;

		if (!empty ($needle)) {


			/** @var string $needleRoot  */
			$needleRoot = df_a ($needle, 0);

			df_assert_string ($needleRoot);


			/** @var int|false $indexOfNeedleRootInHaystack  */
			$indexOfNeedleRootInHaystack =
				array_search ($needleRoot, $haystack)
			;

			if (false !== $indexOfNeedleRootInHaystack) {
				df_assert_integer ($indexOfNeedleRootInHaystack);
			}



			if (FALSE === $indexOfNeedleRootInHaystack) {
				$result = false;
			}

			else {

				for ($offset = 1; $offset < $this->getNumParts (); $offset++) {
					/** @var int $offset */


					/** @var string|null $currentNeedleValue */
					$currentNeedleValue = df_a ($needle, $offset);

					if (!is_null ($currentNeedleValue)) {
						df_assert_string ($currentNeedleValue);
					}


					/** @var string|null $currentHaystackValue */
					$currentHaystackValue = df_a ($haystack, $offset + $indexOfNeedleRootInHaystack);

					if (!is_null ($currentHaystackValue)) {
						df_assert_string ($currentHaystackValue);
					}


					if (
							$currentNeedleValue
						!==
							$currentHaystackValue
					) {
						$result = false;
						break;
					}
				}
			}
		}

		df_result_boolean ($result);

	    return $result;
	}






	/**
	 * @return array
	 */
	private function findCategories () {

		/** @var array $result  */
		$result = array ();


		/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection $categories  */
		$categories = $this->findCategoriesByName ($this->getNodeName ());

		df_helper()->catalog()->assert()->categoryCollection ($categories);

		foreach ($categories as $category) {

			/** @var Mage_Catalog_Model_Category $category */
			df_assert ($category instanceof Mage_Catalog_Model_Category);


			if (
				$this->isContains (
					$this->getPathForCategory ($category)
					,
					$this->getPathAsNamesArray ()
				)
			) {
				// Grab the first relevant category
				$result[] = $category;
			}
		}

		df_result_array ($result);

		return $result;
	}



	
	


	/**
	 * @param  array $path
	 * @return string
	 */
	private function pathToString (array $path) {

		df_param_array ($path, 0);		
		
		/** @var string $result  */
		$result =
			implode (
				self::PARTS_SEPARATOR
				,
				array_map (
					array ($this, 'escapeSlash')
					,
					$path
				)
			)
		;


		df_result_string ($result);

		return $result;
	}

	
	
	

	/**
	 * @param  string $string
	 * @return string
	 */
	public function escapeSlash ($string) {

		df_param_string ($string, 0);

		/** @var string $result  */
		$result = str_replace (self::PARTS_SEPARATOR, '\/', $string);

		df_result_string ($result);

		return $result;
	}



	


	/**
	 * @param  Mage_Catalog_Model_Category $category
	 * @return array
	 */
	private function getPathForCategory (Mage_Catalog_Model_Category $category) {

		df_assert ($category instanceof Mage_Catalog_Model_Category);

		/** @var array $result  */
		$result = array ();


		/** @var Mage_Catalog_Model_Category $currentCategory  */
		$currentCategory = $category;

		df_assert ($currentCategory instanceof Mage_Catalog_Model_Category);



		while (0 !== $currentCategory->getParentId ()) {

			/** @var string $categoryName  */
			$categoryName = $category->getName ();

			df_assert_string ($categoryName);


			$result []= $categoryName;


			$currentCategory = $currentCategory->getParentCategory ();

			df_assert ($currentCategory instanceof Mage_Catalog_Model_Category);

		}


	    return $result;
	}





	/**
	 * Этот метод работает неоптимально.
	 * Вместо того, чтобы делать запрос к БД на каждый вызов метода,
	 * разумнее сделать карту:
	 * <название товарного раздела> => <товарный раздел>
	 *
	 * @param  string $name
	 * @return Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	 */
	private function findCategoriesByName ($name) {

		df_param_string ($name, 0);


		/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection $result */
 		$result =
			Mage::getResourceModel(Df_Catalog_Const::CATEGORY_COLLECTION_CLASS_MF)
		;

		df_helper()->catalog()->assert()->categoryCollection ($result);

		$result->setStore($this->getStore());
		$result->addAttributeToSelect (Df_Varien_Const::ASTERICK);
		$result->addFieldToFilter ('name', $name);


	    return $result;
	}






	/**
	 * @return int
	 */
	private function getNumParts () {

		if (!isset ($this->_numParts)) {

			/** @var int $result  */
			$result =
				count (
					$this->getPathAsNamesArray ()
				)
			;

			df_assert_integer ($result);

			$this->_numParts = $result;

		}

		df_result_integer ($this->_numParts);

	    return $this->_numParts;
	}



	/**
	 * @var int
	 */
	private $_numParts;







	/**
	 * @return string
	 */
	private function getLocalRootName () {

		if (!isset ($this->_localRootName)) {


			/** @var string $result  */
			$result =
 	            df_a ($this->getPathAsNamesArray (), 0)
			;

			df_assert_string ($result);

			$this->_localRootName = $result;

		}

		df_result_string ($this->_localRootName);

		return $this->_localRootName;
	}


	/**
	 * @var string
	 */
	private $_localRootName;








	/**
	 * @return string
	 */
	private function getNodeName () {

		if (!isset ($this->_nodeName)) {

			/** @var string $result  */
			$result =
 	            df_a ($this->getPathAsNamesArray (), $this->getNumParts () - 1)
			;

			df_assert_string ($result);

			$this->_nodeName = $result;

		}


		df_result_string ($this->_nodeName);

		return $this->_nodeName;
	}



	/**
	 * @var string
	 */
	private $_nodeName;








	/**
	 * @return string
	 */
	private function getPathAsString () {

		if (!isset ($this->_pathAsString)) {

			/** @var string $result  */
			$result =
				$this->pathToString (
					$this->getPathAsNamesArray ()
				)
			;

			df_assert_string ($result);

			$this->_pathAsString = $result;

		}

		df_result_string ($this->_pathAsString);

	    return $this->_pathAsString;
	}




	/**
	 * @var string
	 */
	private $_pathAsString;






	/**
	 * @return array
	 */
	private function getPathAsNamesArray () {

		/** @var array $result  */
		$result = $this->cfg (self::PARAM_PATH_AS_NAMES_ARRAY);

		df_result_array ($result);

		return $result;
	}






	/**
	 * @return Mage_Core_Model_Store
	 */
	private function getStore () {

		$result = $this->getData (self::PARAM_STORE);

		df_assert ($result instanceof Mage_Core_Model_Store);

		return $result;
	}





	const PARAM_STORE = 'store';
	const PARAM_STORE_TYPE = 'Mage_Core_Model_Store';

	const PARAM_PATH_AS_NAMES_ARRAY = 'pathAsNamesArray';




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (
				self::PARAM_STORE, self::PARAM_STORE_TYPE
			)
			->addValidator (
				self::PARAM_PATH_AS_NAMES_ARRAY, new Df_Zf_Validate_Array ()
			)
		;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Category_Path';
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



	const INTERNAL_PARAM__IDENTICAL_PART_LENGTH = 'identicalPartLength';
	const INTERNAL_PARAM__IDENTICAL_PART = 'identicalPart';
	const INTERNAL_PARAM__CATEGORY = 'category';
	const PARTS_SEPARATOR = '/';
	const FIRST_PART_FOR_ROOT = '1';

}