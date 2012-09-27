<?php

abstract class Df_1C_Model_Cml2_Import_Processor_Product extends Df_1C_Model_Cml2_Import_Processor {



	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Offer|Df_1C_Model_Cml2_Import_Data_Entity
	 */
	protected function getEntityOffer () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $result */
		$result = $this->getEntity();

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);

		return $result;
	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Product|Df_1C_Model_Cml2_Import_Data_Entity
	 */
	protected function getEntityProduct () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_Product $result */
		$result = $this->getEntityOffer()->getEntityProduct();

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_Product);

		return $result;
	}




	/**
	 * @return Mage_Catalog_Model_Product|null
	 */
	protected function getExistingMagentoProduct () {

		/** @var Mage_Catalog_Model_Product|null $result  */
		$result =
			df_helper()->dataflow()->registry()->products()->findByExternalId (
				$this->getEntityOffer()->getExternalId()
			)
		;

		if (!is_null ($result)) {
			df_assert ($result instanceof Mage_Catalog_Model_Product);
		}

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Product';
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


