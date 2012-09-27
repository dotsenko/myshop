<?php

class Df_1C_Model_Cml2_Action_Orders_Import extends Df_1C_Model_Cml2_Action {


	/**
	 * @overrode
	 * @return bool
	 */
	protected function needUpdateLastProcessedTime () {
		return true;
	}


	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Action_Orders_Import
	 */
	protected function processInternal () {

		/**
		 * Обратите внимание,
		 * что 1С: Управление торговлей передаёт в магазин только те заказы,
		 * которые ранее были переданы из магазина в 1С: Управление торговлей
		 */

		if (df_is_it_my_local_pc()) {
			$this->logXml();
		}



		foreach ($this->getOrders() as $entityOrder) {
			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Order $entityOrder */
			df_assert ($entityOrder instanceof Df_1C_Model_Cml2_Import_Data_Entity_Order);


			/** @var Df_1C_Model_Cml2_Import_Processor_Order $processor */
			$processor =
				df_model (
					Df_1C_Model_Cml2_Import_Processor_Order::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Processor_Order::PARAM__ENTITY => $entityOrder
					)
				)
			;

			df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Order);

			$processor->process();
		}



		$this
			->setResponseBodyAsArrayOfStrings (
				array (
					'success'
					,
					Df_Core_Const::T_EMPTY
				)
			)
		;

		return $this;
	}




	/**
	 * @return string
	 */
	private function getFileFullPath () {

		/** @var string $result  */
		$result =
			implode (
				DS
				,
				array (
					Mage::getBaseDir('var')
					,
					'log'
					,
					'site-to-my.xml'
				)
			)
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_Orders
	 */
	private function getOrders () {

		if (!isset ($this->_orders)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_Orders $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_Orders::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_Orders
							::PARAM__SIMPLE_XML => $this->getSimpleXmlElement()
					)
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_Orders);

			$this->_orders = $result;
		}

		df_assert ($this->_orders instanceof Df_1C_Model_Cml2_Import_Data_Collection_Orders);

		return $this->_orders;
	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_Orders
	*/
	private $_orders;


	
	
	
	
	/**
	 * @return Df_Varien_Simplexml_Element
	 */
	private function getSimpleXmlElement () {
	
		if (!isset ($this->_simpleXmlElement)) {
	
			/** @var Df_Varien_Simplexml_Element $result  */
			$result = 	
				new Df_Varien_Simplexml_Element (
					$this->getXml()
				)
			;
	
			df_assert ($result instanceof Df_Varien_Simplexml_Element);
	
			$this->_simpleXmlElement = $result;
		}
	
		df_assert ($this->_simpleXmlElement instanceof Df_Varien_Simplexml_Element);
	
		return $this->_simpleXmlElement;
	}
	
	
	/**
	* @var Df_Varien_Simplexml_Element
	*/
	private $_simpleXmlElement;	
	
	
	
	
	
	/**
	 * @return string
	 */
	private function getXml () {
	
		if (!isset ($this->_xml)) {
	
			/** @var string $result  */
			$result = 	
				file_get_contents (
					'php://input'
				)
			;
	
			df_assert_string ($result);
	
			$this->_xml = $result;
		}
	
		df_result_string ($this->_xml);
	
		return $this->_xml;
	}
	
	
	/**
	* @var string
	*/
	private $_xml;




	/**
	 * @return Df_1C_Model_Cml2_Action_Orders_Import
	 */
	private function logXml () {

		/** @var Varien_Io_File $file  */
		$file = new Varien_Io_File();

		df_assert ($file instanceof Varien_Io_File);


		$file->setAllowCreateFolders(true);

		$file
			->createDestinationDir (
				dirname (
					$this->getFileFullPath()
				)
			)
		;

		file_put_contents (
			$this->getFileFullPath()
			,
			$this->getXml()
		);

		return $this;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Action_Orders_Import';
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
