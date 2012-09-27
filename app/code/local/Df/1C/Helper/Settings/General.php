<?php

class Df_1C_Helper_Settings_General extends Df_Core_Helper_Settings {


	/**
	 * @return array
	 */
	public function getCurrencyCodesMapFrom1CToMagento () {

		if (!isset ($this->_currencyCodesMapFrom1CToMagento)) {

			/** @var array $result  */
			$result = array ();

			/** @var string|null $mapSerialized  */
			$mapSerialized =
				Mage::getStoreConfig (
					'df_1c/general/non_standard_currency_codes'
					,
					df_helper()->_1c()->cml2()->getStoreProcessed()
				)
			;

			if (!is_null ($mapSerialized)) {

				df_assert_string ($mapSerialized);

				/** @var array $map */
				$map = @unserialize($mapSerialized);

				if (is_array ($map)) {

					foreach ($map as $mapItem) {

						/** @var array $mapItem */
						df_assert_array ($mapItem);

						/** @var string $nonStandardCode */
						$nonStandardCode =
							df_convert_null_to_empty_string (
								$mapItem [
									Df_1C_Block_System_Config_Form_Field_NonStandardCurrencyCodes
										::COLUMN__NON_STANDARD
								]
							)
						;

						df_assert_string ($nonStandardCode);


						/** @var string $standardCode */
						$standardCode =
							df_convert_null_to_empty_string (
								$mapItem [
									Df_1C_Block_System_Config_Form_Field_NonStandardCurrencyCodes
										::COLUMN__STANDARD
								]
							)
						;

						df_assert_string ($nonStandardCode);


						if (!df_empty($nonStandardCode) && !df_empty($standardCode)) {

							$nonStandardCode =
								df_helper()->_1c()->cml2()->normalizeNonStandardCurrencyCode (
									$nonStandardCode
								)
							;

							$result [$nonStandardCode] = $standardCode;

						}

					}
				}
			}

			df_assert_array ($result);

			$this->_currencyCodesMapFrom1CToMagento = $result;
		}


		df_result_array ($this->_currencyCodesMapFrom1CToMagento);

		return $this->_currencyCodesMapFrom1CToMagento;
	}


	/**
	* @var array
	*/
	private $_currencyCodesMapFrom1CToMagento;
	
	
	
	
	/**
	 * @return array
	 */
	public function getCurrencyCodesMapFromMagentoTo1C () {
	
		if (!isset ($this->_currencyCodesMapFromMagentoTo1C)) {
	
			/** @var array $result  */
			$result =
				array_flip (
					$this->getCurrencyCodesMapFrom1CToMagento()
				)
			;
	
			df_assert_array ($result);
	
			$this->_currencyCodesMapFromMagentoTo1C = $result;
		}
	
	
		df_result_array ($this->_currencyCodesMapFromMagentoTo1C);
	
		return $this->_currencyCodesMapFromMagentoTo1C;
	}
	
	
	/**
	* @var array
	*/
	private $_currencyCodesMapFromMagentoTo1C;	
	




	/**
	 * @return boolean
	 */
	public function isEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_1c/general/enabled'
				,
				df_helper()->_1c()->cml2()->getStoreProcessed()
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function needLogging () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_1c/general/enable_logging'
				,
				df_helper()->_1c()->cml2()->getStoreProcessed()
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Helper_Settings_General';
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