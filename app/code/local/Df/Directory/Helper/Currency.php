<?php


class Df_Directory_Helper_Currency extends Mage_Core_Helper_Abstract {




	/**
	 * @param float $amountInBaseCurrency
	 * @return float
	 */
	public function convertFromBaseToRoubles ($amountInBaseCurrency) {

		df_param_float ($amountInBaseCurrency, 0);

		/** @var float $result  */
		$result =
			Mage::app()->getStore()->getBaseCurrency()->convert (
				$amountInBaseCurrency
				,
				df_helper()->directory()->currency()->getRouble ()
			)
		;


		df_result_float ($result);

		return $result;

	}





	/**
	 * @param float $amountInRoubles
	 * @return float
	 */
	public function convertFromRoublesToBase ($amountInRoubles) {

		df_param_float ($amountInRoubles, 0);

		/** @var float $result  */
		$result =
			/**
			 * Обратите внимание, что перевод из одной валюты в другую
			 * надо осуществлять только в направлении "базовая валюта" => "второстепенная валюта",
			 * но не наоборот
			 * (Magento не умеет выполнять первод "второстепенная валюта" => "базовая валюта"
			 * даже при наличии курса "базовая валюта" => "второстепенная валюта",
			 * и возбуждает исключительную ситуацию).
			 */
			$amountInRoubles
			*
				(
						1
					/
						Mage::app()->getStore()->getBaseCurrency()->convert (
							doubleval (1)
							,
							df_helper()->directory()->currency()->getRouble ()
						)
				)
		;


		df_result_float ($result);

		return $result;

	}





	/**
	 * @param float $amountInCustomCurrency
	 * @param string|Mage_Directory_Model_Currency $customCurrency
	 * @return float
	 */
	public function convertToBase ($amountInCustomCurrency, $customCurrency) {

		df_param_float ($amountInCustomCurrency, 0);

		if (is_string ($customCurrency)) {

			/** @var string $customCurrencyCode  */
			$customCurrencyCode = $customCurrency;

			$customCurrency =
				df_model (
					Df_Directory_Const::CURRENCY_CLASS_MF
				)
			;

			df_assert ($customCurrency instanceof Mage_Directory_Model_Currency);

			$customCurrency->load ($customCurrencyCode);

		}

		/** @var float $result  */
		$result =
			/**
			 * Обратите внимание, что перевод из одной валюты в другую
			 * надо осуществлять только в направлении "базовая валюта" => "второстепенная валюта",
			 * но не наоборот
			 * (Magento не умеет выполнять первод "второстепенная валюта" => "базовая валюта"
			 * даже при наличии курса "базовая валюта" => "второстепенная валюта",
			 * и возбуждает исключительную ситуацию).
			 */
				$amountInCustomCurrency
			*
				(
						1
					/
						Mage::app()->getStore()->getBaseCurrency()->convert (
							doubleval (1)
							,
							$customCurrency
						)
				)
		;


		df_result_float ($result);

		return $result;

	}







	/**
	 * @return Mage_Directory_Model_Currency
	 */
    public function getBase () {

		if (!isset ($this->_base)) {

			/** @var Mage_Directory_Model_Currency $result  */
			$result =
				df_model(Df_Directory_Const::CURRENCY_CLASS_MF)
					->load(
						Mage::getStoreConfig (
							Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE
						)
					)
			;

			df_assert ($result instanceof Mage_Directory_Model_Currency);

			$this->_base = $result;
		}

		df_assert ($this->_base instanceof Mage_Directory_Model_Currency);

		return $this->_base;
    }


	/**
	 * @var Mage_Directory_Model_Currency
	 */
	private $_base;






	/**
	 * @return int
	 */
	public function getPrecision () {
		return
				(
						df_enabled(Df_Core_Feature::LOCALIZATION)
					&&
						df_area (
							df_cfg ()->localization ()->translation()->frontend()->needHideDecimals()
							,
							df_cfg ()->localization ()->translation()->admin()->needHideDecimals()
						)
				)
			?
				0
			:
				df_a (Mage::getSingleton ("core/locale")->getJsPriceFormat (), "requiredPrecision", 2)
		;
	}
	
	
	
	
	
	/**
	 * @return Mage_Directory_Model_Currency
	 */
    public function getRouble () {

		if (!isset ($this->_rouble)) {

			/** @var Mage_Directory_Model_Currency $result  */
			$result =
				df_model(Df_Directory_Const::CURRENCY_CLASS_MF)
					->load(
						Df_Directory_Const::CURRENCY__RUB
					)
			;

			df_assert ($result instanceof Mage_Directory_Model_Currency);

			$this->_rouble = $result;
		}

		df_assert ($this->_rouble instanceof Mage_Directory_Model_Currency);

		return $this->_rouble;
    }


	/**
	 * @var Mage_Directory_Model_Currency
	 */
	private $_rouble;	
	
	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Helper_Currency';
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