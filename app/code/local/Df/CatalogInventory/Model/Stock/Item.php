<?php

class Df_CatalogInventory_Model_Stock_Item extends Mage_CatalogInventory_Model_Stock_Item {



	/**
	 * Исправляем дефект метода Mage_CatalogInventory_Model_Indexer_Stock::_registerStockItemSaveEvent()
	 * Этот метод запускает переиндексацию складских остатков, если с полученным им объектом
	 * Df_CatalogInventory_Model_Stock_Item не связан объект Mage_Catalog_Model_Product.
	 *
	 *
	 * Однако для проверки такой связи Mage_CatalogInventory_Model_Indexer_Stock::_registerStockItemSaveEvent()
	 * использует метод Mage_CatalogInventory_Model_Stock_Item::getProduct().
	 *
	 * Метод getProduct() отсутствует в классе Mage_CatalogInventory_Model_Indexer_Stock
	 * и поле «product» тоже отсутствует,
	 * поэтому вызов Mage_CatalogInventory_Model_Stock_Item::getProduct() возвращает null,
	 * и Mage_CatalogInventory_Model_Indexer_Stock::_registerStockItemSaveEvent()
	 * запускает ненужную (дублирующую) переиндексацию
	 *
	 * @return Mage_Catalog_Model_Product|null
	 */
	public function getProduct () {


		$result = NULL;

		try {
			/**
			 * Учитываем ситуацию, что в будущих версиях Magento метод parent::getProduct ()
			 * может вернуть непустое значение.
			 *
			 * Внимание: parent::getDataUsingMethod ('product') ошибочно, ибо приводит к рекурсии!
			 */
			$result = parent::getProduct ();

			if (!is_null ($result)) {
				// Похоже, данная заплатка уже не нужна
			}
			else {
				if (!isset ($this->_productDf)) {

					/**
					 * Учитываем ситуацию, что в будущих версиях Magento поле $this->_productInstance
					 * может быть удалено, или изменится его назначение.
					 */
					if (
							isset ($this->_productInstance)
						&&
							($this->_productInstance instanceof Mage_Catalog_Model_Product)
					) {

						/**
						 * Применяем заплатку только при одновременном выполнении двух условий:
						 * 		[*] наличии лицензии на модуль df-tweaks-admin
						 * 		[*] явном выражении желания администратора включить модуль
						 */

						if (
								df_enabled(Df_Core_Feature::TWEAKS_ADMIN)
							&&
								df_cfg ()->admin()->optimization()
									->getFixDoubleStockReindexingOnProductSave()
						) {
							$this->_productDf = $this->_productInstance;
						}
					}
				}

				$result = $this->_productDf;

			}

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}



		return $result;
	}


	/**
	 * @var Mage_Catalog_Model_Product
	 */
	private $_productDf;


}


