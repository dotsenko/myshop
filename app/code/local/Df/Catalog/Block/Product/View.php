<?php

class Df_Catalog_Block_Product_View extends Mage_Catalog_Block_Product_View {


    /**
     * @return string
     */
    public function __ () {

		/** @var array $args  */
        $args = func_get_args();

		df_assert_array ($args);


		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByParent ($args, $this)
		;

		df_result_string ($result);


	    return $result;
    }



    /**
     * Check if product can be emailed to friend
     *
     * @return bool
     */
    public function canEmailToFriend ()
    {
        $result = parent::canEmailToFriend ();

		if (
				df_module_enabled (Df_Core_Module::TWEAKS)
			&&
				df_enabled (Df_Core_Feature::TWEAKS)
			&&
				df_handle_presents (Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
			&&
				df_cfg()->tweaks()->catalog()->product()->view()->getHideEmailToFriend()
		) {
			$result = false;
		}

	    return $result;
    }





	/**
	 * @var bool
	 */
	private $_dfProductPrepared = false;


    /**
     * Retrieve current product model
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        $result = parent::getProduct ();

	    if (!$this->_dfProductPrepared) {

		    if (
					df_module_enabled (Df_Core_Module::TWEAKS)
				&&
					df_enabled (Df_Core_Feature::TWEAKS)
				&&
					df_handle_presents (Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
				&&
					df_cfg()->tweaks()->catalog()->product()->view()->getHideShortDescription()
			) {
				$result->unsetData ('short_description');
		    }

			$this->_dfProductPrepared = true;
	    }

	    return $result;
    }

}
