<?php

class Df_Directory_Model_Finder_CallingCode extends Df_Core_Model_Abstract {
	
	
	/**
	 * @param Mage_Directory_Model_Country $country
	 * @return string
	 */
	public function getAlternativeByCountry (Mage_Directory_Model_Country $country) {
	
		if (!isset ($this->_alternativeByCountry [$country->getIso3Code()])) {
	
			/** @var string $result  */
			$result =
				df()->config()->getNodeValueAsString (
					df()->config()->getNodeByKey (
						$this->getAlternativeKeyByCountry (
							$country
						)
					)
				)
			;
	
			df_assert_string ($result);
	
			$this->_alternativeByCountry [$country->getIso3Code()] = $result;
	
		}
	
	
		df_result_string ($this->_alternativeByCountry [$country->getIso3Code()]);
	
		return $this->_alternativeByCountry [$country->getIso3Code()];
	
	}
	
	
	/**
	* @var array
	*/
	private $_alternativeByCountry = array ();		
	


	
	/**
	 * @param Mage_Directory_Model_Country $country
	 * @return string
	 */
	public function getByCountry (Mage_Directory_Model_Country $country) {
	
		if (!isset ($this->_byCountry [$country->getIso3Code()])) {
	
			/** @var string $result  */
			$result =
				df()->config()->getNodeValueAsString (
					df()->config()->getNodeByKey (
						$this->getKeyByCountry (
							$country
						)
					)
				)
			;
	
			df_assert_string ($result);
	
			$this->_byCountry [$country->getIso3Code()] = $result;
	
		}
	
	
		df_result_string ($this->_byCountry [$country->getIso3Code()]);
	
		return $this->_byCountry [$country->getIso3Code()];
	
	}
	
	
	/**
	* @var array
	*/
	private $_byCountry = array ();





	/**
	 * @param Mage_Directory_Model_Country $country
	 * @return string
	 */
	private function getAlternativeKeyByCountry (Mage_Directory_Model_Country $country) {

		/** @var string $result  */
		$result =
			df()->config()->implodeKey (
				array (
					self::KEY__BASE
					,
					$country->getIso3Code()
					,
					self::KEY__CALLING_CODE__ALTERNATIVE
				)
			)
		;

		df_result_string ($result);

		return $result;

	}






	/**
	 * @param Mage_Directory_Model_Country $country
	 * @return string
	 */
	private function getKeyByCountry (Mage_Directory_Model_Country $country) {

		/** @var string $result  */
		$result =
			df()->config()->implodeKey (
				array (
					self::KEY__BASE
					,
					$country->getIso3Code()
					,
					self::KEY__CALLING_CODE
				)
			)
		;

		df_result_string ($result);

		return $result;

	}


	



	const KEY__BASE = 'df/countries';
	const KEY__CALLING_CODE = 'calling-code';
	const KEY__CALLING_CODE__ALTERNATIVE = 'calling-code-alternative';
	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Model_Finder_CallingCode';
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


