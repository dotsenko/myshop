<?php


class Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Country
	extends Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Dropdown {



	/**
	 * @return string
	 */
	public function getDropdownAsHtml () {

		/** @var string $result  */
		$result =
			$this->getDropdownAsBlock ()->toHtml()
		;

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string|null
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return mixed
	 */
	public function getValue () {

		/** @var mixed $result  */
		$result =
			$this->getAddress()->getAddress()->getCountryId()
		;

		if (is_null ($result)) {
			$result =
				/**
				 * Нельзя использовать df_mage()->coreHelper()->getDefaultCountry(),
				 * потому что метод Mage_Core_Helper_Data::getDefaultCountry
				 * отсутствует в Magento 1.4.0.1
				 */
				Mage::getStoreConfig (Mage_Core_Model_Locale::XML_PATH_DEFAULT_COUNTRY)
			;
		}

		return $result;

	}






	/**
	 * @return Mage_Directory_Model_Resource_Country_Collection|Mage_Directory_Model_Mysql4_Country_Collection
	 */
	private function getCountries () {

		if (!isset ($this->_countries)) {

			/** @var Mage_Directory_Model_Country $countrySingleton  */
			$countrySingleton = Mage::getSingleton('directory/country');

			df_assert ($countrySingleton instanceof Mage_Directory_Model_Country);

			/** @var Mage_Directory_Model_Resource_Country_Collection|Mage_Directory_Model_Mysql4_Country_Collection $result  */
			$result =
				$countrySingleton->getResourceCollection()
			;


			df_helper()->directory()->assert()->countryCollection ($result);


			$result->loadByStore();

			$this->_countries = $result;

		}

		df_helper()->directory()->assert()->countryCollection ($this->_countries);

		return $this->_countries;

	}


	/**
	* @var Mage_Directory_Model_Resource_Country_Collection|Mage_Directory_Model_Mysql4_Country_Collection
	*/
	private $_countries;




	/**
	 * @return array
	 */
	private function getCountriesAsOptions () {
	
		if (!isset ($this->_countriesAsOptions)) {

			/** @var array|null $result */
			$result = null;

			/** @var bool $useCache  */
			$useCache  = Mage::app()->useCache('config');

			/** @var string $cacheId  */
			$cacheId = 'DIRECTORY_COUNTRY_SELECT_STORE_' . Mage::app()->getStore()->getCode();

			if ($useCache) {

				/** @var string|false $resultFromCache  */
				$resultFromCache = Mage::app()->loadCache($cacheId);

				if ($resultFromCache) {
					$result = unserialize ($resultFromCache);
				}
			}


			if (!$result) {
				$result = $this->getCountries()->toOptionArray();
			}


			if ($useCache) {
				Mage::app()->saveCache(serialize($result), $cacheId, array('config'));
			}

	
			df_assert_array ($result);
	
			$this->_countriesAsOptions = $result;
	
		}
	
	
		df_result_array ($this->_countriesAsOptions);
	
		return $this->_countriesAsOptions;
	
	}
	
	
	/**
	* @var array
	*/
	private $_countriesAsOptions;	






	/**
	 * @return Mage_Core_Block_Html_Select
	 */
	private function getDropdownAsBlock () {

		if (!isset ($this->_dropdownAsBlock)) {

			/** @var Mage_Core_Block_Html_Select $result  */
			$result =
				df_block (
					'core/html_select'
					,
					null
					,
					array (
						'name' => $this->getDomName()
						,
						'id' => $this->getDomId()
						,
						'title' => $this->getLabel()
						,
						'class' => 'validate-select'
						,
						'value' => $this->getValue()
					)
				)
			;

			df_assert ($result instanceof Mage_Core_Block_Html_Select);


			$result->setOptions ($this->getCountriesAsOptions ());

			if (
					Df_Checkout_Block_Frontend_Ergonomic_Address::TYPE__SHIPPING
				===
					$this->getAddress()->getType()
			) {
				$result
					->setData (
						'extra_params'
						,
						'onchange="shipping.setSameAsBilling(false);"'
					)
				;
			}

			$this->_dropdownAsBlock = $result;

		}


		df_assert ($this->_dropdownAsBlock instanceof Mage_Core_Block_Html_Select);

		return $this->_dropdownAsBlock;

	}


	/**
	* @var Mage_Core_Block_Html_Select
	*/
	private $_dropdownAsBlock;





	const DEFAULT_TEMPLATE = 'df/checkout/ergonomic/address/field/country.phtml';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Country';
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


