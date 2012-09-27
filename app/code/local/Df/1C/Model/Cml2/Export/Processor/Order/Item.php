<?php

class Df_1C_Model_Cml2_Export_Processor_Order_Item extends Df_1C_Model_Cml2_Export_Processor {


	/**
	 * @return array
	 */
	public function getDocumentData () {

		if (!isset ($this->_documentData)) {

			/** @var array $result  */
			$result =
				array (
					'Ид' => $this->getProductExternalId()
					,
					'Наименование' => $this->getProductNameForExport()				
					//$this->getProduct()->getName()
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
							$this->getOrderItemExtended()->getPrice()
						)

					,
					'Количество' => $this->getOrderItemExtended()->getQtyOrdered()

					,
					'Сумма' =>
						df_helper()->_1c()->cml2()->formatMoney (
							/**
							 * getRowTotal — это без налогов и скидок
							 */
								$this->getOrderItemExtended()->getRowTotal()
							+
								$this->getOrderItemExtended()->getTaxAmount()
							-
								abs (
									$this->getOrderItemExtended()->getDiscountAmount()
								)
						)

					,
					'СтавкиНалогов' =>
						array (
							'СтавкаНалога' =>
								array (
									'Наименование' => 'НДС'
									,
									'Ставка' =>
										df_helper()->_1c()->cml2()->formatMoney (
												100
											*
												$this->getOrderItemExtended()->getTaxAmount()
											/
												$this->getOrderItemExtended()->getRowTotal()
										)
								)
						)

					,
					'Налоги' =>
						array (
							'Налог' =>
								array (
									'Наименование' => 'НДС'

									,
									'УчтеноВСумме' => $this->convertBooleanToString (true)

									,
									'Сумма' =>
										df_helper()->_1c()->cml2()->formatMoney (
											$this->getOrderItemExtended()->getTaxAmount()
										)
								)
						)

					,
					'Скидки' =>
						array (
							'Скидка' =>
								array (
									'Наименование' => 'Совокупная скидка'
									,
									'УчтеноВСумме' => $this->convertBooleanToString (true)
									,
									'Сумма' =>
										/**
										 * Magento хранит скидки в виде отрицательных чисел
										 */
										df_helper()->_1c()->cml2()->formatMoney (
											abs (
												$this->getOrderItemExtended()->getDiscountAmount()
											)
										)
								)
						)


					,
					'ЗначенияРеквизитов' =>
						array (
							'ЗначениеРеквизита' =>
								array (
									array (
										'Наименование' => 'ВидНоменклатуры'
										,
										'Значение' => $this->getAttributeSetName()
									)
									,
									array (
										'Наименование' => 'ТипНоменклатуры'
										,
										'Значение' => 'Товар'
									)
								)
						)

				)
			;

			df_assert_array ($result);


			if ($this->isProductCreatedInMagento ()) {

//				df_helper()->_1c()
//					->log (
//						'Товар создан в Magento: ' . $this->getProduct()->getName()
//					)
//				;

				/**
				 * Если товар был создан в Magento,
				 * а не импортирован ранее из 1С:Управление торговлей,
				 * то 1С:Управление торговлей импортирует его
				 * на основании указанной в документе-заказе информации.
				 *
				 * Поэтому постараемся здесь наиболее полно описать такие товары.
				 *
				 * К сожалению, 1С:Управление торговлей не воспринимает
				 * дополнительные характеристики таких товаров
				 * (например, мы не можем экспортировать описание товара)
				 */

				$result =
					array_merge_recursive (
						$result
						,
						array (
							     /*
							'Комментарий' =>
								implode (
									Df_Core_Const::T_NEW_LINE
									,
									array (
										implode (
											' = '
											,
											array (
												'Артикул'
												,
												$this->getProduct()->getSku()
											)
										)
										,
										implode (
											' = '
											,
											array (
												'№ строки заказа'
												,
												$this->getOrderItem()->getId()
											)
										)
									)
								)
                                    */
							/**
							'Описание' =>
								array (
									Df_Varien_Simplexml_Element::KEY__ATTRIBUTES =>
										array (
											'ФорматHTML' => $this->convertBooleanToString (true)
										)
									,
									Df_Varien_Simplexml_Element::KEY__VALUE =>
										Df_Varien_Simplexml_Element::markAsCData (
											$this->getProduct()->getDescription()
										)
								)

							,
							'ЗначенияРеквизитов' =>
								array (
									'ЗначениеРеквизита' =>
										array (
											array (
												'Наименование' => 'Описание'
												,
												'Значение' =>
													Df_Varien_Simplexml_Element::markAsCData (
														$this->getProduct()->getDescription()
													)
											)
										)
								)
							 **/
						)
					)
				;

			}

			$this->_documentData = $result;
		}


		df_result_array ($this->_documentData);

