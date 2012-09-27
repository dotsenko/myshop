<?php

class Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_Price
	extends Df_1C_Model_Cml2_Import_Data_Entity {



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
	 * @override
	 * @return string
	 */
	public function getExternalId () {

		/** @var string $result  */
		$result = $this->getEntityParam ('ИдТипаЦены');

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return float
	 */
	public function getPrice () {

		/** @var float $result  */
		$result =
			floatval (
				str_replace (
					',', '.', $this->getEntityParam ('ЦенаЗаЕдиницу')
				)
			)
		;

		df_result_float ($result);

		return $result;
	}




	/**
	 * @return float
	 */
	public function getPriceBase () {

		/** @var float $result  */
		$result =
			df_helper()->directory()->currency()->convertToBase (
				$this->getPrice()
				,
				$this->getCurrencyCode()
			)
		;

		df_result_float ($result);

		return $result;
	}





	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType
	 */
	public function getPriceType () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType $result  */
		$result =
			$this->getRegistry()->getPriceTypes()->findByExternalId (
				$this->getId ()
			)
		;

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_Price';
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

