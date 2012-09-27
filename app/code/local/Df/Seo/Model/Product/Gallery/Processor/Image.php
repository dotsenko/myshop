<?php


class Df_Seo_Model_Product_Gallery_Processor_Image extends Df_Core_Model_Abstract {





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Product_Gallery_Processor_Image';
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










	/**
	 * @return Df_Seo_Model_Product_Gallery_Processor_Image
	 */
	public function process () {
		// Path may be changed due to renaming

		$imagePath =
			Mage
				::getModel (
					"df_seo/product_gallery_processor_image_renamer"
					,
					array (
						"product" => $this->getProduct ()
						,
						"image" => $this->getImage ()
					)
				)
					->process ()
		;

		Mage
			::getModel (
				Df_Seo_Model_Product_Gallery_Processor_Image_Exif::getNameInMagentoFormat()
				,
				array (
					"product" => $this->getProduct ()
					,
					"imagePath" => $imagePath
				)
			)
				->process ()
		;


		return $this;
	}



	/**
	 * @return Varien_Object
	 */
	private function getImage () {
		return $this->cfg (self::PARAM_IMAGE);
	}


	/**
	 * @return Mage_Catalog_Model_Product
	 */
	private function getProduct () {
		return $this->cfg (self::PARAM_PRODUCT);
	}


	const PARAM_IMAGE = 'image';
	const PARAM_IMAGE_TYPE = 'Varien_Object';

	const PARAM_PRODUCT = 'product';
	const PARAM_PRODUCT_TYPE = 'Mage_Catalog_Model_Product';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (self::PARAM_IMAGE, self::PARAM_IMAGE_TYPE)
	        ->validateClass (self::PARAM_PRODUCT, self::PARAM_PRODUCT_TYPE)
		;
	}

}