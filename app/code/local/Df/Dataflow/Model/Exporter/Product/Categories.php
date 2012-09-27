<?php


class Df_Dataflow_Model_Exporter_Product_Categories extends Df_Core_Model_Abstract {



	/**
	 * @return string
	 */
	public function process () {

		try {
			/** @var string $result  */
			$result =
				$this->encodeAsSimple (
					$this->getCategoriesInExportFormat ()
				)
			;

			df_result_string ($result);
		}
		catch (Exception $e) {
			df_handle_entry_point_exception ($e, true);
		}


		return $result;
	}





	/**
	 * @param  array $data
	 * @return string
	 */
	private function encodeAsSimple (array $data) {

		df_param_array ($data, 0);

		/** @var string $result  */
		$result =
			implode (
				"\r\n,"
				,
				array_map (
					array ($this, "encodePathAsSimple")
					,
					$data
				)
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @param  array $path
	 * @return string
	 */
	public function encodePathAsSimple (array $path) {

		df_param_array ($path, 0);

		/** @var string $result  */
		$result =
			implode (
				"/"
				,
				array_map (
					array ($this, "encodePathPartAsSimple")
					,
					$path
				)
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @param  string $pathPart
	 * @return string
	 */
	public function encodePathPartAsSimple ($pathPart) {

		df_param_string ($pathPart, 0);

		/** @var string $result  */
		$result =
			str_replace (
				','
				,
				'\,'
				,
				str_replace (
					"/"
					,
					'\/'
					,
					$pathPart
				)
			)
		;

		df_result_string ($result);

		return $result;
	}






	/**
	 * @param  mixed $data
	 * @return  string
	 */
	private function encodeAsJson ($data) {

		/** @var string $result  */
		$result =
			df_text()
				->adjustCyrillicInJson (
					Zend_Json::encode (
						$data
					)
				)
		;

		df_result_string ($result);

		return $result;
	}






	/**
	 * @return array
	 */
	private function getCategoriesInExportFormat () {

		/** @var array $result  */
		$result = array ();

		foreach ($this->getProduct ()->getCategoryCollection() as $category) {

			/** Mage_Catalog_Model_Category $category */
		    df_assert ($category instanceof Mage_Catalog_Model_Category);

			$result []=
				$this->getCategoryInExportFormat ($category)
			;
		}

		df_result_array ($result);

		return $result;
	}





	/**
	 * @param Mage_Catalog_Model_Category $category
	 * @return array
	 */
	private function getCategoryInExportFormat (Mage_Catalog_Model_Category $category) {

		df_assert ($category instanceof Mage_Catalog_Model_Category);

		/** @var array $result  */
		$result = array ();

		foreach ($this->getParentCategories ($category) as $ancestor) {

			/** @var Mage_Catalog_Model_Category $ancestor */
			df_assert ($ancestor instanceof Mage_Catalog_Model_Category);

			/** @var string $ancestorName  */
			$ancestorName = $ancestor->getName ();

			if (is_null ($ancestorName)) {
				/**
				 * В магазине мир-пряжи.рф встретился безымянный товарный раздел
				 */
				continue;
			}

			df_assert_string ($ancestorName);

			$result []=
				$ancestorName
			;
		}

		df_result_array ($result);

		return $result;
	}







	/**
	 * В отличие от стандартного метода Mage_Catalog_Model_Category::getParentCategories(),
	 * данный метод упорядочивает родительские разделы в соответствии с их иерархией.
	 *
	 * @param Mage_Catalog_Model_Category $category
	 * @return Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	 */
	private function getParentCategories (Mage_Catalog_Model_Category $category) {

		df_assert ($category instanceof Mage_Catalog_Model_Category);


		/** @var array $pathIds  */
        $pathIds = array_reverse(explode(',', $category->getPathInStore()));

		df_assert_array ($pathIds);


		/** @var Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection $result  */
        $result =
			Mage::getResourceModel(Df_Catalog_Const::CATEGORY_COLLECTION_CLASS_MF)
		;

		$result
			->setStore(Mage::app()->getStore())
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('url_key')
            ->addFieldToFilter('entity_id', array('in'=>$pathIds))
            ->addFieldToFilter('is_active', 1)
			->setOrder('level', 'asc')
		;


		df_helper()->catalog()->assert()->categoryCollection ($result);

		return $result;

	}





	/**
	 * @return Mage_Catalog_Model_Product
	 */
	private function getProduct () {

		/** @var Mage_Catalog_Model_Product $result */
		$result = $this->getData (self::PARAM_PRODUCT);

		df_assert ($result instanceof Mage_Catalog_Model_Product);

		return $result;
	}




	const PARAM_PRODUCT = 'product';
	const PARAM_PRODUCT_TYPE = 'Mage_Catalog_Model_Product';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM_PRODUCT, self::PARAM_PRODUCT_TYPE
			)
		;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Exporter_Product_Categories';
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