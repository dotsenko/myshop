<?php

class Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType
	extends Df_1C_Model_Cml2_Import_Data_Entity {



	/**
	 * @todo Надо учитывать НДС
	 *
	 * @param float $originalPrice
	 * @return float
	 */
	public function convertPriceToBase ($originalPrice) {

		df_param_float ($originalPrice, 0);

		/** @var float $result  */
		$result =
			df_helper()->directory()->currency()->convertToBase (
				$originalPrice
				,
				$this->getCurrencyCode()
			)
		;

		df_result_float ($result);

		return $result;

	}


	
	
	/**
	 * @return string
	 */
	public function getCurrencyCode () {
	
		/** @var string $result  */
		$result =
			df_helper()->_1c()->cml2()->convertCurrencyCodeToMagentoFormat (
				$this->getEntityParam ('Валюта')
			)
		;

		df_result_string ($result);
	
		return $result;
	
	}

	
	
	
	/**
	 * @return bool
	 */
	public function isVatIncluded () {
	
		if (!isset ($this->_vatIncluded)) {

			/** @var bool $result  */
			$result = false;


			/** @var array $taxSettings  */
			$taxSettings = $this->getEntityParam ('Налог');

			df_assert_array ($taxSettings);


			/** @var string $taxName  */
			$taxName = df_a ($taxSettings, 'Наименование');

			df_assert_string ($taxName);


			if ('НДС' === $taxName) {

				/** @var string $isIncluded  */
				$isIncluded = df_a ($taxSettings, 'УчтеноВСумме');

				df_assert_string ($isIncluded);


				$result = ('true' === $isIncluded);

			}

	
			df_assert_boolean ($result);
	
			$this->_vatIncluded = $result;
	
		}
	
	
		df_result_boolean ($this->_vatIncluded);
	
		return $this->_vatIncluded;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_vatIncluded;		





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType';
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

