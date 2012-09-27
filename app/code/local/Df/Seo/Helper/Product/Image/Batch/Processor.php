<?php


class Df_Seo_Helper_Product_Image_Batch_Processor extends Mage_Core_Helper_Abstract {


	/**
	 * @return Df_Seo_Helper_Product_Image_Batch_Processor
	 */
	public function process () {

		$collection =
			df_model('catalog/product')
				->getCollection ()
				->addAttributeToSelect('*')

				// Преобразуем картинки только для тех доменов,
				// для которых данная функция лицензирована
				->addWebsiteFilter (
					df_feature (Df_Core_Feature::SEO)->getWebsiteIds ()
				)
				->load ()
		;
		/** @var Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection */

		df_helper()->catalog()->assert()->productCollection ($collection);


		try {
			foreach ($collection as $product) {
				Mage
					::getModel (
						"df_seo/product_gallery_processor"
						,
						array ("product" => $product)
					)
						->process ()
				;
			}
		}
		catch (Exception $e) {
			df_handle_entry_point_exception ($e, false);
		}


		return $this;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Helper_Product_Image_Batch_Processor';
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