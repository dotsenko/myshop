<?php


class Df_Directory_Helper_Country extends Mage_Core_Helper_Abstract {




	/**
	 * @param string $iso2Code
	 * @return Mage_Directory_Model_Country
	 */
	public function getByIso2Code ($iso2Code) {

		df_param_string ($iso2Code, 0);

		if (!isset ($this->_byIso2Code [$iso2Code])) {

			/** @var Mage_Directory_Model_Country $result  */
			$result =
				Mage::getModel (Df_Directory_Const::COUNTRY_CLASS_MF)
			;

			df_assert ($result instanceof Mage_Directory_Model_Country);


			$result->loadByCode ($iso2Code);

			df_assert (!is_null ($result->getCountryId()));

			df_assert ($iso2Code === $result->getIso2Code());


			$this->_byIso2Code [$iso2Code] = $result;

		}


		df_assert ($this->_byIso2Code [$iso2Code] instanceof Mage_Directory_Model_Country);

		return $this->_byIso2Code [$iso2Code];

	}


	/**
	* @var array
	*/
	private $_byIso2Code = array ();




	/**
	 * @param Mage_Directory_Model_Country $country
	 * @param Zend_Locale $locale
	 * @return string
	 */
	public function getName (Mage_Directory_Model_Country $country, Zend_Locale $locale) {

		/** @var string $result  */
		$result =
			Mage::app()->getLocale()->getLocale()->getTranslation (
				$country->getId()
				,
				'country'
				,
				$locale
			)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @param Mage_Directory_Model_Country $country
	 * @return string
	 */
	public function getNameEnglish (Mage_Directory_Model_Country $country) {

		/** @var string $result  */
		$result =
			$this->getName (
				$country
				,
				df_helper()->directory()->getLocaleEnglish()
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return Mage_Directory_Model_Country
	 */
	public function getRussia () {

		/** @var Mage_Directory_Model_Country $result  */
		$result =
			$this->getByIso2Code (
				self::ISO_2_CODE__RUSSIA
			)
		;

		df_assert ($result instanceof Mage_Directory_Model_Country);

		return $result;

	}




	/**
	 * @return Df_Directory_Helper_Country_Russia
	 */
	public function russia () {

		/** @var Df_Directory_Helper_Country_Russia $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Directory_Helper_Country_Russia $result  */
			$result = Mage::helper (Df_Directory_Helper_Country_Russia::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Directory_Helper_Country_Russia);

		}

		return $result;

	}



	const ISO_2_CODE__RUSSIA = 'RU';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Helper_Country';
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