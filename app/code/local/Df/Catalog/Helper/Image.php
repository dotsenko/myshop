<?php

class Df_Catalog_Helper_Image extends Mage_Catalog_Helper_Image {


	/**
	 * @param  Mage_Catalog_Model_Product $product
	 * @return Df_Catalog_Helper_Image
	 */
    protected function setProduct (
		/**
		 * Мы не можем явно указать тип параметра $product,
		 * потому что иначе интерпретатор сделает нам замечание:
		 * «Strict Notice: Declaration of Df_Catalog_Helper_Image::setProduct()
		 * should be compatible with that of Mage_Catalog_Helper_Image::setProduct()»
		 */
		$product
	) {

		df_assert ($product instanceof Mage_Catalog_Model_Product);

	    parent::setProduct ($product);

		$model = $this->_getModel();
		if ($model instanceof Df_Catalog_Model_Product_Image) {
			/** @var Df_Catalog_Model_Product_Image $model */
			$model->setProductDf ($product);
		}
        return $this;
    }




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Image';
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