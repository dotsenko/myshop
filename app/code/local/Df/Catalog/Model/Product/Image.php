<?php

class Df_Catalog_Model_Product_Image extends Mage_Catalog_Model_Product_Image
{

	/**
	 * @return Df_Catalog_Model_Product_Image
	 */
    public function saveFile() {
	    parent::saveFile ();

	    if (df_enabled (Df_Core_Feature::SEO) && df_cfg()->seo()->images()->getAddExifToJpegs()) {
			Mage
				::getModel (
					Df_Seo_Model_Product_Gallery_Processor_Image_Exif::getNameInMagentoFormat()
					,
					array (
						"product" => $this->getProductDf ()
						,
						"imagePath" => $this->getNewFile()
					)
				)
					->process ()
			;
	    }


        return $this;
    }


	/**
	 * @return Mage_Catalog_Model_Product
	 */
    private function getProductDf () {
        return $this->_productDf;
    }



	/**
	 * @param  Mage_Catalog_Model_Product $product
	 * @return Df_Catalog_Helper_Image
	 */
    public function setProductDf (Mage_Catalog_Model_Product $product) {
		$this->_productDf = $product;
        return $this;
    }


	/**
	 * @var Mage_Catalog_Model_Product
	 */
	private $_productDf;


}
