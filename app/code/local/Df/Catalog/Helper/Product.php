<?php

class Df_Catalog_Helper_Product extends Mage_Catalog_Helper_Data {

	/**
	 * @param int $attributeSetId
	 * @param string $groupName
	 * @param int|null $sortOrder [optional]
	 * @return Df_Catalog_Helper_Product
	 */
	public function addGroupToAttributeSetIfNeeded ($attributeSetId, $groupName, $sortOrder = null) {

		df_param_integer ($attributeSetId, 0);
		df_param_between ($attributeSetId, 0, 1);
		df_param_string ($groupName, 1);

		if (!isset ($this->_mapFromSetsToGroups [$attributeSetId])) {
			$this->_mapFromSetsToGroups [$attributeSetId] = array ();
		}

		if (!isset ($this->_mapFromSetsToGroups [$attributeSetId][$groupName])) {

			df_helper()->catalog()->getSetup()
				->addAttributeGroup (
					df_helper()->catalog()->eav()->getProductEntity()->getTypeId()
					,
					$attributeSetId
					,
					$groupName
					,
					$sortOrder
				)
			;

			$this->_mapFromSetsToGroups [$attributeSetId][$groupName] = true;

			df_helper()->_1c()
				->log (
					sprintf (
						'Добавили к прикладному типу товаров №%d группу свойств «%s»'
						,
						$attributeSetId
						,
						$groupName
					)
				)
			;
		}


		return $this;
	}


	/**
	 * @var array
	 */
	private $_mapFromSetsToGroups = array ();

	
	

	/**
	 * @return Mage_Eav_Model_Entity_Attribute_Set
	 */
	public function getDefaultAttributeSet () {
	
		if (!isset ($this->_defaultAttributeSet)) {
	
			/** @var Mage_Eav_Model_Entity_Attribute_Set $result  */
			$result = 
				df_model (
					Df_Eav_Const::CLASS_MF__ENTITY_ATTRIBUTE_SET
				)
			;
	
			df_assert ($result instanceof Mage_Eav_Model_Entity_Attribute_Set);


			$result
				->load (
					$this->getResource()->getEntityType()->getDefaultAttributeSetId()
				)
			;

			$this->_defaultAttributeSet = $result;
	
		}
	
	
		df_assert ($this->_defaultAttributeSet instanceof Mage_Eav_Model_Entity_Attribute_Set);
	
		return $this->_defaultAttributeSet;
	
	}
	
	
	/**
	* @var Mage_Eav_Model_Entity_Attribute_Set
	*/
	private $_defaultAttributeSet;	
	
	
	

	
	/**
	 * @param string $sku
	 * @return int|null
	 */
	public function getIdBySku ($sku) {

		df_param_string ($sku, 0);

		/** @var int|null|bool $result  */
		$result =
			$this->getResource()->getIdBySku ($sku)
		;


		if (false === $result) {
			$result = null;
		}


		if (!is_null ($result)) {
			df_result_integer ($result);
		}

		return $result;

	}
	
	
	
	
	/**
	 * @return Mage_Catalog_Model_Resource_Product|Mage_Catalog_Model_Resource_Eav_Mysql4_Product
	 */
	public function getResource () {
	
		if (!isset ($this->_resource)) {
	
			/** @var Mage_Catalog_Model_Resource_Product|Mage_Catalog_Model_Resource_Eav_Mysql4_Product $result  */
			$result =
				Mage::getResourceModel ('catalog/product')
			;
	
	
			df_helper()->catalog()->assert()->productResource ($result);
	
			$this->_resource = $result;
	
		}
	

		df_helper()->catalog()->assert()->productResource ($this->_resource);
	
		return $this->_resource;
	
	}
	
	
	/**
	* @var Mage_Catalog_Model_Resource_Product|Mage_Catalog_Model_Resource_Eav_Mysql4_Product
	*/
	private $_resource;	
	
	


