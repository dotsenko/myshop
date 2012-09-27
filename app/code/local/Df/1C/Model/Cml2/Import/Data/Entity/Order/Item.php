<?php

class Df_1C_Model_Cml2_Import_Data_Entity_Order_Item
	extends Df_1C_Model_Cml2_Import_Data_Entity {


	
	/**
	 * @return Df_Catalog_Model_Product
	 */
	public function getProduct () {
	
		if (!isset ($this->_product)) {
	
			/** @var Df_Catalog_Model_Product $product  */
			$product =
				df_model (
					Df_Catalog_Model_Product::getNameInMagentoFormat()
				)
			;
	
			df_assert ($product instanceof Df_Catalog_Model_Product);

			/**
			 * Сначала пробуем загрузить товар по его внешнему идентификатору
			 */

			/** @var Df_Catalog_Model_Product $result  */
			$result =
				$product->loadByAttribute (
					Df_1C_Const::ENTITY_1C_ID
					,
					$this->getExternalId()
				)
			;

			if (false === $result) {

				/**
				 * Раз нам не удалось найти товар в Magento по его внешнему идентификатору,
				 * значит:
				 * [*] либо товар был создан изначально в Magento (и тогда мы ищем его по имени)
				 * [*] либо товар был создан в 1С:Управление торговлей,
				 * и этот товар из-за неправильных настроек профиля обмена данными с WEB-сайтом
				 * в 1С:Управление торговлей не экспортируется в Magento,
				 * и при этом товар был добавлен
				 * к ранее экспортированному из Magento в 1С:Управление торговлей заказу
				 * (в этом случае сигнализируем о неверных данных)
				 */

				$result =
					$product->loadByAttribute (
						Df_Catalog_Model_Product::PARAM__NAME
						,
						$this->getName()
					)
				;

				if (
						($result instanceof Df_Catalog_Model_Product)
					&&
						(0 < $result->getId())
				) {

					/**
					 * Итак, товар нашли по имени:
					 * значит, в Magento у этого товара нет внешнего идентификатора.
					 * Добавляем.
					 */
					Df_1C_Model_Cml2_Processor_Product_AddExternalId
						::processStatic (
							$result
							,
							$this->getExternalId()
						)
					;

				}
			}

			if (
					!($result instanceof Df_Catalog_Model_Product)
				||
					(1 > $result->getId())
			) {
				df_error (
					sprintf (
							'Товар «%s» из заказа «%s» отсутствует в Magento. '
						.
							"\r\nВы должны настроить экспорт товаров "
						.
							"из 1С:Управление торговлей в Magento таким образом, "
						.
							"чтобы этот товар экспортировался в Magento."
					)
				);
			}


			$this->_product = $result;
		}
	
		df_assert ($this->_product instanceof Df_Catalog_Model_Product);
	
		return $this->_product;
	}
	
	
	/**
	* @var Df_Catalog_Model_Product
	*/
	private $_product;	
	
	



	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Order
	 */
	private function getEntityOrder () {
		return $this->cfg (self::PARAM__ENTITY_ORDER);
	}




	/**
	 * @return Mage_Sales_Model_Order
	 */
	private function getOrder () {

		/** @var Mage_Sales_Model_Order $result  */
		$result = $this->getEntityOrder()->getOrder();

		df_assert ($result instanceof Mage_Sales_Model_Order);

		return $result;
	}




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
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Order_Item';
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
