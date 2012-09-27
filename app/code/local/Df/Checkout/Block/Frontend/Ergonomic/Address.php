<?php


class Df_Checkout_Block_Frontend_Ergonomic_Address extends Df_Core_Block_Abstract {




	/**
	 * @return Mage_Sales_Model_Quote_Address
	 */
	public function getAddress () {

		if (!isset ($this->_address)) {

			/** @var Mage_Sales_Model_Quote_Address $result  */
			$result =
					df_mage()->customer()->isLoggedIn()
				?
					(
							(self::TYPE__BILLING === $this->getType())
						?
							df_mage()->checkout()->sessionSingleton()->getQuote()
								->getBillingAddress()
						:
							df_mage()->checkout()->sessionSingleton()->getQuote()
								->getShippingAddress()
					)

				:
					Mage::getModel('sales/quote_address')
			;


			df_assert ($result instanceof Mage_Sales_Model_Quote_Address);

			$this->_address = $result;

		}


		df_assert ($this->_address instanceof Mage_Sales_Model_Quote_Address);

		return $this->_address;

	}


	/**
	* @var Mage_Sales_Model_Quote_Address
	*/
	private $_address;





	/**
	 * Этот метод публичен, потому что его использует
	 * класс Df_Checkout_Block_Frontend_Ergonomic_Address_Field
	 * @return string
	 */
	public function getType () {

		/** @var string $result  */
		$result = $this->getData (self::PARAM__TYPE);

		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	protected function _toHtml () {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;

		try {

			$result =
				implode (
					"\n"
					,
					$this->getRows ()->walk ('toHtml')
				)
			;

			df_result_string ($result);

		}
		catch (Exception $e) {
			df_handle_entry_point_exception ($e, true);
		}

		return $result;

	}
	
	
	
	
	
	/**
	 * @return Df_Checkout_Model_Collection_Ergonomic_Address_Field
	 */
	private function getFields () {
	
		if (!isset ($this->_fields)) {
	
			/** @var Df_Checkout_Model_Collection_Ergonomic_Address_Field $result  */
			$result = 
				df_model (
					Df_Checkout_Model_Collection_Ergonomic_Address_Field
						::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Checkout_Model_Collection_Ergonomic_Address_Field);


			/** @var int $orderingInConfig  */
			$orderingInConfig = 1;

			foreach ($this->getFieldsConfig()->getNode()->asCanonicalArray() as $fieldType => $fieldConfig) {

				/** @var string $fieldType */
				df_assert_string ($fieldType);

				/** @var array $fieldConfig */
				df_assert_array ($fieldConfig);


				/** @var Df_Checkout_Block_Frontend_Ergonomic_Address_Field $block  */
				$block = null;

				try {

					$block =
						df_block (
							df_a ($fieldConfig, 'block')
							,
							null
							,
							array_merge (
								$fieldConfig
								,
								array (
									Df_Checkout_Block_Frontend_Ergonomic_Address_Field
										::PARAM__TYPE => $fieldType
									,
									Df_Checkout_Block_Frontend_Ergonomic_Address_Field
										::PARAM__ADDRESS => $this

									,
									Df_Checkout_Block_Frontend_Ergonomic_Address_Field
										::PARAM__ORDERING_IN_CONFIG => $orderingInConfig++
								)
							)

						)
					;

				}
				catch (Exception $e) {

					df_error (
						sprintf (
							'Не найден класс блока: %s'
							,
							df_a ($fieldConfig, 'block')
						)
					);

				}

				$result
					->addItem(
						$block
					)
				;


			}


			$result =
				$this->getFilter()->filter (
					$result
				)
			;


	
			$this->_fields = $result;
	
		}
	
	
		df_assert ($this->_fields instanceof Df_Checkout_Model_Collection_Ergonomic_Address_Field);
	
		return $this->_fields;
	
	}
	
	
	/**
	* @var Df_Checkout_Model_Collection_Ergonomic_Address_Field
	*/
	private $_fields;		
	





	/**
	 * @return Df_Checkout_Model_Config_Query_Ergonomic_Address_Fields
	 */
	private function getFieldsConfig () {

		if (!isset ($this->_fieldsConfig)) {

			/** @var Df_Checkout_Model_Config_Query_Ergonomic_Address_Fields $result  */
			$result =
				df_model (
					Df_Checkout_Model_Config_Query_Ergonomic_Address_Fields::getNameInMagentoFormat()
					,
					array (
						Df_Checkout_Model_Config_Query_Ergonomic_Address_Fields
							::PARAM__ADDRESS_TYPE => $this->getType()
					)
				)
			;

			df_assert ($result instanceof Df_Checkout_Model_Config_Query_Ergonomic_Address_Fields);

			$this->_fieldsConfig = $result;

		}


		df_assert ($this->_fieldsConfig instanceof Df_Checkout_Model_Config_Query_Ergonomic_Address_Fields);

		return $this->_fieldsConfig;

	}


	/**
	* @var Df_Checkout_Model_Config_Query_Ergonomic_Address_Fields
	*/
	private $_fieldsConfig;





	/**
	 * @return Zend_Filter
	 */
	private function getFilter () {

		if (!isset ($this->_filter)) {

			/** @var Zend_Filter $result  */
			$result = new Zend_Filter ();


			$result
				->addFilter (
					df_zf_filter (
						Df_Checkout_Model_Filter_Ergonomic_Address_Field_Collection_ByVisibility
							::getNameInMagentoFormat()
					)
				)
				->addFilter (
					df_zf_filter (
						Df_Checkout_Model_Filter_Ergonomic_Address_Field_Collection_Order_ByWeight
							::getNameInMagentoFormat()
					)
				)
			;


			df_assert ($result instanceof Zend_Filter);

			$this->_filter = $result;

		}


		df_assert ($this->_filter instanceof Zend_Filter);

		return $this->_filter;

	}


	/**
	* @var Zend_Filter
	*/
	private $_filter;



	
	
	
	/**
	 * @return Df_Checkout_Model_Collection_Ergonomic_Address_Row
	 */
	private function getRows () {
	
		if (!isset ($this->_rows)) {
	
			/** @var Df_Checkout_Model_Collection_Ergonomic_Address_Row $result  */
			$result = 
				df_model (
					Df_Checkout_Model_Collection_Ergonomic_Address_Row
						::getNameInMagentoFormat()
				)
			;
	
			df_assert ($result instanceof Df_Checkout_Model_Collection_Ergonomic_Address_Row);




			/** @var int|null $previousWeight  */
			$previousWeight = null;

			/** @var Df_Checkout_Block_Frontend_Ergonomic_Address_Row $currentRow  */
			$currentRow = null;


			foreach ($this->getFields () as $field) {

				/** @var Df_Checkout_Block_Frontend_Ergonomic_Address_Field $field */
				df_assert ($field instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Field);

				if (

						is_null ($previousWeight)


					||

						 /**
						  * Мы пользуемся тем обстоятельством, что поля уже упорядочены по весу.
						  * Поэтому, если вес предыдущего и текущего полей различен — это значит,
						  * что для нового поля нужно добавить новую строку.
						  */
						($previousWeight != $field->getOrderingWeight ())
				) {


					/** @var Df_Checkout_Block_Frontend_Ergonomic_Address_Row $row  */
					$row =
						df_block (
							Df_Checkout_Block_Frontend_Ergonomic_Address_Row::getNameInMagentoFormat()
						)
					;

					df_assert ($row instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Row);

					$row->getFields()->addItem($field);

					$result->addItem ($row);

					$currentRow = $row;

				}


				else {

					df_assert ($currentRow instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Row);

					$currentRow->getFields()->addItem($field);


				}


				$previousWeight = $field->getOrderingWeight ();

			}




	
			$this->_rows = $result;
	
		}
	
	
		df_assert ($this->_rows instanceof Df_Checkout_Model_Collection_Ergonomic_Address_Row);
	
		return $this->_rows;
	
	}
	
	
	/**
	* @var Df_Checkout_Model_Collection_Ergonomic_Address_Row
	*/
	private $_rows;	
	
	




	const PARAM__TYPE = 'type';

	const TYPE__BILLING = 'billing';
	const TYPE__SHIPPING = 'shipping';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address';
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


