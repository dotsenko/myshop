<?php

/**
 * Экспорт заказов из магазина в 1С: Управление торговлей
 */
class Df_1C_Model_Cml2_Action_Orders_Export extends Df_1C_Model_Cml2_Action {


	/**
	 * Для тестирования
	 *
	 * @override
	 * @return Zend_Date
	 */
	protected function getLastProcessedTime () {
		return
				df_is_it_my_local_pc()
			?
				Zend_Date::now()->sub (7, Zend_Date::DAY)
			:
				parent::getLastProcessedTime()
		;
	}



	/**
	 * @overrode
	 * @return bool
	 */
	protected function needUpdateLastProcessedTime () {
		return true;
	}



	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Action_Orders_Export
	 */
	protected function processInternal () {

		$this
			->getResponse()
			->setHeader (
				'Content-Type'
				,
				'application/xml; charset=UTF-8'
			)
		;


		if (df_is_it_my_local_pc()) {
			file_put_contents (
				implode (
					DS
					,
					array (
						Mage::getBaseDir('var')
						,
						'log'
						,
						'site-from-my.xml'
					)
				)
				,
				$this->getDocument()->getXml()
			);
		}


		$this
			->getResponse()
			->setBody (
				$this->getDocument()->getXml()
				//$this->getDocumentFake()->getXml()
			)
		;

		return $this;

	}




	/**
	 * Как нам создать документ XML?
	 * 2 основных способа:
	 *
	 * SimpleXml
	 *		плюсы:
	 *			лаконичность
	 *		примеры:
	 * 			iPay
	 * 			Varien_Object
	 *
	 * стандартные блоки и шаблоны
	 * 		плюсы:
	 * 			шаблон чётко отделён от бизнес-логики
	 * 		минусы:
	 * 		примеры:
	 *			генератор лицензий df/licensor_generator/license.xml
	 * 			ChronoPay df/chronopay/gate/request.xml
	 *
	 * @return Df_1C_Model_Cml2_SimpleXml_Document_Orders
	 */
	private function getDocument () {

		if (!isset ($this->_document)) {

			/** @var Df_1C_Model_Cml2_SimpleXml_Document_Orders $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_SimpleXml_Document_Orders::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_SimpleXml_Document_Orders
							::PARAM__ORDERS => $this->getOrders()
					)
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_SimpleXml_Document_Orders);


			$this->_document = $result;

		}


		df_assert ($this->_document instanceof Df_1C_Model_Cml2_SimpleXml_Document_Orders);

		return $this->_document;

	}


	/**
	* @var Df_1C_Model_Cml2_SimpleXml_Document_Orders
	*/
	private $_document;





	/**
	 * @return Df_1C_Model_Cml2_SimpleXml_Document
	 */
	private function getDocumentFake () {

		/** @var Df_1C_Model_Cml2_SimpleXml_Document $result  */
		$result =
			df_model (
				Df_1C_Model_Cml2_SimpleXml_Document::getNameInMagentoFormat()
			)
		;

		df_assert ($result instanceof Df_1C_Model_Cml2_SimpleXml_Document);

		return $result;
	}

	

	
	
	/**
	 * @return Mage_Sales_Model_Resource_Order_Collection|Mage_Sales_Model_Mysql4_Order_Collection
	 */
	private function getOrders () {
	
		if (!isset ($this->_orders)) {
	
			/** @var Mage_Sales_Model_Resource_Order_Collection|Mage_Sales_Model_Mysql4_Order_Collection $result  */
			$result = 
				Mage::getResourceModel (
					'sales/order_collection'
				)
			;
	
	        df_helper()->sales()->assert()->orderCollection ($result);



			/** @var Varien_Db_Select $select */
			$select = $result->getSelect ();

			df_assert ($select instanceof Varien_Db_Select);


			/** @var Zend_Db_Adapter_Abstract $adapter */
			$adapter = $select->getAdapter();

			df_assert ($adapter instanceof Zend_Db_Adapter_Abstract);



			/**
			 * Отбраковываем из коллекции заказы других магазинов
			 */
			$result
				->addFieldToFilter (
					$adapter->quoteIdentifier (
						Df_Sales_Model_Order::PARAM__STORE_ID
					)
					,
					df_helper()->_1c()->cml2()->getStoreProcessed()->getId()
				)
			;


			/**
			 * Магазин должен передавать в 1С: Управление торговлей 2 вида заказов:
			 *
			 * 1) Заказы, которые были созданы в магазине ПОСЛЕ последнего сеанса передачи данных
			 * 2) Заказы, которые были изменены в магазине ПОСЛЕ последнего сеанса передачи данных
			 *
			 * Как я понимаю, оба ограничения можно наложить единым фильтром:
			 * по времени изменения заказа.
			 */

			$result
				->addFieldToFilter (
					$adapter->quoteIdentifier (
						Df_Sales_Model_Order::PARAM__UPDATED_AT
					)
					,
					array (
						Df_Varien_Const::FROM => $this->getLastProcessedTime()
						,
						Df_Varien_Const::DATETIME => true
					)
				)
			;

			$this->_orders = $result;
		}

		df_helper()->sales()->assert()->orderCollection ($this->_orders);
	
		return $this->_orders;
	
	}
	
	
	/**
	* @var Mage_Sales_Model_Resource_Order_Collection|Mage_Sales_Model_Mysql4_Order_Collection
	*/
	private $_orders;	






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Action_Orders_Export';
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
