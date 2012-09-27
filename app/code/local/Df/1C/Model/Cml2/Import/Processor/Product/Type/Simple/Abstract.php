<?php

abstract class Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple_Abstract
	extends Df_1C_Model_Cml2_Import_Processor_Product_Type {



	/**
	 * @override
	 * @return float
	 */
	protected function getPrice () {

		/** @var float $result  */
		$result =
				is_null ($this->getEntityOffer()->getPrices()->getMain())
			?
				0
			:
				$this->getEntityOffer()->getPrices()->getMain()->getPriceBase()
		;

		df_result_float ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	protected function getType () {
		return Mage_Catalog_Model_Product_Type::TYPE_SIMPLE;
	}




	/**
	 * @override
	 * @return int
	 */
	protected function getVisibility () {
		return Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple_Abstract';
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


