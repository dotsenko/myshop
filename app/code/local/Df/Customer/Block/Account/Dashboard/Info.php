<?php

class Df_Customer_Block_Account_Dashboard_Info extends Mage_Customer_Block_Account_Dashboard_Info {


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
     *  Newsletter module availability
     *
     *  @return	  boolean
     */
    public function isNewsletterEnabled()
    {
        return
				parent::isNewsletterEnabled()
			&&
				!(
						df_module_enabled (Df_Core_Module::TWEAKS)
					&&
						df_enabled (Df_Core_Feature::TWEAKS)
					&&
						df_cfg()->tweaks()->account ()->getRemoveSectionNewsletterSubscriptions ()
				)
		;
    }


}