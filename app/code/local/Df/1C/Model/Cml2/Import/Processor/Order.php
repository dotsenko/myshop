<?php

class Df_1C_Model_Cml2_Import_Processor_Order extends Df_1C_Model_Cml2_Import_Processor {



	/**
	 * @return Df_1C_Model_Cml2_Import_Processor_Order
	 */
	public function process () {

		/**
		 * Обновляем в Magento заказ
		 * на основании пришедших из 1С:Управление торговлей данных
		 */

		if (is_null ($this->getEntity()->getOrder()->getId())) {
			df_helper()->_1c()
				->log (
					sprintf (
								'Отказываемся импортировать заказ №%s, '
							.
								'потому что этот заказ отсутствует в Magento'
						,
						$this->getEntity()->getIncrementId()
					)
				)
			;
		}
		else {

			/**
			 * Итак, пришедший из 1С:Управление торговлей заказ найден в магазине.
			 * Обновляем заказ в магазине.
			 * Обратите внимание, что при ручном редактировании заказа
			 * из административной части Magento система:
			 * 1) отменяет подлежащий редактированию заказ
			 * 2) создаёт его копию — новый заказ
			 * 3) привязывает копию к подлежащему редактированию заказу
			 * 4) редактирует и сохраняет копию.
			 *
			 * Мы так делать не будем,
			 * вместо этого будем менять подлежащий редактированию заказ напрямую.
			 */


			/**
			 * Исходим из предпосылки, что в разным простым строкам заказа в Magento
			 * непременно соответствуют разные товары
			 *
			 * @todo ЭТО НЕПРАВДА!
			 * @todo В заказе один и тот же простой товар с настраиваемыми вариантами
			 * @todo может присутствовать в разных строках:
			 * @todo каждой строке будет соответствовать свой настраиваемый вариант.
			 *
			 *
			 * В импортирумых данных разным строкам заказа тоже соответствуют разные товары
			 * благодаря применению шаблона composite:
			 * @see Df_1C_Model_Cml2_Import_Data_Entity_Order_Item_Composite
			 *
			 * Поэтому путём сравнения множества идентификаторов товаров в заказе Magento
			 * с множеством идентификатором товаров импортированной версии того же самого заказа,
			 * мы увидим:
			 * а) какие строки заказа надо удалить
			 * б) какие строки заказа надо изменить
			 * в) какие строки заказа надо добавить
			 */


//			$this->orderItemsUpdate();
//			$this->orderItemsDelete();
//			$this->orderItemsAdd();

		}

		return $this;
	}




	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Order
	 */
	protected function getEntity () {
		return parent::getEntity();
	}




	/**
	 * @return array
	 */
	private function getMapFromProductIdToSimpleOrderItem () {

		if (!isset ($this->_mapFromProductIdToSimpleOrderItem)) {

			/** @var array $result  */
			$result = array ();

			foreach ($this->getEntity()->getOrder()->getAllItems() as $orderItem) {

				/** @var Mage_Sales_Model_Order_Item $orderItem */
				df_assert ($orderItem instanceof Mage_Sales_Model_Order_Item);

				/**
				 * Собираем идентификаторы только простых товаров.
				 */
				if (Mage_Catalog_Model_Product_Type::TYPE_SIMPLE === $orderItem->getProductType()) {

					/** @var int $productId */
					$productId = $orderItem->getProductId();

					df_assert_integer ($productId);

					df_assert (is_null (df_a ($result, $productId)));

					$result[$productId] = $orderItem;

				}

			}

			df_assert_array ($result);

			$this->_mapFromProductIdToSimpleOrderItem = $result;
		}


		df_result_array ($this->_mapFromProductIdToSimpleOrderItem);

		return $this->_mapFromProductIdToSimpleOrderItem;
	}


