<?php


class Df_Catalog_Model_Resource_Product_Collection
	extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection {




	/**
	 * @param int[] $categoryIds
	 * @return Df_Catalog_Model_Resource_Product_Collection
	 */
	public function addCategoriesFilter (array $categoryIds) {

		df_param_array ($categoryIds, 0);

        $this
			->_productLimitationFilters
					[self::LIMITATION_FILTER__CATEGORIES]
				=
					$categoryIds
		;


        if ($this->getStoreId() == Mage_Core_Model_App::ADMIN_STORE_ID) {
            $this->_applyZeroStoreProductLimitations();
        } else {
            $this->_applyProductLimitations();
        }

        return $this;
	}







    /**
     * Apply limitation filters to collection base on API
     *
     * Method allows using one time category product table
     * for combinations of category_id filter states
     *
     * @return Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    protected function _applyZeroStoreProductLimitations() {

		/** @var array $filters  */
        $filters = $this->_productLimitationFilters;

		df_assert_array ($filters);



		/** @var array $conditions  */
		$conditions =
			df_clean (
				array (
					'cat_pro.product_id=e.entity_id'
					,

						!$this->hasCategoriesFilter ()
					?
						$this->getConnection()->quoteInto(
							'cat_pro.category_id=?'
							,
							$filters['category_id']
						)
					:
						(
								(1 > count($this->getCategoriesFilter()))
							?
								null
							:
								sprintf (
									'cat_pro.category_id IN (%s)'
									,
									implode (
										Df_Core_Const::T_COMMA
										,
										$this->getCategoriesFilter()
									)
								)
						)
				)
			)
		;

		df_assert_array ($conditions);



		/** @var string $joinCond  */
        $joinCond = implode (' AND ', $conditions);

		df_assert_string ($joinCond);



		/** @var array $fromPart */
        $fromPart = $this->getSelect()->getPart (Zend_Db_Select::FROM);

		df_assert_array ($fromPart);



		/** @var array|null $catPro */
		$catPro = df_a ($fromPart, 'cat_pro');

		if (!is_null ($catPro)) {
			df_assert_array ($catPro);
		}



		/**
		 * При выборке товаров сразу по нескольким товарным разделам
		 * надо использовать DISTINCT,
		 * иначе при создании коллекции произойдёт исключительная ситуация.
		 */
		if ($this->hasCategoriesFilter ()) {
			$this->getSelect()->distinct (true);
		}




        if (!is_null ($catPro)) {
            $fromPart['cat_pro']['joinCondition'] = $joinCond;
            $this->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
        }
        else {
            $this->getSelect()
				->join (
					array (
						  'cat_pro' => $this->getTable('catalog/category_product')
					)
					,
					$joinCond

					/**
					 * Обратите внимание, что синтаксис array () указывает на то,
					 * что система не должна выбирать данные из связанной таблицы.
					 *
					 * Если при выборке по нескольким товарным разделам
					 * система бы выбирала ещё и данные по связанной таблице,
					 * то, при наличии одного товара сразу в нескольких товарных разделах
					 * DISTINCT бы не сработал,
					 * и система выбрала бы один и тот же товар несколько раз,
					 * что при добавлении товаров в коллекцию привело бы к исключительной ситуации.
					 *
					 * Пример на основе демо данных:
					 *
					 * [code]
						SELECT DISTINCT  `e` . * ,  `cat_pro`.`position` AS  `cat_index_position`
						FROM  `catalog_product_entity` AS  `e`
						INNER JOIN  `catalog_category_product` AS  `cat_pro`
						ON cat_pro.product_id = e.entity_id
						AND cat_pro.category_id
						IN ( 3, 13, 8, 15, 27, 28 )
						LIMIT 0 , 30
					 * [/code]
					 *
					 * В этом примере DISTINCT работает не по идентификаторам товаров,
					 * а по комбинации идентификатора товара и поля `position` связанной таблицы,
					 * что приводит к выборке одного и того же товара несколько раз подряд.
					 */
					,
						$this->hasCategoriesFilter ()
					?
						array ()
					:
						array('cat_pro' => 'position')
				)
			;
        }

        return $this;
    }







    /**
     * Apply limitation filters to collection
     *
     * Method allows using one time category product index table (or product website table)
     * for different combinations of store_id/category_id/visibility filter states
     *
     * Method supports multiple changes in one collection object for this parameters
     *
     * @return Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
    protected function _applyProductLimitations()
    {
        $this->_prepareProductLimitationFilters();
        $this->_productLimitationJoinWebsite();
        $this->_productLimitationJoinPrice();
        $filters = $this->_productLimitationFilters;

        if (
				isset($filters['category_id'])
			||
				isset($filters['visibility'])
			||
				$this->hasCategoriesFilter()
		) {

			/** @var array $conditions  */
			$conditions =
				array (
					'cat_index.product_id=e.entity_id'
					,
					$this->getConnection()->quoteInto (
						'cat_index.store_id=?'
						,
						$filters['store_id']
					)
				)
			;

			df_assert_array ($conditions);



			if (
					isset ($filters['visibility'])
				&&
					!isset($filters['store_table'])
			) {
				$conditions[] =
					$this->getConnection()
						->quoteInto (
							'cat_index.visibility IN(?)'
							,
							$filters['visibility']
						)
				;
			}



			$conditions[] =
					!$this->hasCategoriesFilter ()
				?
					$this->getConnection()->quoteInto(
						'cat_index.category_id=?'
						,
						$filters['category_id']
					)
				:
					(
							(1 > count ($this->getCategoriesFilter()))
						?
							null
						:
							sprintf (
								'cat_index.category_id IN (%s)'
								,
								implode (
									Df_Core_Const::T_COMMA
									,
									$this->getCategoriesFilter()
								)
							)
					)
			;


			$conditions = df_clean ($conditions);



			if (isset($filters['category_is_anchor'])) {
				$conditions[] =
					$this->getConnection()
						->quoteInto(
							'cat_index.is_parent=?'
							,
							$filters['category_is_anchor']
						)
				;
			}



			/** @var string $joinCond  */
			$joinCond = implode (' AND ', $conditions);

			df_assert_string ($joinCond);



			/** @var array $fromPart  */
			$fromPart =
				$this->getSelect()->getPart (
					Zend_Db_Select::FROM
				)
			;

			df_assert_array ($fromPart);



			/**
			 * При выборке товаров сразу по нескольким товарным разделам
			 * надо использовать DISTINCT,
			 * иначе при создании коллекции произойдёт исключительная ситуация.
			 */
			if ($this->hasCategoriesFilter ()) {
				$this->getSelect()->distinct (true);
			}



			if (isset ($fromPart['cat_index'])) {
				$fromPart['cat_index']['joinCondition'] = $joinCond;
				$this->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
			}
			else {
				$this->getSelect()
					->join (
						array ('cat_index' => $this->getTable('catalog/category_product_index'))
						,
						$joinCond

						/**
						 * Обратите внимание, что синтаксис array () указывает на то,
						 * что система не должна выбирать данные из связанной таблицы.
						 *
						 * Если при выборке по нескольким товарным разделам
						 * система бы выбирала ещё и данные по связанной таблице,
						 * то, при наличии одного товара сразу в нескольких товарных разделах
						 * DISTINCT бы не сработал,
						 * и система выбрала бы один и тот же товар несколько раз,
						 * что при добавлении товаров в коллекцию привело бы к исключительной ситуации.
						 *
						 * Пример на основе демо данных:
						 *
						 * [code]
						    SELECT DISTINCT  `e` . * ,  `cat_pro`.`position` AS  `cat_index_position`
							FROM  `catalog_product_entity` AS  `e`
							INNER JOIN  `catalog_category_product` AS  `cat_pro`
						 	ON cat_pro.product_id = e.entity_id
							AND cat_pro.category_id
							IN ( 3, 13, 8, 15, 27, 28 )
							LIMIT 0 , 30
						 * [/code]
						 *
						 * В этом примере DISTINCT работает не по идентификаторам товаров,
						 * а по комбинации идентификатора товара и поля `position` связанной таблицы,
						 * что приводит к выборке одного и того же товара несколько раз подряд.
						 */
						,
							$this->hasCategoriesFilter ()
						?
							array ()
						:
							array('cat_index_position' => 'position')
					)
				;
			}

			$this->_productLimitationJoinStore();



			Mage::dispatchEvent('catalog_product_collection_apply_limitations_after', array(
				'collection'    => $this
			));

        }


        return $this;
    }





	/**
	 * @return array|null
	 */
	private function getCategoriesFilter () {

		/** @var string $result  */
		$result =
			df_a (
				$this->_productLimitationFilters
				,
				self::LIMITATION_FILTER__CATEGORIES
			)
		;

		if (!is_null ($result)) {
			df_result_array ($result);
		}

		return $result;

	}






	/**
	 * @return bool
	 */
	private function hasCategoriesFilter () {

		/** @var bool $result  */
		$result =
			!is_null (
				$this->getCategoriesFilter ()
			)
		;

		df_result_boolean ($result);

		return $result;

	}





	const LIMITATION_FILTER__CATEGORIES = 'df_categories';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Resource_Product_Collection';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			self::CLASS_NAME_MF
		;
	}


	const CLASS_NAME_MF = 'df_catalog/product_collection';


}


