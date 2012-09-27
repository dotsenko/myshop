<?php

class Df_Checkout_Block_Frontend_Ergonomic_Address_Type_Shipping extends Mage_Checkout_Block_Onepage_Shipping {



	/**
	 * @override
	 * @return bool
	 */
	public function customerHasAddresses() {

		/** @var bool $result  */
		$result =
			0 !== count ($this->getAddresses())
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @override
	 * @param string $type
	 * @return string
	 */
	public function getAddressesHtmlSelect ($type) {

		if ($this->isCustomerLoggedIn()) {
			$options = array();
			foreach (
				/**
				 * BEGIN PATCH
				 */
				$this->getAddresses()

				/**
				 * END PATCH
				 */

				as $address) {
				$options[] = array(
					'value' => $address->getId(),
					'label' => $address->format('oneline')
				);
			}

			$addressId = $this->getAddress()->getCustomerAddressId();
			if (empty($addressId)) {
				if ($type=='billing') {
					$address = $this->getCustomer()->getPrimaryBillingAddress();
				} else {
					$address = $this->getCustomer()->getPrimaryShippingAddress();
				}
				if ($address) {
					$isAllowed = false;

					foreach ($this->getAddresses() as $allowedAddress) {

						if ($allowedAddress->getId () === $address->getId ()) {
							$isAllowed = true;
							break;
						}
					}

					if (!$isAllowed) {
						$address = df_a ($this->getAddresses(), 0);
					}

					if ($address) {
						$addressId = $address->getId();
					}
				}
			}

			$select = $this->getLayout()->createBlock('core/html_select')
				->setName($type.'_address_id')
				->setId($type.'-address-select')
				->setClass('address-select')
				->setExtraParams('onchange="'.$type.'.newAddress(!this.value)"')
				->setValue($addressId)
				->setOptions($options);

			$select->addOption('', Mage::helper('checkout')->__('New Address'));

			return $select->getHtml();
		}
		return '';

	}




	
	
	/**
	 * @return array
	 */
	private function getAddresses () {
	
		if (!isset ($this->_addresses)) {
	
			/** @var array $result  */
			$result = $this->getCustomer()->getAddresses();

			if (
				df_cfg()->checkout()->_interface()->needShowAllStepsAtOnce()
			) {

				/** @var Df_Checkout_Model_Filter_Ergonomic_Address $filter  */
				$filter =
					df_model (
						Df_Checkout_Model_Filter_Ergonomic_Address::getNameInMagentoFormat()
						,
						array (
							Df_Checkout_Model_Filter_Ergonomic_Address
								::PARAM__ADDRESS_TYPE => 'shipping'
						)
					)
				;

				df_assert ($filter instanceof Df_Checkout_Model_Filter_Ergonomic_Address);

				$result = $filter->filter ($result);

			}
	
			df_assert_array ($result);
	
			$this->_addresses = $result;
	
		}
	
	
		df_result_array ($this->_addresses);
	
		return $this->_addresses;
	
	}
	
	
	/**
	* @var array
	*/
	private $_addresses;




	/**
	 * @param array $addresses
	 * @return array
	 */
	private function filterAddresses (array $addresses) {

		/** @var array $result  */
		$result =
			$addresses
		;


		df_result_array ($result);

		return $result;

	}


	
	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address_Type_Shipping';
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


