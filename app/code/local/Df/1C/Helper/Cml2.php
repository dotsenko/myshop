<?php


class Df_1C_Helper_Cml2 extends Mage_Core_Helper_Abstract {



	/**
	 * @return Df_1C_Helper_Cml2_AttributeSet
	 */
	public function attributeSet () {

		/** @var Df_1C_Helper_Cml2_AttributeSet $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Helper_Cml2_AttributeSet $result  */
			$result = Mage::helper (Df_1C_Helper_Cml2_AttributeSet::getNameInMagentoFormat());

			df_assert ($result instanceof Df_1C_Helper_Cml2_AttributeSet);
		}

		return $result;
	}



	/**
	 * @param string $currencyCodeInMagentoFormat
	 * @return string
	 */
	public function convertCurrencyCodeTo1CFormat ($currencyCodeInMagentoFormat) {

		df_assert_string ($currencyCodeInMagentoFormat, 0);

		$result =
			df_a (
				df_cfg()->_1c()->general()->getCurrencyCodesMapFromMagentoTo1C()
				,
				$currencyCodeInMagentoFormat
				,
				$currencyCodeInMagentoFormat
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @param string $currencyCodeIn1CFormat
	 * @return string
	 */
	public function convertCurrencyCodeToMagentoFormat ($currencyCodeIn1CFormat) {

		df_assert_string ($currencyCodeIn1CFormat, 0);

		/** @var $codeNormalized $result  */
		$codeNormalized =
			df_helper()->_1c()->cml2()->normalizeNonStandardCurrencyCode (
				$currencyCodeIn1CFormat
			)
		;

		df_assert_string ($codeNormalized);

		$result =
			df_a (
				array_merge (
					array (
						'РУБ' => 'RUB'
						,
						'ГРН' => 'UAH'
					)
					,
					df_cfg()->_1c()->general()->getCurrencyCodesMapFrom1CToMagento()
				)
				,
				$codeNormalized
				,
				$codeNormalized
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @param string|float $money
	 * @return string
	 */
	public function formatMoney ($money) {

		/** @var string $result  */
		$result =
			sprintf (
				floatval ($money)
				,
				'.2f'
			)
		;

		df_result_string ($result);

		return $result;
	}

	
	
	
	
	/**
	 * @return Mage_Core_Model_Store
	 */
	public function getStoreProcessed () {
	
		if (!isset ($this->_storeProcessed)) {
	
			/** @var Mage_Core_Model_Store $result  */
			$result = null;

			if (Mage::app()->isSingleStoreMode()) {
				$result = Mage::app()->getStore(true);
			}

			else {

				/**
				 * Если в системе присутствует больше одного магазина,
				 * то администратор должен указать обрабатываемый магазин
				 * параметром в запрашиваемом адресе, например:
				 *
				 * http://localhost.com:686/df-1c/cml2/index/store-view/store_686/
				 */

				/** @var string $pattern */
				$pattern = '#\/store\-view\/([^\/]+)\/#u';

				/** @var array $matches */
				$matches = array ();


				/** @var int|bool $matchingResult  */
				$matchingResult =
					preg_match (
						$pattern
						,
						Mage::app()->getRequest()->getRequestUri()
						,
						$matches
					)
				;

				if (1 !== $matchingResult) {

					df_error (
'Ваша система содержит несколько витрин,
поэтому Вы должны указать системное имя обрабатываемой витрины
в запрашиваемом из «1С: Управление торговлей» адресе:
http://example.ru/df-1c/cml2/index/store-view/<системное имя витрины>/'
					);
				}


				/** @var string $storeCode */
				$storeCode = df_a ($matches, 1);

				df_assert_string ($storeCode);


				try {
					$result = Mage::app()->getStore($storeCode);
				}
				catch (Mage_Core_Model_Store_Exception $e) {
					df_error (
						sprintf (
							'Витрина с системным именем «%s» отсутствует в Вашей системе.'
							,
							$storeCode
						)
					);
				}


			}
	
	
			df_assert ($result instanceof Mage_Core_Model_Store);
	
			$this->_storeProcessed = $result;
	
		}
	
	
		df_assert ($this->_storeProcessed instanceof Mage_Core_Model_Store);
	
		return $this->_storeProcessed;
	
	}
	
	
	/**
	* @var Mage_Core_Model_Store
	*/
	private $_storeProcessed;	
	
	





	/**
	 * @return Df_1C_Helper_Cml2_Registry
	 */
	public function registry () {

		/** @var Df_1C_Helper_Cml2_Registry $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Helper_Cml2_Registry $result  */
			$result = Mage::helper (Df_1C_Helper_Cml2_Registry::getNameInMagentoFormat());

			df_assert ($result instanceof Df_1C_Helper_Cml2_Registry);

		}

		return $result;
	}




	/**
	 * @param string $nonStandardCurrencyCode
	 * @return string
	 */
	public function normalizeNonStandardCurrencyCode ($nonStandardCurrencyCode) {

		df_param_string ($nonStandardCurrencyCode, 0);

		/** @var string $result  */
		$result =
			mb_substr (
				df_trim (
					mb_strtoupper (
						$nonStandardCurrencyCode
					)
					,
					' .'
				)
				,
				0
				,
				3
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @param string $path
	 * @param string $value
	 * @return Df_1C_Helper_Cml2
	 */
	public function setStoreProcessedConfigValue ($path, $value) {

		Mage::getConfig()
			->saveConfig (
				$path
				,
				$value
				,
				$scope = 'stores'
				,
				$scopeId = $this->getStoreProcessed()->getId()
			)
		;

		Mage::app()->getStore()
			->setConfig (
				$path
				,
				$value
			)
		;

		return $this;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Helper_Cml2';
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