	/**
	* @var array
	*/
	private $_mapFromProductIdToSimpleOrderItem;
	
	
	
	
	/**
	 * @return int[]
	 */
	private function getProductIdsFrom1COrder () {
	
		if (!isset ($this->_productIdsFrom1COrder)) {
	
			/** @var int[] $result  */
			$result = array ();

			foreach ($this->getEntity()->getItems() as $entityOrderItem) {
				/** @var Df_1C_Model_Cml2_Import_Data_Entity_Order_Item $entityOrderItem */
				df_assert ($entityOrderItem instanceof Df_1C_Model_Cml2_Import_Data_Entity_Order_Item);


				/** @var Df_Catalog_Model_Product $product */
				$product = $entityOrderItem->getProduct();

				df_assert ($product instanceof Df_Catalog_Model_Product);


				$result []= $product->getId();
			}
	
			df_assert_array ($result);
	
			$this->_productIdsFrom1COrder = $result;
		}
	
	
		df_result_array ($this->_productIdsFrom1COrder);
	
		return $this->_productIdsFrom1COrder;
	}
	
	
	/**
	* @var int[]
	*/
	private $_productIdsFrom1COrder;
	
	
	
	
	
	/**
	 * Возвращает идентификаторы только простых товаров
	 *
	 * @return int[]
	 */
	private function getProductIdsFromMagentoOrder () {
	
		if (!isset ($this->_productIdsFromMagentoOrder)) {
	
			/** @var int[] $result  */
			$result = array ();

			foreach ($this->getEntity()->getOrder()->getAllItems() as $orderItem) {

				/** @var Mage_Sales_Model_Order_Item $orderItem */
				df_assert ($orderItem instanceof Mage_Sales_Model_Order_Item);

				/**
				 * Собираем идентификаторы только простых товаров.
				 */
				if (Mage_Catalog_Model_Product_Type::TYPE_SIMPLE === $orderItem->getProductType()) {
					$result[]= $orderItem->getProductId();
				}

			}
	
			df_assert_array ($result);
	
			$this->_productIdsFromMagentoOrder = $result;
		}
	
	
		df_result_array ($this->_productIdsFromMagentoOrder);
	
		return $this->_productIdsFromMagentoOrder;
	}
	
	
	/**
	* @var int[]
	*/
	private $_productIdsFromMagentoOrder;	
	
	
	
	
	
	/**
	 * @return int[]
	 */
	private function getProductIdsToAdd () {
	
		if (!isset ($this->_productIdsToAdd)) {
	
			/** @var int[] $result  */
			$result =
				array_diff (
					$this->getProductIdsFrom1COrder()
					,
					$this->getProductIdsFromMagentoOrder()
				)
			;
	
			df_assert_array ($result);
	
			$this->_productIdsToAdd = $result;
		}
	
	
		df_result_array ($this->_productIdsToAdd);
	
		return $this->_productIdsToAdd;
	}
	
	
	/**
	* @var int[]
	*/
	private $_productIdsToAdd;		
	
	
	
	
	

	/**
	 * @return int[]
	 */
	private function getProductIdsToDelete () {
	
		if (!isset ($this->_productIdsToDelete)) {
	
			/** @var int[] $result  */
			$result =
				array_diff (
					$this->getProductIdsFromMagentoOrder()
					,
					$this->getProductIdsFrom1COrder()
				)
			;
	
			df_assert_array ($result);
	
			$this->_productIdsToDelete = $result;
		}
	
	
		df_result_array ($this->_productIdsToDelete);
	
		return $this->_productIdsToDelete;
	}
	
	
	/**
	* @var int[]
	*/
	private $_productIdsToDelete;	
	
	
	
	
	
	/**
	 * @return int[]
	 */
	private function getProductIdsToUpdate () {
	
		if (!isset ($this->_productIdsToUpdate)) {
	
			/** @var int[] $result  */
			$result =
				array_intersect (
					$this->getProductIdsFromMagentoOrder()
					,
					$this->getProductIdsFrom1COrder()
				)
			;
	
			df_assert_array ($result);
	
			$this->_productIdsToUpdate = $result;
		}
	
	
		df_result_array ($this->_productIdsToUpdate);
	
		return $this->_productIdsToUpdate;
	}
	
	
	/**
	* @var int[]
	*/
	private $_productIdsToUpdate;		





