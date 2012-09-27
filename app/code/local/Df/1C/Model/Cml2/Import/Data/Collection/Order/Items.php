<?php

class Df_1C_Model_Cml2_Import_Data_Collection_Order_Items
	extends Df_1C_Model_Cml2_Import_Data_Collection {



	/**
	 * @param int $productId
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Order_Item
	 */
	public function getItemByProductId ($productId) {

		df_param_integer ($productId, 0);
		df_param_between ($productId, 0, 1);

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_Order_Item $result  */
		$result =
			df_a (
				$this->getMapFromProductIdToOrderItem()
				,
				$productId				
			)
		;

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_Order_Item);

		return $result;
	}




	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Order_Item[]
	 */
	public function getItems () {

		if (!isset ($this->_items)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Order_Item[] $result  */
			$result = new ArrayObject (array ());

			/** @var array $mapFromProductsToOrderItems */
			$mapFromProductsToOrderItems = array ();

			foreach ($this->getImportEntitiesAsSimpleXMLElementArray () as $entityAsSimpleXMLElement) {

				/** @var Varien_Simplexml_Element $entityAsSimpleXMLElement */
				df_assert ($entityAsSimpleXMLElement instanceof Varien_Simplexml_Element);

				/** @var Df_1C_Model_Cml2_Import_Data_Entity_Order_Item $orderItem  */
				$orderItem = $this->createItemFromSimpleXmlElement ($entityAsSimpleXMLElement);

				df_assert ($orderItem instanceof Df_1C_Model_Cml2_Import_Data_Entity_Order_Item);


				/** @var int $productId */
				$productId = $orderItem->getProduct()->getId();

				df_assert_integer ($productId);


				/** @var array $orderItemsForTheProduct */
				$orderItemsForTheProduct =
					df_a (
						$mapFromProductsToOrderItems
						,
						$productId
						,
						array ()
					)
				;

				df_assert_array ($orderItemsForTheProduct);

				$orderItemsForTheProduct []= $orderItem;

				$mapFromProductsToOrderItems[$productId] = $orderItemsForTheProduct;
			}


			foreach ($mapFromProductsToOrderItems as $orderItemsForTheProduct) {

				/** @var array $orderItemsForTheProduct */

				/** @var int $orderItemsCount */
				$orderItemsCount = count ($orderItemsForTheProduct);

				df_assert_integer ($orderItemsCount);
				df_assert_between ($orderItemsCount, 1);

				if (1 === $orderItemsCount) {
					$result []= df_a ($orderItemsForTheProduct, 0);
				}
				else {

					/** @var $orderItemComposite */
					$orderItemComposite =
						df_model (
							Df_1C_Model_Cml2_Import_Data_Entity_Order_Item_Composite::getNameInMagentoFormat()
							,
							array (
								Df_1C_Model_Cml2_Import_Data_Entity_Order_Item_Composite
									/**
									 * Намеренно передаём null!
									 */
									::PARAM__SIMPLE_XML => null

								,
								Df_1C_Model_Cml2_Import_Data_Entity_Order_Item_Composite
									::PARAM__ENTITY_ORDER => $this->getEntityOrder()

								,
								Df_1C_Model_Cml2_Import_Data_Entity_Order_Item_Composite
									::PARAM__SIMPLE_ITEMS => $orderItemsForTheProduct
							)
						)
					;

					df_assert (
							$orderItemComposite
						instanceof
							Df_1C_Model_Cml2_Import_Data_Entity_Order_Item_Composite
					);

					$result []= $orderItemComposite;
				}

			}


			df_assert ($result instanceof ArrayObject);

			$this->_items = $result;

		}


		df_assert ($this->_items instanceof ArrayObject);

		return $this->_items;

	}





	/**
	 * @override
	 * @return string
	 */
	protected function getItemClassMf () {
		return Df_1C_Model_Cml2_Import_Data_Entity_Order_Item::getNameInMagentoFormat();
	}




	/**
	 * @override
	 * @return array
	 */
	protected function getItemParamsAdditional () {

		/** @var array $result */
		$result =
			array_merge (
				parent::getItemParamsAdditional()
				,
				array (
					Df_1C_Model_Cml2_Import_Data_Entity_Order_Item
						::PARAM__ENTITY_ORDER => $this->getEntityOrder()
				)
			)
		;

		df_result_array ($result);

		return $result;
	}





	/**
	 * @override
	 * @return array
	 */
	protected function getXmlPathAsArray () {
		return
			array (
				'Товары'
				,
				'Товар'
			)
		;
	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Order
	 */
	private function getEntityOrder () {
		return $this->cfg (self::PARAM__ENTITY_ORDER);
	}

	
	
	
	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Order_Item[]
	 */
	private function getMapFromProductIdToOrderItem () {
	
		if (!isset ($this->_mapFromProductIdToOrderItem)) {
	
			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Order_Item[] $result  */
			$result = array ();

			foreach ($this->getItems() as $entityOrderItem) {
				/** @var Df_1C_Model_Cml2_Import_Data_Entity_Order_Item $entityOrderItem */
				df_assert ($entityOrderItem instanceof Df_1C_Model_Cml2_Import_Data_Entity_Order_Item);

				/** @var int $productId */
				$productId = $entityOrderItem->getProduct()->getId();

				df_assert_integer ($productId);

				df_assert (is_null (df_a ($result, $productId)));

				$result[$productId] = $entityOrderItem;
			}
	
			df_assert_array ($result);
	
			$this->_mapFromProductIdToOrderItem = $result;
		}
	
	
		df_result_array ($this->_mapFromProductIdToOrderItem);
	
		return $this->_mapFromProductIdToOrderItem;
	}
	
	
	/**
	* @var Df_1C_Model_Cml2_Import_Data_Entity_Order_Item[]
	*/
	private $_mapFromProductIdToOrderItem;	
	




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__ENTITY_ORDER, Df_1C_Model_Cml2_Import_Data_Entity_Order::getClass()
			)
		;
	}



	const PARAM__ENTITY_ORDER = 'entity_order';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Collection_Order_Items';
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
