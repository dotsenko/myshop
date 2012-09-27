<?php


class Df_1C_Helper_Cml2_Registry extends Mage_Core_Helper_Abstract {



	/**
	 * @return Df_1C_Model_Cml2_Registry_Attribute
	 */
	public function attribute () {

		/** @var Df_1C_Model_Cml2_Registry_Attribute $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Model_Cml2_Registry_Attribute $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Registry_Attribute::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_Registry_Attribute);

		}

		return $result;

	}




	/**
	 * @return Df_1C_Model_Cml2_Registry_Export
	 */
	public function export () {

		/** @var Df_1C_Model_Cml2_Registry_Export $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Model_Cml2_Registry_Export $result  */
			$result = df_model (Df_1C_Model_Cml2_Registry_Export::getNameInMagentoFormat());

			df_assert ($result instanceof Df_1C_Model_Cml2_Registry_Export);
		}

		return $result;
	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_PriceTypes
	 */
	public function getPriceTypes () {

		if (!isset ($this->_priceTypes)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_PriceTypes $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_PriceTypes::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_PriceTypes);

			$this->_priceTypes = $result;

		}


		df_assert ($this->_priceTypes instanceof Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_PriceTypes);

		return $this->_priceTypes;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_PriceTypes
	*/
	private $_priceTypes;

	
	
	
	
	/**
	 * @return Df_1C_Model_Cml2_Registry_Import
	 */
	public function import () {

		/** @var Df_1C_Model_Cml2_Registry_Import $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Model_Cml2_Registry_Import $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Registry_Import::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_Registry_Import);

		}

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Helper_Cml2_Registry';
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