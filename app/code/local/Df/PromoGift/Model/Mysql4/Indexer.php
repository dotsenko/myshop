<?php

class Df_PromoGift_Model_Mysql4_Indexer extends Df_Core_Model_Mysql4_Abstract {



	/**
	 * Перестраивает таблицу подарков полностью
	 *
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	public function reindexAll () {

		try {

			$this
				/**
				 * Сначала полностью очищаем таблицу от старых данных
				 */
				->deleteAllGifts ()

				/**
				 * Затем записываем в таблицу новые данные
				 */
				->createGiftsForAllWebsites ()
			;

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e, true);
		}

		return $this;
	}



	/**
	 * @param Mage_Core_Model_Website $website
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	public function reindexWebsite (Mage_Core_Model_Website $website) {

		$this
			->deleteGiftsForWebsite ($website)
			->createGiftsForWebsite ($website)
		;

		return $this;
	}




	/**
	 * @param Mage_SalesRule_Model_Rule $rule
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	public function reindexRule (Mage_SalesRule_Model_Rule $rule) {

		$this
			->deleteGiftsForRule ($rule)
			->createGiftsForRule ($rule)
		;

		return $this;
	}




	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	public function reindexProduct (Mage_Catalog_Model_Product $product) {

		$this
			->deleteGiftsForProduct ($product)
			->createGiftsForProduct ($product)
		;

		return $this;
	}





	/**
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	private function createGiftsForAllWebsites () {

		foreach ($this->getWebsites () as $website) {
			/** @var Mage_Core_Model_Website $website */

			$this->createGiftsForWebsite ($website);
		}

		return $this;
	}




	/**
	 * @param Mage_Core_Model_Website $website
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	private function deleteGiftsForWebsite (Mage_Core_Model_Website $website) {

		$this->_getWriteAdapter()
			->delete (
				$this->getMainTable()
				,
				$this->_getReadAdapter()->quoteInto('website_id = ?', $website->getId())
			)
		;

		return $this;
	}




	/**
	 * @param Mage_SalesRule_Model_Rule $rule
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	private function deleteGiftsForRule (Mage_SalesRule_Model_Rule $rule) {

		$this->_getWriteAdapter()
			->delete (
				$this->getMainTable()
				,
				$this->_getReadAdapter()->quoteInto('rule_id = ?', $rule->getId())
			)
		;

		return $this;
	}




	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	private function deleteGiftsForProduct (Mage_Catalog_Model_Product $product) {

		$this->_getWriteAdapter()
			->delete (
				$this->getMainTable()
				,
				$this->_getReadAdapter()->quoteInto('product_id = ?', $product->getId())
			)
		;

		return $this;
	}




	/**
	 * @param Mage_Core_Model_Website $website
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	private function createGiftsForWebsite (Mage_Core_Model_Website $website) {

		/**
		 * @todo: С фильтрами код будет компактнее.
		 */

		$products = $this->getProductsByWebsite ($website);
		/** @var array $products */


		$rules = $this->getPromoGiftingRulesByWebsite ($website);
		/** @var Mage_SalesRule_Model_Mysql4_Rule_Collection $rules */

		foreach ($rules as $rule) {
			/** @var Mage_SalesRule_Model_Rule $rule */

			df_assert ($rule instanceof Mage_SalesRule_Model_Rule);


			/**
			 * Распаковываем содержимое свойств conditions и actions,
			 * которое в БД хранится в запакованном (serialized) виде
			 */
			$rule->afterLoad ();

			foreach ($products as $product) {
				/** @var Mage_Catalog_Model_Product $product */

				df_assert ($product instanceof Mage_Catalog_Model_Product);

				if ($this->isProductEligibleForRule ($product, $rule)) {
					$this->makeIndexEntry ($website, $rule, $product);
				}
			}
		}

		return $this;
	}




	/**
	 * @param Mage_SalesRule_Model_Rule $rule
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	private function createGiftsForRule (Mage_SalesRule_Model_Rule $rule) {

		/**
		 * @todo: С фильтрами код будет компактнее.
		 */

		/**
		 * Отбраковываем отключенные правила
		 */
		if ($rule [Df_SalesRule_Const::DB__SALESRULE__IS_ACTIVE]) {

			/**
			 * Отбраковываем истёкшие правила
			 */
			$isValid = true;


			$toDateAsDbExpr = $rule [Df_SalesRule_Const::DB__SALESRULE__TO_DATE];
			/** @var Zend_Db_Expr $toDateAsDbExpr */


			$toDateAsString = (string)$toDateAsDbExpr;
			/** @var string $toDateAsString */


			if (!df_helper ()->zf()->db()->isNull ($toDateAsString)) {

				/** @var Zend_Date|null $toDate */
				$toDate = df_parse_mysql_datetime ($toDateAsString);

				$isValid =
						!is_null ($toDate)
					&&
						df_is_date_in_future ($toDate)
				;
			}


			if ($isValid) {

				foreach ($this->getWebsites () as $website) {
					/** @var Mage_Core_Model_Website $website */

					$products = $this->getProductsByWebsite ($website);
					/** @var array $products */


					/**
					 * Распаковываем содержимое свойств conditions и actions,
					 * которое в БД хранится в запакованном (serialized) виде
					 */
					$rule->afterLoad ();

					foreach ($products as $product) {
						/** @var Mage_Catalog_Model_Product $product */

						df_assert ($product instanceof Mage_Catalog_Model_Product);

						if ($this->isProductEligibleForRule ($product, $rule)) {
							$this->makeIndexEntry ($website, $rule, $product);
						}
					}
				}
			}

		}


		return $this;
	}




	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	private function createGiftsForProduct (Mage_Catalog_Model_Product $product) {

		/**
		 * Надо понимать, что не любой присланный сюда товар подходит в качестве подарка
		 * Нам надо подвергнуть присланный сюда товар тем же проверкам,
		 * что содержатся в методе Df_PromoGift_Model_Mysql4_Indexer::getProductsByWebsite()
		 */

		if (Mage_Catalog_Model_Product_Status::STATUS_ENABLED == $product->getStatus()) {

			$eligible= true;
			/** @var bool $eligible */

			if (Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE == $product->getTypeId()) {

				if (
					is_null (
						$this->getDependentProducts()->getItemById (
							$product->getId ()
						)
					)
				) {
					/**
					 * Отбраковываем товар, если он является частью другого и не видим самостоятельно
					 */
					$eligible = false;
				}
			}


			if ($eligible) {

				$stockItem = Mage::getModel('cataloginventory/stock_item');
				/** @var Mage_CatalogInventory_Model_Stock_Item $stockItem */

				$stockItem->loadByProduct($product);



				if ($stockItem->getManageStock ()) {

					if (!$stockItem->getIsInStock ()) {
						$eligible = false;
					}
					else {
						if ($stockItem->getQty () < $stockItem->getMinQty ()) {
							$eligible = false;
						}
					}
				}
			}



			if ($eligible) {

				foreach ($this->getWebsites () as $website) {
					/** @var Mage_Core_Model_Website $website */

					/**
					 * Нам нужно отсеять сайты, к которым товар не относится
					 */
					if (in_array ($website->getId (), $product->getWebsiteIds())) {

						$rules = $this->getPromoGiftingRulesByWebsite ($website);
						/** @var Mage_SalesRule_Model_Mysql4_Rule_Collection $rules */

						foreach ($rules as $rule) {
							/** @var Mage_SalesRule_Model_Rule $rule */

							df_assert ($rule instanceof Mage_SalesRule_Model_Rule);


							/**
							 * Распаковываем содержимое свойств conditions и actions,
							 * которое в БД хранится в запакованном (serialized) виде
							 */
							$rule->afterLoad ();

							if ($this->isProductEligibleForRule ($product, $rule)) {
								$this->makeIndexEntry ($website, $rule, $product);
							}
						}
					}

				}

			}

		}


		return $this;
	}




	/**
	 * @return Df_Varien_Data_Collection
	 */
	private function getDependentProducts () {

		if (!isset ($this->_dependentProducts)) {

			$products = Mage::getModel (Df_Catalog_Const::PRODUCT_CLASS_MF)->getCollection();
			/** @var Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $products */

			df_helper()->catalog()->assert()->productCollection ($products);


			$products->addAttributeToSelect ("*");


			/**
			 * Нам надо отбраковать товары,
			 * которые являются составными элементами настраиваемого товара.
			 */
			$dependentProductRemover =
				df_model (
					Df_Catalog_Model_Filter_Product_Collection_DependentProductRemover
						::getNameInMagentoFormat()
				)
			;
			/** @var Df_Catalog_Model_Filter_Product_Collection_DependentProductRemover $dependentProductRemover */


			$filter = $dependentProductRemover->getRejectingFilter ();
			/** @var Zend_Filter_Interface $filter */


			$result = $filter->filter ($products);
			/** @var Df_Varien_Data_Collection $result */

			df_result_collection ($result, Df_Catalog_Const::PRODUCT_CLASS);
			df_assert ($result instanceof Df_Varien_Data_Collection);


			$this->_dependentProducts = $result;
		}

		return $this->_dependentProducts;
	}


	/**
	 * @var Df_Varien_Data_Collection
	 */
	private $_dependentProducts;



	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @param Mage_SalesRule_Model_Rule $rule
	 * @return bool
	 */
	private function isProductEligibleForRule (
		Mage_Catalog_Model_Product $product, Mage_SalesRule_Model_Rule $rule
	) {

		$actions = $rule->getActions();
		/** @var Mage_SalesRule_Model_Rule_Condition_Product_Combine $actions */


		$result =
			$actions->validate (
				$this->getQuoteItemMockForProduct ($product)
			)
		;


		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_boolean ($result);
		/*************************************/

		return $result;
	}





	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @return Mage_Core_Model_Abstract|Mage_Sales_Model_Quote_Item
	 */
	private function getQuoteItemMockForProduct (Mage_Catalog_Model_Product $product) {

		$result = Mage::getModel (Df_Sales_Const::QUOTE_ITEM_CLASS_MF);
		/** @var Mage_Sales_Model_Quote_Item $result */

		$result->setProduct ($product);

		/**
		 * При вызове Mage_Sales_Model_Quote_Item::setQty()
		 * срабатывает сигнал sales_quote_item_qty_set_after,
		 * который обрабатывается методом Mage_CatalogInventory_Model_Observer::checkQuoteItemQty ().
		 *
		 *
		 * Метод Mage_CatalogInventory_Model_Observer::checkQuoteItemQty ()
		 * в Magento 1.5.0.1 в нашей ситуации ничего не делает,
		 * потому что он прерывает выполнение, получив NULL
		 * при вызове Mage_Sales_Model_Quote_Item::getQuote().
		 *
		 *
		 * Однако в Magento 1.4.0.1 Mage_CatalogInventory_Model_Observer::checkQuoteItemQty ()
		 * не проверяет существование свойства quote, а сразу вызывает метод
		 * $quoteItem->getQuote()->getIsSuperMode(),
		 * что приводит к фатальной ошибке при отсутствии quote.
		 *
		 * Поэтому идём на хитрость, подсовывая методу Mage_CatalogInventory_Model_Observer::checkQuoteItemQty ()
		 * пустой объект quote c установленным в true значением is_super_mode
		 *
		 */

		/** @var Mage_Sales_Model_Quote $quote */
		$quote = df_model (Df_Sales_Const::QUOTE_CLASS_MF, array ('is_super_mode' => true));

		df_assert ($quote instanceof Mage_Sales_Model_Quote);

  		$result->setQuote ($quote);

		$result->setQty (1);

		df_assert ($result instanceof Mage_Sales_Model_Quote_Item);

		return $result;
	}





	/**
	 * @param Mage_Core_Model_Website $website
	 * @param Mage_SalesRule_Model_Rule $rule
	 * @param Mage_Catalog_Model_Product $product
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	private function makeIndexEntry (
		Mage_Core_Model_Website $website
		,
		Mage_SalesRule_Model_Rule $rule
		,
		Mage_Catalog_Model_Product $product
	) {

		$gift =
			df_model (
				Df_PromoGift_Model_Gift::getNameInMagentoFormat()
				,
				array (
					Df_PromoGift_Model_Gift::PARAM_PRODUCT => $product
					,
					Df_PromoGift_Model_Gift::PARAM_RULE => $rule
					,
					Df_PromoGift_Model_Gift::PARAM_WEBSITE => $website
				)
			)
		;
		/** @var Df_PromoGift_Model_Gift $gift */


		$gift->setDataChanges (true);
		$gift->save ();

		return $this;
	}





	/**
	 * @param Mage_Core_Model_Website $website
	 * @return array
	 */
	private function getProductsByWebsite (Mage_Core_Model_Website $website) {

		$result = Mage::getModel (Df_Catalog_Const::PRODUCT_CLASS_MF)->getCollection();
		/** @var Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $result */

		df_helper()->catalog()->assert()->productCollection ($result);


		$result->addAttributeToSelect ("*");


		/**
		 * Товары привязаны к сайту (website).
		 * Так что фильтр по store на уровне SQL всё равно транслируется в фильтр по website
		 */
		$result->addWebsiteFilter ($website->getId ());


		/**
		 * Мы не отбраковываем невидимые товары,
		 * потому что администратор может захотеть, чтобы товары-подарки были скрыты при просмотре витрины
		 * и отображались только при выполнении условий акции и только в специальном блоке для подарков,
		 * а не в общем каталоге.
		 *
		 * @todo Откроет ли Magento карточку товара, если товар скрыт?
		 *
		 */

		/**
		 * Отбраковываем отключенные товары
		 */
		$result->addAttributeToFilter ('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);


		/**
		 * А вот фильтр по наличию на складе нам бы не помешал!
		 * Или он и так по умолчанию включен?
		 */
		$stock = Mage::getSingleton('cataloginventory/stock');
		/** @var Mage_CatalogInventory_Model_Stock $stock */


		/**
		 * С типом аргумента всё в порядке
		 */
		$stock->addInStockFilterToCollection ($result);



		/**
		 * Нам надо отбраковать товары,
		 * которые являются составными элементами настраиваемого товара.
		 */
		$dependentProductRemover =
			df_model (
				Df_Catalog_Model_Filter_Product_Collection_DependentProductRemover
					::getNameInMagentoFormat()
			)
		;
		/** @var Df_Catalog_Model_Filter_Product_Collection_DependentProductRemover $dependentProductRemover */

		$result = $dependentProductRemover->filter ($result);


		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_collection ($result, Df_Catalog_Const::PRODUCT_CLASS);
		/*************************************/


		return $result;
	}




	/**
	 * Возвращает список подлежащих индексации правил.
	 *
	 * Обратите внимание, что не все эти правила
	 * являются действующими сейчас или будут действующими на момент покупки,
	 * потому что мы намеренно не отбраковали правила,
	 * недействующие сейчас, но запланированные на будущее
	 *
	 * @return Df_PromoGift_Model_Mysql4_Rule_Collection
	 */
	private function getPromoGiftingRulesByWebsite (Mage_Core_Model_Website $website) {


		/** @var Df_PromoGift_Model_Mysql4_Rule_Collection $result */
		$result =
			Mage::getResourceModel (
				Df_PromoGift_Model_Mysql4_Rule_Collection::CLASS_MF
			)
		;

		df_assert ($result instanceof Df_PromoGift_Model_Mysql4_Rule_Collection);




		/**
		 * Отбраковываем правила, не относящиеся к обрабатываемому сайту $website
		 */
		$result
			->addWebsiteFilter (
				array (
					  $website->getId ()
				)
			)
		;

		df_assert ($result instanceof Df_PromoGift_Model_Mysql4_Rule_Collection);

		return $result;
	}





	/**
	 * @return array
	 */
	private function getWebsites () {

		if (!isset ($this->_websites)) {

			$result =
				Mage::app()->getWebsites (
					false	// включать ли сайт с идентификатором 0 (административный)
					,
					false	// использовать ли коды сайтов в качестве ключей массива
				)
			;
			/** @var array $result */

			/*************************************
			 * Проверка результата работы метода
			 */
			df_result_collection ($result, 'Mage_Core_Model_Website');
			/*************************************/


			$this->_websites = $result;
		}
		return $this->_websites;
	}


	/**
	 * @var array
	 */
	private $_websites;




	/**
	 * @return Df_PromoGift_Model_Mysql4_Indexer
	 */
	private function deleteAllGifts () {
		$this->_getWriteAdapter()->truncate ($this->getMainTable());
		return $this;
	}



	/**
	 * @override
	 * @return void
	 */
    protected function _construct() {
		/**
		 * Нельзя вызывать parent::_construct(),
		 * потому что это метод в родительском классе — абстрактный.
		 */
        $this
			->_init (
				Df_PromoGift_Model_Mysql4_Gift::MAIN_TABLE
				,
				Df_PromoGift_Model_Mysql4_Gift::PRIMARY_KEY
			)
		;
    }





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Mysql4_Indexer';
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


