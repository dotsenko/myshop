<?php

class Df_1C_Model_Cml2_Export_Processor_Order extends Df_1C_Model_Cml2_Export_Processor {


	/**
	 * @return Df_1C_Model_Cml2_Export_Processor_Order
	 */
	public function process () {

		$this->getDocument()
			->importArray(
				$this->getDocumentData_Order()
				,
				$wrapInCData =
					array (
						'Ид'
						,'Комментарий'
						,'Наименование'
						,'Описание'
						,'Представление'

					)
			)
		;


		return $this;
	}





	/**
	 * @return Mage_Sales_Model_Order_Address
	 */
	private function getAddress () {
	
		if (!isset ($this->_address)) {
	
			/** @var Mage_Sales_Model_Order_Address $result  */
			$result = 	
				df_model (
					Df_Sales_Const::ORDER_ADDRESS_CLASS_MF
				)
			;
	
			df_assert ($result instanceof Mage_Sales_Model_Order_Address);

			$result
				->addData (
					df_merge_not_empty (
						$this->getOrder()->getBillingAddress()->getData()
						,
						$this->getOrder()->getShippingAddress()->getData()
					)
				)
			;

			$this->_address = $result;
		}
	
		df_assert ($this->_address instanceof Mage_Sales_Model_Order_Address);
	
		return $this->_address;
	}
	
	
	/**
	* @var Mage_Sales_Model_Order_Address
	*/
	private $_address;	



	
	/**
	 * @return Df_Customer_Model_Customer
	 */
	private function getCustomer () {
	
		if (!isset ($this->_customer)) {
	
			/** @var Df_Customer_Model_Customer $result  */
			$result = 
				df_model (
					Df_Customer_Model_Customer::getNameInMagentoFormat()
				)
			;
	
			df_assert ($result instanceof Df_Customer_Model_Customer);

			$result->load ($this->getOrder()->getCustomerId());

			$this->_customer = $result;
		}
	
	
		df_assert ($this->_customer instanceof Df_Customer_Model_Customer);
	
		return $this->_customer;
	}
	
	
	/**
	* @var Df_Customer_Model_Customer
	*/
	private $_customer;	