	/**
	 * @param int $productId
	 * @return Mage_Sales_Model_Order_Item
	 */
	private function getSimpleOrderItemByProductId ($productId) {

		df_param_integer ($productId, 0);
		df_param_between ($productId, 0, 1);

		/** @var Mage_Sales_Model_Order_Item $result  */
		$result =
			df_a (
				$this->getMapFromProductIdToSimpleOrderItem()
				,
				$productId
			)
		;

		df_assert ($result instanceof Mage_Sales_Model_Order_Item);

		return $result;
	}





	/**
	 * @return Df_1C_Model_Cml2_Import_Processor_Order
	 */
	private function orderItemsAdd () {

		$this
			->orderItemsProcess (
				$this->getProductIdsToAdd()
				,
				Df_1C_Model_Cml2_Import_Processor_Order_Item_Add::getNameInMagentoFormat()
			)
		;

		return $this;
	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Processor_Order
	 */
	private function orderItemsDelete () {

//		$this
//			->orderItemsProcess (
//				$this->getProductIdsToDelete()
//				,
//				Df_1C_Model_Cml2_Import_Processor_Order_Item_Delete::getNameInMagentoFormat()
//			)
//		;


		foreach ($this->getProductIdsToDelete() as $productId) {

			/** @var int $productId */
			df_assert_integer ($productId);
			df_assert_between ($productId, 1);

			/** @var Mage_Sales_Model_Order_Item $orderItem  */
			$orderItem = $this->getSimpleOrderItemByProductId ($productId);

			df_assert ($orderItem instanceof Mage_Sales_Model_Order_Item);

			/** @var Df_1C_Model_Cml2_Import_Processor_Order_Item $processor */
//			$processor =
//				df_model (
//					$processorClassMf
//					,
//					array (
//						Df_1C_Model_Cml2_Import_Processor_Order_Item_Add
//							::PARAM__ENTITY_ORDER => $this->getEntity()
//						,
//						Df_1C_Model_Cml2_Import_Processor_Order_Item_Add::PARAM__ENTITY =>
//							$this->getEntity()->getItems()->getItemByProductId($productId)
//					)
//				)
//			;
//
//			df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Order_Item);
//
//			$processor->process();

		}


		return $this;
	}




	/**
	 * @param int[] $productIds
	 * @param string $processorClassMf
	 * @return Df_1C_Model_Cml2_Import_Processor_Order
	 */
	private function orderItemsProcess (array $productIds, $processorClassMf) {

		foreach ($productIds as $productId) {

			/** @var int $productId */
			df_assert_integer ($productId);
			df_assert_between ($productId, 1);

			/** @var Df_1C_Model_Cml2_Import_Processor_Order_Item $processor */
			$processor =
				df_model (
					$processorClassMf
					,
					array (
						Df_1C_Model_Cml2_Import_Processor_Order_Item_Add
							::PARAM__ENTITY_ORDER => $this->getEntity()
						,
						Df_1C_Model_Cml2_Import_Processor_Order_Item_Add::PARAM__ENTITY =>
							$this->getEntity()->getItems()->getItemByProductId($productId)
					)
				)
			;

			df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Order_Item);

			$processor->process();

		}

		return $this;
	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Processor_Order
	 */
	private function orderItemsUpdate () {

		$this
			->orderItemsProcess (
				$this->getProductIdsToUpdate()
				,
				Df_1C_Model_Cml2_Import_Processor_Order_Item_Update::getNameInMagentoFormat()
			)
		;

		return $this;
	}

	



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__ENTITY, Df_1C_Model_Cml2_Import_Data_Entity_Order::getClass()
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Order';
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
