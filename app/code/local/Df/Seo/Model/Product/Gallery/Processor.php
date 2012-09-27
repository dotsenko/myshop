<?php


class Df_Seo_Model_Product_Gallery_Processor extends Df_Core_Model_Abstract {






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Product_Gallery_Processor';
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
	 * @return Df_Seo_Model_Product_Gallery_Processor
	 */
	public function process () {
		$this->getProduct ()->load('media_gallery');

		$this
			->getProduct ()
			->setMediaGalleryAttribute (
				df_a (
					$this
						->getProduct ()
						->getTypeInstance(true)
						->getSetAttributes (
							$this->getProduct ()
						)
					,
					"media_gallery"
				)
			)
			->setImageKey (
				df_output()
					->transliterate (
						$this->getProduct ()->getName ()
					)
			)
		;

		foreach ($this->getProduct ()->getMediaGalleryImages() as $image) {
			Mage
				::getModel (
					"df_seo/product_gallery_processor_image"
					,
					array (
						"product" => $this->getProduct ()
						,
						"image" => $image
					)
				)
					->process ()
			;
		}
		$this->getProduct ()->save ();

		//$this->getProduct ()->getResource()->saveAttribute($this->getProduct (), 'media_gallery');

		return $this;
	}



	/**
	 * @return Mage_Catalog_Model_Product
	 */
	private function getProduct () {
		return $this->cfg (self::PARAM_PRODUCT);
	}


	const PARAM_PRODUCT = 'product';
	const PARAM_PRODUCT_TYPE = 'Mage_Catalog_Model_Product';



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (self::PARAM_PRODUCT, self::PARAM_PRODUCT_TYPE)
		;
	}


}