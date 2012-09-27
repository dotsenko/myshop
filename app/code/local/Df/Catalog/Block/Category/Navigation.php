<?php


class Df_Catalog_Block_Category_Navigation extends Df_Core_Block_Template {


	
	/**
	 * @return
	 *			Mage_Catalog_Model_Resource_Category_Collection
	 * 		|	Mage_Catalog_Model_Resource_Category_Flat_Collection
	 *		|	Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	 * 		|	Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Flat_Collection
	 *		|	array
	 */
	public function getItems () {
	
		if (!isset ($this->_items)) {

			/**
			 * @var
			 *			Mage_Catalog_Model_Resource_Category_Collection
			 * 		|	Mage_Catalog_Model_Resource_Category_Flat_Collection
			 *		|	Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
			 * 		|	Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Flat_Collection
			 *		|	array
			 */
			$result = array ();

			if ($this->hasItems()) {

				/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Category_Flat_Collection $result  */
				$result = $this->getCurrentCategory()->getCollection();

				df_helper()->catalog()->assert()->categoryCollection ($result);

				$result->addAttributeToSelect ('*');
				$result->addAttributeToFilter('is_active', 1);
				$result->addIdFilter($this->getCurrentCategory()->getChildren());
				$result->setOrder('position', 'ASC');

				if (method_exists ($result, 'joinUrlRewrite')) {
					$result->joinUrlRewrite();
				}

			}
	
	
			if (!is_array ($result)) {
				df_helper()->catalog()->assert()->categoryCollection ($result);
			}
	
			$this->_items = $result;
	
		}
	
	
		if (!is_array ($this->_items)) {
			df_helper()->catalog()->assert()->categoryCollection ($this->_items);
		}
	
		return $this->_items;
	
	}
	
	
	/**
	 * @var
	 *			Mage_Catalog_Model_Resource_Category_Collection
	 * 		|	Mage_Catalog_Model_Resource_Category_Flat_Collection
	 *		|	Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	 * 		|	Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Flat_Collection
	 *		|	array
	 */
	private $_items;	




	/**
	 * @override
	 * @return Mage_Core_Block_Abstract
	 */
	protected function _prepareLayout() {
		if (df_cfg()->catalog()->navigation()->getEnabled()) {

			/**
			 * Добавление *.css и *.js именно здесь, а не через файлы layout,
			 * даёт нам большую гибкость: нам не нужно думать, какие handle обрабатывать
			 */
			$this->addClientResourcesToThePage ();
		}
		parent::_prepareLayout();
	}




	/**
	 * @override
	 * @return string|null
	 */
	protected function getDefaultTemplate () {
		return self::DF_TEMPLATE;
	}




	/**
	 * @override
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result =
				parent::needToShow()
			&&
				df_enabled (Df_Core_Feature::TWEAKS)
			&&
				df_cfg()->catalog()->navigation()->getEnabled()
			&&
				$this->hasItems()
		;

		df_result_boolean ($result);

		return $result;

	}


	
	
	
	/**
	 * @return Mage_Catalog_Model_Category
	 */
	private function getCurrentCategory () {
	
		if (!isset ($this->_currentCategory)) {
	
			/** @var Mage_Catalog_Model_Category $result  */
			$result = $this->cfg (self::PARAM__CATEGORY);

			if (is_null ($result)) {

				$result = Mage::registry ('current_category');

				if (is_null ($result)) {

					$result = $this->getStoreRootCategory ();

				}
			}
	
	
			df_assert ($result instanceof Mage_Catalog_Model_Category);
	
			$this->_currentCategory = $result;
	
		}
	
	
		df_assert ($this->_currentCategory instanceof Mage_Catalog_Model_Category);
	
		return $this->_currentCategory;
	
	}
	
	
	/**
	* @var Mage_Catalog_Model_Category
	*/
	private $_currentCategory;	
	
	



	/**
	 * @return Df_Catalog_Block_Category_Navigation
	 */
	private function addClientResourcesToThePage () {

		if (!is_null ($this->getBlockHead ())) {
			/** Блок может отсутствовать на некоторых страницах. Например, при импорте товаров. */
			$this
				->getBlockHead()
					->addCss ('df/common/reset.css')
					->addCss ('df/catalog/category/navigation.css')
			;
		}
		return $this;
	}



	
	
	
	/**
	 * @return Mage_Catalog_Model_Category
	 */
	private function getStoreRootCategory () {
	
		if (!isset ($this->_storeRootCategory)) {
	
			/** @var Mage_Catalog_Model_Category $result  */
			$result = 
				df_model (
					Df_Catalog_Const::CATEGORY_CLASS_MF
				)
			;
	
			df_assert ($result instanceof Mage_Catalog_Model_Category);


			$result
				->load (
					Mage::app()->getStore()->getRootCategoryId()
				)
			;

	
			$this->_storeRootCategory = $result;
	
		}
	
	
		df_assert ($this->_storeRootCategory instanceof Mage_Catalog_Model_Category);
	
		return $this->_storeRootCategory;
	
	}
	
	
	/**
	* @var Mage_Catalog_Model_Category
	*/
	private $_storeRootCategory;	




	/**
	 * @return bool
	 */
	private function hasItems () {

		if (!isset ($this->_hasItems)) {

			/** @var bool $result  */
			$result =
					$this->getCurrentCategory()
				&&
					$this->getCurrentCategory()->hasChildren()
			;


			df_assert_boolean ($result);

			$this->_hasItems = $result;

		}


		df_result_boolean ($this->_hasItems);

		return $this->_hasItems;

	}


	/**
	* @var bool
	*/
	private $_hasItems;




	const DF_TEMPLATE = 'df/catalog/category/navigation.phtml';

	const PARAM__CATEGORY = 'category';
}

