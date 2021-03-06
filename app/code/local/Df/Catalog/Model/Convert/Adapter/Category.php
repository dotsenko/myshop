<?php


/**
 * @deprecated
 */
class Df_Catalog_Model_Convert_Adapter_Category extends Mage_Eav_Model_Convert_Adapter_Entity {

	/**
	 * @var array
	 */
	protected $_categoryCache = array();

	/**
	 * @var array
	 */
	protected $_stores;

	/**
	 * Category display modes
	 * @var array
	 */
	protected $_displayModes =
		array (
			Mage_Catalog_Model_Category::DM_PRODUCT
			,
			Mage_Catalog_Model_Category::DM_PAGE
			,
			Mage_Catalog_Model_Category::DM_MIXED
		)
	;


	/**
	 * @return void
	 */
	public function parse()
	{
		$batchModel = Mage::getSingleton('dataflow/batch');

		$batchImportModel = $batchModel->getBatchImportModel();
		$importIds = $batchImportModel->getIdCollection();

		foreach ($importIds as $importId) {
			//print '<pre>'.memory_get_usage().'</pre>';
			$batchImportModel->load($importId);
			$importData = $batchImportModel->getBatchData();

			$this->saveRow($importData);
		}
	}

	/**
	 * Save category (import)
	 *
	 * @param array $importData
	 * @throws Mage_Core_Exception
	 * @return bool
	 */
	public function saveRow(array $importData)
	{
		$store = false;
		if (empty($importData['store'])) {
			if (!is_null($this->getBatchParams('store'))) {
				$store = Mage::app()->getStore($this->getBatchParams('store'));
			} else {
				$message = df_mage()->catalogHelper()->__('Skip import row, required field "%s" not defined', 'store');
				Mage::throwException($message);
			}
		} else {
			$store = $this->getStoreByCode($importData['store']);
		}


		if ($store === false) {
			$message = df_mage()->catalogHelper()->__('Skip import row, store "%s" field not exists', $importData['store']);
			Mage::throwException($message);
		}

		$rootId = $store->getRootCategoryId();
		if (!$rootId) {
			return array();
		}
		$rootPath = '1/'.$rootId;
		if (empty($this->_categoryCache[$store->getId()])) {
			$collection = df_model('catalog/category')->getCollection()
					->setStore($store)
					->addAttributeToSelect('name');
			$collection->getSelect()->where("path like '".$rootPath."/%'");

			foreach ($collection as $cat) {
				$pathArr = explode('/', $cat->getPath());
				$namePath = '';
				for ($i=2, $l=sizeof($pathArr); $i<$l; $i++) {
					$name = $collection->getItemById($pathArr[$i])->getName();
					$namePath .= (empty($namePath) ? '' : '/').trim($name);
				}
				$cat->setNamePath($namePath);
			}

			$cache = array();
			foreach ($collection as $cat) {
				$cache[strtolower($cat->getNamePath())] = $cat;
				$cat->unsNamePath();
			}
			$this->_categoryCache[$store->getId()] = $cache;
		}
		$cache =& $this->_categoryCache[$store->getId()];

		$importData['categories'] = preg_replace('#\s*/\s*#', '/', trim($importData['categories']));
		if (!empty($cache[$importData['categories']])) {
			return true;
		}

		$path = $rootPath;
		$namePath = '';

		$i = 1;
		$categories = explode('/', $importData['categories']);
		foreach ($categories as $catName) {
			$namePath .= (empty($namePath) ? '' : '/').strtolower($catName);
			if (empty($cache[$namePath])) {

				$dispMode = $this->_displayModes[2];

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
				 * @var Mage_Catalog_Model_Category $cat
				 */
				$cat =
					df_helper()->catalog()->category()->createAndSave (
						array (
							Df_Catalog_Const::CATEGORY_PARAM_PATH => $path
							,
							Df_Catalog_Const::CATEGORY_PARAM_NAME => $catName
							,
							Df_Catalog_Const::CATEGORY_PARAM_IS_ACTIVE => 1
							,
							Df_Catalog_Const::CATEGORY_PARAM_IS_ANCHOR => 1
							,
							Df_Catalog_Const::CATEGORY_PARAM_DISPLAY_MODE => $dispMode
						)
						,
						$store->getId()
					)
				;

				df_assert ($cat instanceof Mage_Catalog_Model_Category);

				$cache[$namePath] = $cat;
			}
			$catId = $cache[$namePath]->getId();
			$path .= '/'.$catId;
			$i++;
		}

		return true;
	}

	/**
	 * Retrieve store object by code
	 *
	 * @param string $store
	 * @return Mage_Core_Model_Store
	 */
	public function getStoreByCode($store)
	{
		$this->_initStores();
		if (isset($this->_stores[$store])) {
			return $this->_stores[$store];
		}
		return false;
	}

	/**
	 *  Init stores
	 *
	 *  @param    none
	 *  @return      void
	 */
	protected function _initStores ()
	{
		if (is_null($this->_stores)) {
			$this->_stores = Mage::app()->getStores(true, true);
			foreach ($this->_stores as $code => $store) {
				$this->_storesIdCode[$store->getId()] = $code;
			}
		}
	}
}
