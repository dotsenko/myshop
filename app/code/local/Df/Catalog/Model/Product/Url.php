<?php

class Df_Catalog_Model_Product_Url extends Mage_Catalog_Model_Product_Url {


	/**
	 * @param  string $str
	 * @return string
	 */
    public function formatUrlKey($str) {

        return
				(
						df_enabled (Df_Core_Feature::SEO)
					&&
						df_cfg()->seo()->common()->getEnhancedRussianTransliteration()
				)
			?
				df_helper()->catalog()->product()->url()->extendedFormat ($str)
			:
				parent::formatUrlKey ($str)
		;	    
    }


}