		return $this->_documentData;
	}


	/**
	* @var array
	*/
	private $_documentData;



	
	/**
	 * @return string
	 */
	private function getAttributeSetName () {
	
		if (!isset ($this->_attributeSetName)) {

			/** @var Mage_Eav_Model_Entity_Attribute_Set $attributeSet */
			$attributeSet = Mage::getModel('eav/entity_attribute_set');

			df_assert ($attributeSet instanceof Mage_Eav_Model_Entity_Attribute_Set);


			$attributeSet->load ($this->getProduct()->getAttributeSetId());

	
			/** @var string $result  */
			$result = $attributeSet->getAttributeSetName();
	
			df_assert_string ($result);
	
			$this->_attributeSetName = $result;
		}
	
		df_result_string ($this->_attributeSetName);
	
		return $this->_attributeSetName;
	}
	
	
	/**
	* @var string
	*/
	private $_attributeSetName;	
	




	/**
	 * @return string
	 */
	private function getItemId () {

		/** @var string $result  */
		$result =
			Df_Core_Const::T_EMPTY
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return Df_Catalog_Model_Product
	 */
	private function getProduct () {
	
		if (!isset ($this->_product)) {
	
			/** @var Df_Catalog_Model_Product $result  */
			$result = 	
				df_helper()->_1c()->cml2()->registry()->export()->getProducts()
					->getProductById(
						intval ($this->getOrderItem()->getProductId())
					)
			;
	
			df_assert ($result instanceof Df_Catalog_Model_Product);
	
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
	 * @return string
	 */
	private function getProductExternalId () {

		/** @var string $result  */
		$result =
			df_convert_null_to_empty_string (
				$this->getProduct()->getData (Df_1C_Const::ENTITY_1C_ID)
			)
		;

		/**
		 * У товара может отсутствовать внешний идентификатор, если товар был создан в Magento.
		 * В таком случае мы не назначаем товару внешний идентификатор,
		 * потому что 1С:Управление торговлей всё равно его проигнорирует
		 * и назначит свой идентификатор.
 		 */

		df_result_string ($result);

		return $result;
	}
	
	
	
	
	/**
	 * @return string
	 */
	private function getProductNameForExport () {
	
		if (!isset ($this->_productNameForExport)) {
	
			/** @var string $result  */
			$result = $this->getProduct()->getName();

			df_assert_string ($result);

			if ($this->isProductCreatedInMagento()) {

				/** @var array $productOptions  */
				$productOptions = $this->getOrderItem()->getProductOptions();

				df_assert_array ($productOptions);


				/** @var array $customOptions  */
				$customOptions = df_a ($productOptions, 'options', array ());

				df_assert_array ($customOptions);


				if (0 < count ($customOptions)) {

					/** @var array $customOptionsKeyValuePairsAsText */
					$customOptionsKeyValuePairsAsText = array ();

					foreach ($customOptions as $customOption) {
						/** @var array $customOption */
						df_assert_array ($customOption);

						/** @var string $label */
						$label = df_a ($customOption, 'label');

						df_assert_string ($label);


						/** @var string $value */
						$value = df_a ($customOption, 'value');

						df_assert_string ($value);


						$customOptionsKeyValuePairsAsText []=
							implode (
								' = '
								,
								array (
									$label
									,
									$value
								)
							)
						;
					}

					$result =
						sprintf (
							'%s {%s}'
							,
							$result
							,
							implode (
								', '
								,
								$customOptionsKeyValuePairsAsText
							)
						)
					;

				}

			}
	
			$this->_productNameForExport = $result;
		}
	
		df_result_string ($this->_productNameForExport);
	
		return $this->_productNameForExport;
	}
	
	
	/**
	* @var string
	*/
	private $_productNameForExport;	





	/**
	 * @return Mage_Sales_Model_Order_Item
	 */
	private function getOrderItem () {
		return $this->cfg (self::PARAM__ORDER_ITEM);
	}
	
	
	
	/**
	 * @return Df_Sales_Model_Order_Item_Extended
	 */
	private function getOrderItemExtended () {
	
		if (!isset ($this->_orderItemExtended)) {
	
			/** @var Df_Sales_Model_Order_Item_Extended $result  */
			$result = 	
				Df_Sales_Model_Order_Item_Extended::create (
					$this->getOrderItem()
				)
			;
	
			df_assert ($result instanceof Df_Sales_Model_Order_Item_Extended);
	
			$this->_orderItemExtended = $result;
		}
	
		df_assert ($this->_orderItemExtended instanceof Df_Sales_Model_Order_Item_Extended);
	
		return $this->_orderItemExtended;
	}
	
	
	/**
	* @var Df_Sales_Model_Order_Item_Extended
	*/
	private $_orderItemExtended;	
	
	
	
	
	/**
	 * @return bool
	 */
	private function isProductCreatedInMagento () {
	
		if (!isset ($this->_productCreatedInMagento)) {
	
			/** @var bool $result  */
			$result =
				df_empty ($this->getProductExternalId())
//				(
//						0
//					===
//						mb_strpos (
//							$this->getProductExternalId()
//							,
//							self::EXTERNAL_ID__PREFIX_MAGENTO
//						)
//				)
			;
	
			df_assert_boolean ($result);
	
			$this->_productCreatedInMagento = $result;
		}
	
		df_result_boolean ($this->_productCreatedInMagento);
	
		return $this->_productCreatedInMagento;
	}
	
	
	/**
	* @var bool
	*/
	private $_productCreatedInMagento;		




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__ORDER_ITEM, 'Mage_Sales_Model_Order_Item'
			)
		;
	}


	/**
	 * Значение 74 мы можем ставить без опаски, потому что 1С:Управление торговалей
	 * сама использует идентификаторы такой длины для вариантов настраиваемых товаров, например:
	 * b79b0fe2-c8a5-11e1-a928-4061868fc6eb#cb2b9d20-c97a-11e1-a928-4061868fc6eb
	 */
	const EXTERNAL_ID__MAX_LENGTH = 74;

	const EXTERNAL_ID__PREFIX_MAGENTO = 'magento';
	const PARAM__ORDER_ITEM = 'order_item';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Export_Processor_Order_Item';
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