	/**
	 * @return Df_Catalog_Model_Product
	 */
	public function getSingleton () {
	
		if (!isset ($this->_singleton)) {
	
			/** @var Df_Catalog_Model_Product $result  */
			$result =
				df_model (
					Df_Catalog_Const::DF_PRODUCT_CLASS_MF
				)
			;
	
	
			df_assert ($result instanceof Df_Catalog_Model_Product);
	
			$this->_singleton = $result;
	
		}
	
	
		df_assert ($this->_singleton instanceof Df_Catalog_Model_Product);
	
		return $this->_singleton;
	
	}
	
	
	/**
	* @var Df_Catalog_Model_Product
	*/
	private $_singleton;	

	
	
	
	/**
	 * @return int
	 */
	public function getTypeId () {
	
		if (!isset ($this->_typeId)) {
	
			/** @var int $result  */
			$result =
				$this->getSingleton()->getTypeId()
			;
	
			df_assert_integer ($result);
	
			$this->_typeId = $result;
	
		}
	
	
		df_result_integer ($this->_typeId);
	
		return $this->_typeId;
	
	}
	
	
	/**
	* @var int
	*/
	private $_typeId;




	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @return Mage_Catalog_Model_Product
	 * @throws Exception
	 */
	public function reload (Mage_Catalog_Model_Product $product) {

		/** @var int $storeId */
		$storeId = $product->getStoreId();

		df_assert_integer ($storeId);


		$product->cleanCache();


		/** @var string $class  */
		$class = get_class ($product);

		/** @var Mage_Catalog_Model_Product $result  */
		$result = new $class ();

		df_assert ($result instanceof Mage_Catalog_Model_Product);


		$result->setDataUsingMethod ('store_id', $storeId);

		$result->load ($product->getId());

		df_assert_between (intval ($result->getId()), 1);

		return $result;
	}





	/**
	 * @param Mage_Catalog_Model_Product $product      
	 * @param bool $isMassUpdate [optional]
	 * @return Df_Catalog_Helper_Product
	 * @throws Exception
	 */
	public function save (Mage_Catalog_Model_Product $product, $isMassUpdate = false) {

		$product
			->setDataUsingMethod ('is_massupdate', $isMassUpdate)
			->setDataUsingMethod ('exclude_url_rewrite', $isMassUpdate)
		;

		/** @var Mage_Core_Model_Store $currentStore */
		$currentStore = Mage::app()->getStore();

		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

		try {
			$product->save ();
		}
		catch (Exception $e) {

			Mage::app()->setCurrentStore($currentStore);

			throw $e;
		}

		Mage::app()->setCurrentStore($currentStore);

		return $this;

	}




	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @param array $attributeValues
	 * @param int $storeId [optional]
	 * @return Df_Catalog_Helper_Product
	 * @throws Exception
	 */
	public function saveAttributes (
		Mage_Catalog_Model_Product $product
		,
		array $attributeValues
		,
		$storeId = null
	) {

		df_assert_between ($product->getId(), 1);

		/** @var Mage_Core_Model_Store $currentStore */
		$currentStore = Mage::app()->getStore();

		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

		try {

			/** @var Mage_Catalog_Model_Product_Action $productAction */
			$productAction = Mage::getSingleton('catalog/product_action');

			df_assert ($productAction instanceof Mage_Catalog_Model_Product_Action);

			if (is_null ($storeId)) {
				$storeId = Mage_Core_Model_App::ADMIN_STORE_ID;
			}

			$productAction
				->updateAttributes (
					array (
						$product->getId()
					)
					,
					$attributeValues
					,
					$storeId
				)
			;

		}
		catch (Exception $e) {

			Mage::app()->setCurrentStore($currentStore);

			throw $e;
		}

		Mage::app()->setCurrentStore($currentStore);

		return $this;

	}


	



	/**
	 * @return Df_Catalog_Helper_Product_Url
	 */
	public function url () {

		/** @var Df_Catalog_Helper_Product_Url $result  */
		$result =
			Mage::helper (Df_Catalog_Helper_Product_Url::getNameInMagentoFormat())
		;


		df_assert ($result instanceof Df_Catalog_Helper_Product_Url);

		return $result;

	}

	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Product';
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

