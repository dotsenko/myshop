<?php

class Df_1C_Model_Cml2_Registry_Export_Products extends Df_Varien_Data_Collection {



	/**
	 * @param int $productId
	 * @return Df_Catalog_Model_Product
	 */
	public function getProductById ($productId) {

		df_param_integer ($productId, 0);

		/** @var Df_Catalog_Model_Product $result  */
		$result = $this->getItemById ($productId);

		if (is_null ($result)) {

			$result =
				df_model (
					Df_Catalog_Model_Product::getNameInMagentoFormat()
					,
					array (
						Df_Catalog_Model_Product::PARAM__STORE_ID =>
							df_helper()->_1c()->cml2()->getStoreProcessed()->getId()
					)
				)
			;

			df_assert ($result instanceof Df_Catalog_Model_Product);

			$result->load ($productId);

			df_assert_between (intval ($result->getId()), 1);

			$this->addItem ($result);
		}

		df_assert ($result instanceof Df_Catalog_Model_Product);

		return $result;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Registry_Export_Products';
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
