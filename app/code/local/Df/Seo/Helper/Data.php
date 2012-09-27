<?php


class Df_Seo_Helper_Data extends Mage_Core_Helper_Abstract {



	/**
	 * @return Df_Seo_Helper_Product_Image_Batch_Processor
	 */
	public function getProductImageBatchProcessor () {

		/** @var Df_Seo_Helper_Product_Image_Batch_Processor $result  */
		$result = Mage::helper (Df_Seo_Helper_Product_Image_Batch_Processor::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Seo_Helper_Product_Image_Batch_Processor);

		return $result;

	}




	/**
	 * @return Df_Seo_Helper_Product_Image_Renamer
	 */
	public function getProductImageRenamer () {

		/** @var Df_Seo_Helper_Product_Image_Renamer $result  */
		$result = Mage::helper (Df_Seo_Helper_Product_Image_Renamer::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Seo_Helper_Product_Image_Renamer);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Helper_Data';
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