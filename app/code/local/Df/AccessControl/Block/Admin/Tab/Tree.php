<?php


class Df_AccessControl_Block_Admin_Tab_Tree	extends Mage_Adminhtml_Block_Catalog_Category_Tree {



	/**
	 * @return string
	 */
    public function getSelectedCategoriesAsString () {

		/** @var string $result  */
        $result =
			implode (
				Df_Core_Const::T_COMMA
				,
				$this->getSelectedCategories()
			)
		;

		df_result_string ($result);

		return $result;
    }






	/**
	 * @param bool|null $expanded [optional]
	 * @return string
	 */
	public function getLoadTreeUrl ($expanded = null) {

		/** @var string $result  */
		$result =
			$this
				->getUrl(
					'df_access_control/admin/categories'
					,
					array (
						'rid' => $this->getRoleId()
					)
				)
		;

		df_result_string ($result);

		return $result;
	}






	/**
	 * @override
	 * @param  Mage_Catalog_Model_Category|null $parentNodeCategory [optional]
	 * @param  int $recursionLevel [optional]
	 * @return Varien_Data_Tree_Node|null
	 */
    public function getRoot ($parentNodeCategory = null, $recursionLevel = 3) {

		if (!is_null ($parentNodeCategory)) {
			df_assert ($parentNodeCategory instanceof Mage_Catalog_Model_Category);
		}

		df_param_integer ($recursionLevel, 1);

		
		/** @var Varien_Data_Tree_Node|null $result  */
        $result = parent::getRoot($parentNodeCategory, $recursionLevel);

		
		if (!is_null ($result)) {
			df_assert ($result instanceof Varien_Data_Tree_Node);
		}

		
        return $result;
    }





	/**
	 * @override
	 * @return string|null
	 */
    public function getTemplate() {

		/** @var string|null $result  */
        $result =
				!(
						df_enabled (Df_Core_Feature::ACCESS_CONTROL)
					&&
						df_cfg()->admin()->access_control()->getEnabled ()
				)
			?
				null
			:
				self::TEMPLATE

		;


		if (!is_null($result)) {
			/*************************************
			 * Проверка результата работы метода
			 */
			df_result_string ($result);
			/*************************************/
		}


		return $result;
    }




	/**
	 * @return bool
	 */
	public function isTreeEmpty () {

		/** @var bool $result  */
		$result =
			!(
					!is_null ($this->getRoot())
				&&
					$this->getRoot()->hasChildren()
			)
		;


		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return bool
	 */
	public function isRootVisible () {

		/** @var bool $result  */
		$result =
				!is_null ($this->getRoot())
			&&
				$this->getRoot()->getDataUsingMethod ('is_visible')
		;


		df_result_boolean ($result);

		return $result;

	}






	/**
	 * @param  int $categoryId
	 * @param  int $roleId
	 * @return array
	 */
    public function getChildrenNodes ($categoryId, $roleId) {

		df_param_integer ($categoryId, 0);
		df_param_integer ($roleId, 1);


		/** @var array $result  */
		$result = array ();


		/** @var Mage_Catalog_Model_Category $category  */
        $category =
			df_model (Df_Catalog_Const::CATEGORY_CLASS_MF)
				->load (
					$categoryId
				)
		;

		df_assert ($category instanceof Mage_Catalog_Model_Category);



		/** @var Varien_Data_Tree_Node|null $node  */
        $node = $this->getRoot ($category, 1)->getTree()->getNodeById ($categoryId);

		if (!is_null ($node)) {
			df_assert ($node instanceof Varien_Data_Tree_Node);
		}

		if (!is_null ($node) && $node->hasChildren()) {

			foreach ($node->getChildren() as $child) {

				/** Varien_Data_Tree_Node $node */
				df_assert ($node instanceof Varien_Data_Tree_Node);


				$result[] =
					$this->_getNodeJson ($child)
				;
			}
		}


		df_assert_array ($result);

        return $result;
    }







    /**
     * Get JSON of a tree node or an associative array
     *
	 * @override
     * @param Varien_Data_Tree_Node|array $node
     * @param int $level [optional]
     * @return array
     */
    protected function _getNodeJson ($node, $level = 1) {

        if (is_array($node)) {
            $node = new Varien_Data_Tree_Node ($node, 'entity_id', new Varien_Data_Tree);
        }

		df_assert ($node instanceof Varien_Data_Tree_Node);
		df_param_integer ($level, 1);


		/** @var array $result  */
        $result = parent::_getNodeJson ($node, $level);


		/** @var bool $isParent  */
        $isParent = $this->_isParentSelectedCategory ($node);

		df_assert_boolean ($isParent);



		/** @var bool $needBeChecked  */
		$needBeChecked =
			in_array (
				$node->getId()
				,
				$this->getSelectedCategories()
			)
		;

		df_assert_boolean ($needBeChecked);


        if ($needBeChecked) {
            $result['checked'] = true;
        }


        if ($isParent || $needBeChecked) {
            $result['expanded'] = true;
        }


		df_result_array ($result);

        return $result;
    }






	/**
	 * @return int[]
	 */
	private function getSelectedCategories () {

		if (!isset ($this->_selectedCategories)) {

			/** @var int[] $result  */
			$result =
					!$this->getRole()->isModuleEnabled()
				?
					array ()
				:
					$this->getRole()->getCategoryIds()
			;


			df_assert_array ($result);

			$this->_selectedCategories = $result;

		}


		df_result_array ($this->_selectedCategories);

		return $this->_selectedCategories;

	}


	/**
	* @var int[]
	*/
	private $_selectedCategories;

	


	
	
	/**
	 * @return Df_AccessControl_Model_Role
	 */
	private function getRole () {
	
		if (!isset ($this->_role)) {
	
			/** @var Df_AccessControl_Model_Role $result  */
			$result =
				df_model (
					Df_AccessControl_Model_Role::getNameInMagentoFormat()
				)
			;
	
			df_assert ($result instanceof Df_AccessControl_Model_Role);


			$result->load ($this->getRoleId());


			df_assert ($result instanceof Df_AccessControl_Model_Role);
	
			$this->_role = $result;
	
		}
	
	
		df_assert ($this->_role instanceof Df_AccessControl_Model_Role);
	
		return $this->_role;
	
	}
	
	
	/**
	* @var Df_AccessControl_Model_Role
	*/
	private $_role;	
	





	/**
	 * @return int|null
	 */
	private function getRoleId () {

		/** @var int|null $result  */
		$result =
			df_request ('rid')
		;


		if (!is_null($result)) {
			df_result_integer ($result);
		}


		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_AccessControl_Block_Admin_Tab_Tree';
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




	//const TEMPLATE = 'catalog/product/edit/categories.phtml';
	const TEMPLATE = 'df/access_control/tab/tree.phtml';

}


