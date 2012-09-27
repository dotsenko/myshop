<?php

class Df_Sales_Helper_Guest extends Mage_Sales_Helper_Guest {

    /**
     * Get Breadcrumbs for current controller action
     *
     * @param  Mage_Core_Controller_Front_Action $controller
     */
    public function getBreadcrumbs($controller)
    {
        $breadcrumbs = $controller->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb(
            'home',
            array(
                'label' => Mage::helper('cms')->__('Home'),
                'title' => Mage::helper('cms')->__('Go to Home Page'),
                'link'  => Mage::getBaseUrl()
            )
        );
        $breadcrumbs->addCrumb(
            'cms_page',
            array(

				/**
				 * BEGIN PATCH
				 */

                'label' => df_mage()->salesHelper()->__ ('Order Information'),
                'title' => df_mage()->salesHelper()->__ ('Order Information')

				/**
				 * END PATCH
				 */
            )
        );
    }


}


