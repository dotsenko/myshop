<?php


class Df_AccessControl_Model_Role extends Df_Core_Model_Abstract {


	/**
	 * Говорим системе использовать insert, а не update
	 * @param int $roleId
	 * @return Df_AccessControl_Model_Role
	 */
	public function prepareForInsert ($roleId) {

		df_param_integer ($roleId, 0);

		$this->getResource()->prepareForInsert ();

		$this
			->setId ($roleId)
			->isObjectNew (true)
		;

		return $this;

	}




	/**
	 * Retrieve model resource
	 *
	 * @return Df_AccessControl_Model_Resource_Role
	 */
	public function getResource() {

		$result = parent::getResource();

		df_assert ($result instanceof Df_AccessControl_Model_Resource_Role);

		return $result;
	}





	
	/**
	 * @return bool
	 */
	public function isModuleEnabled () {
	
		if (!isset ($this->_moduleEnabled)) {
	
			/** @var bool $result  */
			$result = 
				!is_null ($this->getId ())
			;
	
	
			df_assert_boolean ($result);
	
			$this->_moduleEnabled = $result;
	
		}
	
	
		df_result_boolean ($this->_moduleEnabled);
	
		return $this->_moduleEnabled;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_moduleEnabled;	
	




	/**
	 * @param int[] $categoryIds
	 * @return Df_AccessControl_Model_Role
	 */
	public function setCategoryIds (array $categoryIds) {

		df_param_array ($categoryIds, 0);

		$this->_categoryIds = $categoryIds;

		$this->setDataChanges (true);

		return $this;
	}







	/**
	 * @return int[]
	 */
	public function getCategoryIdsWithAncestors () {

		df_assert ($this->isModuleEnabled());

		if (!isset ($this->_categoryIdsWithAncestors)) {

			/** @var int[] $result  */
			$result =
				$this->getCategoryIds()
			;

			foreach ($this->getCategories() as $category) {
				/** @var Mage_Catalog_Model_Category $category */
				df_assert ($category instanceof Mage_Catalog_Model_Category);

				$result =
					array_merge (
						$result
						,
						$category->getParentIds()
					)
				;
			}


			df_assert_array ($result);

			$this->_categoryIdsWithAncestors = $result;

		}


		df_result_array ($this->_categoryIdsWithAncestors);

		return $this->_categoryIdsWithAncestors;

	}


	/**
	* @var int[]
	*/
	private $_categoryIdsWithAncestors;




	
	
	/**
	 * @return Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	 */
	private function getCategories () {
	
		if (!isset ($this->_categories)) {
	
			/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection $result  */
			$result = 
				Mage::getResourceModel (
					Df_Catalog_Const::CATEGORY_COLLECTION_CLASS_MF
				)
			;

			df_helper()->catalog()->assert()->categoryCollection ($result);


			$result
				->setFlag (
					Df_AccessControl_Model_Handler_Catalog_Category_Collection_ExcludeForbiddenCategories
						::DISABLE_PROCESSING
					,
					true
				)
			;

			$result->addAttributeToSelect ('*');
			$result->addIdFilter ($this->getCategoryIds());
	

			df_helper()->catalog()->assert()->categoryCollection ($result);

			$this->_categories = $result;
	
		}
	

		df_helper()->catalog()->assert()->categoryCollection ($this->_categories);

		return $this->_categories;
	
	}
	
	
	/**
	* @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	*/
	private $_categories;	


	
	
	
	
	
	/**
	 * @return int[]
	 */
	public function getCategoryIds () {

		df_assert ($this->isModuleEnabled());
	
		if (!isset ($this->_categoryIds)) {
	
			/** @var int[] $result  */
			$result =
				df_parse_csv (
					$this->getCategoryIdsAsString()
				)
			;
	
			df_assert_array ($result);
	
			$this->_categoryIds = $result;
	
		}
	
	
		df_result_array ($this->_categoryIds);
	
		return $this->_categoryIds;
	
	}
	
	
	/**
	* @var int[]
	*/
	private $_categoryIds;








    /**
     * Prepare data before saving
     *
     * @return Df_Core_Model_Abstract
     */
    protected function _beforeSave() {

		$this
			->setData (
				self::PARAM_CATEGORIES
				,
				implode (Df_Core_Const::T_COMMA, $this->getCategoryIds())
			)
		;


		parent::_beforeSave();
    }





	/**
	 * @return string
	 */
	private function getCategoryIdsAsString () {

		df_assert ($this->isModuleEnabled());

		/** @var string $result  */
		$result =
			$this->cfg (self::PARAM_CATEGORIES, Df_Core_Const::T_EMPTY)
		;


		df_result_string ($result);

		return $result;

	}



    /**
	 * @override
     * Initialize resource
     */
    protected function _construct() {
		parent::_construct();
        $this->_init (self::RESOURCE_MODEL);
    }





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_AccessControl_Model_Role';
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
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'df_access_control_role';



    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getGift() in this case
     *
     * @var string
     */
    protected $_eventObject = 'role';



	const PARAM_CATEGORIES = 'categories';
	const PARAM_STORES = 'stores';

	const RESOURCE_MODEL = 'df_access_control/role';
	

}