	/**
	 * @return string
	 */
	private function getCustomerDateOfBirthAsString () {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;

		if (!is_null ($this->getCustomer()->getDateOfBirth())) {
			$result =
				$this->getCustomer()->getDateOfBirth()->toString (
					Df_1C_Model_Cml2_SimpleXml_Document::DATE_FORMAT
				)
			;
		}

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return string
	 */
	private function getCustomerGender () {

		/** @var string $result  */
		$result =
			df_convert_null_to_empty_string (
				df_a (
					array (
						Df_Customer_Model_Customer::GENDER__FEMALE => 'F'
						,
						Df_Customer_Model_Customer::GENDER__MALE => 'M'
					)
					,
					$this->getCustomer()->getGender()
				)
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return Df_Varien_Simplexml_Element
	 */
	private function getDocument () {

		if (!isset ($this->_document)) {

			/** @var Df_Varien_Simplexml_Element $result  */
			$result = $this->getSimpleXmlElement()->addChild ('Документ');

			/**
			 * Обратите внимание, что
			 * SimpleXMLElement::addChild создаёт и возвращает не просто SimpleXMLElement,
			 * как говорит документация, а объект класса родителя.
			 * Поэтому в нашем случае addChild создаст объект Df_Varien_Simplexml_Element.
			 */
			df_assert ($result instanceof Df_Varien_Simplexml_Element);

			$this->_document = $result;
		}


		df_assert ($this->_document instanceof Df_Varien_Simplexml_Element);

		return $this->_document;
	}


	/**
	* @var Df_Varien_Simplexml_Element
	*/
	private $_document;





	/**
	 * @return array
	 */
	private function getDocumentData_Customer () {

		/** @var array $result  */
		$result =
			array (
				'Ид' => $this->getOrder()->getCustomerId()
				,
				'Наименование' => $this->getCustomer()->getName()
				,
				'Роль' => 'Покупатель'
				,
				'ПолноеНаименование' => $this->getCustomer()->getName()
				,
				'Фамилия' => $this->getCustomer()->getNameLast()
				,
				'Имя' => $this->getCustomer()->getNameFirst()
				,
				'Отчество' => $this->getCustomer()->getNameMiddle()
				,
				'ДатаРождения' => $this->getCustomerDateOfBirthAsString()
				,
				'Пол' => $this->getCustomerGender()
				,
				'ИНН' => $this->getCustomer()->getInn()
				,
				'КПП' => Df_Core_Const::T_EMPTY
				,
				'АдресРегистрации' => $this->getDocumentData_CustomerAddress()
				,
				'Адрес' => $this->getDocumentData_CustomerAddress()
				,
				'Контакты' => $this->getDocumentData_CustomerContacts()
			)
		;

		df_result_array ($result);

		return $result;
	}





	/**
	 * @return array
	 */
	private function getDocumentData_CustomerAddress () {

		/** @var array $result  */
		$result =
			array (
				'Представление' =>
					Df_Varien_Simplexml_Element::markAsCData (
						$this->getAddress()->format(
							Mage_Customer_Model_Attribute_Data::OUTPUT_FORMAT_TEXT
						)
					)
				,
				'АдресноеПоле' =>
					array (
						array (
							'Тип' => 'Почтовый индекс'
							,
							'Значение' => $this->getAddress()->getPostcode()
						)
						,
						array (
							'Тип' => 'Улица'
							,
							'Значение' =>
								Df_Varien_Simplexml_Element::markAsCData (
										is_array ($this->getAddress()->getStreetFull())
									?
										implode ("\r\n", $this->getAddress()->getStreetFull())
									:
										$this->getAddress()->getStreetFull()
								)
						)
						,
						array (
							'Тип' => 'Страна'
							,
							'Значение' => $this->getAddress()->getCountryModel()->getName()
						)
						,
						array (
							'Тип' => 'Регион'
							,
							'Значение' => $this->getAddress()->getRegion()
						)
						,
						array (
							'Тип' => 'Район'
							,
							'Значение' => Df_Core_Const::T_EMPTY
						)
						,
						array (
							'Тип' => 'Населенный пункт'
							,
							'Значение' => $this->getAddress()->getCity()
						)
						,
						array (
							'Тип' => 'Город'
							,
							'Значение' => $this->getAddress()->getCity()
						)
						,
						array (
							'Тип' => 'Улица'
							,
							'Значение' => Df_Core_Const::T_EMPTY
						)
						,
						array (
							'Тип' => 'Дом'
							,
							'Значение' => Df_Core_Const::T_EMPTY
						)
						,
						array (
							'Тип' => 'Корпус'
							,
							'Значение' => Df_Core_Const::T_EMPTY
						)
						,
						array (
							'Тип' => 'Квартира'
							,
							'Значение' => Df_Core_Const::T_EMPTY
						)
					)
			)
		;

		df_result_array ($result);

		return $result;
	}





	/**
	 * @return array
	 */
	private function getDocumentData_CustomerContacts () {

		/** @var array $result  */
		$result =
			array (
				'Контакт' =>
					array (
						array (
							'Тип' => 'ТелефонРабочий'
							,
							'Значение' => $this->getAddress()->getTelephone()
						)
						,
						array (
							'Тип' => 'Почта'
							,
							'Значение' => $this->getCustomer()->getEmail()
						)
					)
			)
		;

		df_result_array ($result);

		return $result;
	}





	/**
	 * @return array
	 */
	private function getDocumentData_Discounts () {

		/** @var array $result  */
		$result =
			array (
				array (
					'Наименование' => 'Скидка'
					,
					'УчтеноВСумме' => $this->convertBooleanToString (true)
					,
					'Сумма' =>
						df_helper()->_1c()->cml2()->formatMoney (
							abs (
								$this->getOrder()->getDiscountAmount()
							)
						)
				)
			)
		;


		/** @var float $rewardAmount */
		$rewardAmount =
			floatval (
				$this->getOrder()->getData (
					Df_Sales_Model_Order::PARAM__REWARD_CURRENCY_AMOUNT
				)
			)
		;


		if (0 < $rewardAmount) {
			$result[] =
				array (
					'Наименование' => 'Бонусная скидка'
					,
					'УчтеноВСумме' => $this->convertBooleanToString (false)
					,
					'Сумма' =>
						df_helper()->_1c()->cml2()->formatMoney (
							$rewardAmount
						)
				)
			;
		}



		/** @var float $customerBalanceAmount */
		$customerBalanceAmount =
			floatval (
				$this->getOrder()->getData (
					Df_Sales_Model_Order::PARAM__CUSTOMER_BALANCE_AMOUNT
				)
			)
		;

		if (0 < $customerBalanceAmount) {
			$result[] =
				array (
					'Наименование' => 'Оплата с личного счёта'
					,
					'УчтеноВСумме' => $this->convertBooleanToString (false)
					,
					'Сумма' =>
						df_helper()->_1c()->cml2()->formatMoney (
							$customerBalanceAmount
						)
				)
			;
		}


		df_result_array ($result);

		return $result;
	}





	/**
	 * @return array
	 */
	private function getDocumentData_Order () {

		/** @var array $result  */
		$result =
			array (
				'Ид' => $this->getOrder()->getId()

				,
				'Номер' => $this->getOrder()->getIncrementId()

				,
				'Дата' =>
					$this->getOrder()->getCreatedAtStoreDate()
						->toString (
							Df_1C_Model_Cml2_SimpleXml_Document::DATE_FORMAT
						)

				,
				'ХозОперация' => 'Заказ товара'

				,
				'Роль' => 'Продавец'

				,
				'Валюта' =>
					df_helper()->_1c()->cml2()->convertCurrencyCodeTo1CFormat (
						$this->getOrder()->getOrderCurrencyCode()
					)

				,
				'Курс' => 1

				,
				'Сумма' =>
					df_helper()->_1c()->cml2()->formatMoney (
						$this->getOrder()->getGrandTotal()
					)

				,
				'Контрагенты' =>
					array (
						'Контрагент' => $this->getDocumentData_Customer()
					)

				,
				'Время' =>
					$this->getOrder()->getCreatedAtStoreDate()
						->toString (
							Zend_Date::TIME_MEDIUM
						)

				,
				'Налоги' =>
					array (
						'Налог' =>
							array (
								'Наименование' => 'Совокупный налог'

								,
								'УчтеноВСумме' => $this->convertBooleanToString (true)

								,
								'Сумма' =>
									df_helper()->_1c()->cml2()->formatMoney (
										$this->getOrder()->getTaxAmount()
									)
							)
					)

				,
				'Скидки' =>
					array (
						'Скидка' =>  $this->getDocumentData_Discounts()
					)

				,
				'Товары' =>
					array (
						'Товар' => $this->getDocumentData_OrderItems()
					)


					,
					'ЗначенияРеквизитов' =>
						array (
							'ЗначениеРеквизита' => $this->getDocumentData_OrderProperties()
						)


				,
				'Комментарий' => $this->getOrderComments()
			)
		;

		df_result_array ($result);

		return $result;
	}







	/**
	 * @return array
	 */
	private function getDocumentData_OrderItems () {

		/** @var array $result  */
		$result = array ();

		foreach ($this->getOrder()->getItemsCollection() as $item) {

			/** @var Mage_Sales_Model_Order_Item $item */
			df_assert ($item instanceof Mage_Sales_Model_Order_Item);

			if (Mage_Catalog_Model_Product_Type::TYPE_SIMPLE === $item->getProductType()) {

				/** @var Df_1C_Model_Cml2_Export_Processor_Order_Item $processor  */
				$processor =
					df_model (
						Df_1C_Model_Cml2_Export_Processor_Order_Item::getNameInMagentoFormat()
						,
						array (
							Df_1C_Model_Cml2_Export_Processor_Order_Item::PARAM__ORDER_ITEM => $item
						)
					)
				;

				df_assert ($processor instanceof Df_1C_Model_Cml2_Export_Processor_Order_Item);

				if (!df_empty ($processor->getDocumentData())) {
					$result []= $processor->getDocumentData();
				}
			}
		}

		if (0 < $this->getOrder()->getShippingAmount()) {

			/**
			 * Используем тот же трюк, что и 1С-Битрикс:
			 * указываем стоимость доставки отдельной строкой заказа
			 */

			$result[] =
				array (
					'Ид' => 'ORDER_DELIVERY'
					,
					'Наименование' => 'Доставка заказа'
					,
					'БазоваяЕдиница' =>
						array (
							Df_Varien_Simplexml_Element::KEY__ATTRIBUTES =>
								array (
									'Код' => '796'
									,
									'НаименованиеПолное' => 'Штука'
									,
									'МеждународноеСокращение' => 'PCE'
								)
							,
							Df_Varien_Simplexml_Element::KEY__VALUE => 'шт'
						)

					,
					'ЦенаЗаЕдиницу' =>
						df_helper()->_1c()->cml2()->formatMoney (
							$this->getOrder()->getShippingAmount()
						)


					,
					'Количество' => 1

					,
					'Сумма' =>
						df_helper()->_1c()->cml2()->formatMoney (
							$this->getOrder()->getShippingAmount()
						)

					,
					'ЗначенияРеквизитов' =>
						array (
							'ЗначениеРеквизита' =>
								array (
									array (
										'Наименование' => 'ВидНоменклатуры'
										,
										'Значение' => 'Услуга'
									)
									,
									array (
										'Наименование' => 'ТипНоменклатуры'
										,
										'Значение' => 'Услуга'
									)
								)
						)

				)
			;

		}

		$result = df_clean ($result);

		df_result_array ($result);

		return $result;
	}





	/**
	 * @return array
	 */
	private function getDocumentData_OrderProperties () {

		/** @var array $result  */
		$result = array ();


		if (false !== $this->getOrder()->getPayment()) {

			$result []=
				array (
					'Наименование' => 'Метод оплаты'
					,
					'Значение' => $this->getOrder()->getPayment()->getMethodInstance()->getTitle()
				)
			;
		}


		$result []=
			array (
				'Наименование' => 'Заказ оплачен'
				,
				'Значение' =>
					$this->convertBooleanToString (0 >= $this->getOrder()->getTotalDue())
			)
		;


		$result []=
			array (
				'Наименование' => 'Способ доставки'
				,
				'Значение' => $this->getOrder()->getShippingDescription()
			)
		;


		$result []=
			array (
				'Наименование' => 'Доставка разрешена'
				,
				'Значение' => $this->convertBooleanToString ($this->getOrder()->canShip())
			)
		;


		$result []=
			array (
				'Наименование' => 'Отменен'
				,
				'Значение' => $this->convertBooleanToString ($this->getOrder()->isCanceled())
			)
		;


		$result []=
			array (
				'Наименование' => 'Финальный статус'
				,
				'Значение' =>
					$this->convertBooleanToString (
						Mage_Sales_Model_Order::STATE_COMPLETE === $this->getOrder()->getState()
					)
			)
		;


		$result []=
			array (
				'Наименование' => 'Статус заказа'
				,
				'Значение' =>
					implode (
						' / '
						,
						array (
							$this->getOrder()->getState()
							,
							$this->getOrder()->getStatus()
						)
					)
			)
		;



		$result []=
			array (
				'Наименование' => 'Дата изменения статуса'
				,
				'Значение' => $this->getOrder()->getUpdatedAt()
			)
		;



		$result []=
			array (
				'Наименование' => 'Сайт'
				,
				'Значение' => $this->getOrder()->getStore()->getName()
			)
		;


		df_result_array ($result);

		return $result;
	}






	/**
	 * @return Mage_Sales_Model_Order
	 */
	private function getOrder () {
		return $this->cfg (self::PARAM__ORDER);
	}




	/**
	 * @return string
	 */
	private function getOrderComments () {

		/** @var array $comments */
		$comments = array ();

		foreach ($this->getOrder()->getAllStatusHistory() as $historyItem) {
			/** @var Mage_Sales_Model_Order_Status_History $historyItem */
			df_assert ($historyItem instanceof Mage_Sales_Model_Order_Status_History);

			if (!df_empty ($historyItem->getComment())) {

				$comments []=
					implode (
						"\r\n"
						,
						array (
							$historyItem->getCreatedAt()
							,
							$historyItem->getComment()
						)
					)
				;

			}
		}


		/** @var string $result  */
		$result =
			implode (
				"\r\n\r\n"
				,
				$comments
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Df_Varien_Simplexml_Element
	 */
	private function getSimpleXmlElement () {
		return $this->cfg (self::PARAM__SIMPLE_XML_ELEMENT);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__ORDER, 'Mage_Sales_Model_Order'
			)
			->validateClass (
				self::PARAM__SIMPLE_XML_ELEMENT, 'Df_Varien_Simplexml_Element'
			)
		;
	}



	const PARAM__ORDER = 'order';
	const PARAM__SIMPLE_XML_ELEMENT = 'simple_xml_element';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Export_Processor_Order';
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


