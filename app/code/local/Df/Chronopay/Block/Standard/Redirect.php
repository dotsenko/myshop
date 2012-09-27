<?php

class Df_Chronopay_Block_Standard_Redirect extends Df_Core_Block_Abstract
{

    protected function _toHtml()
    {
        $standard = df_model('df_chronopay/standard');
		/** @var Df_Chronopay_Model_Standard $standard  */

        $form = new Varien_Data_Form();
        $form->setAction($standard->getChronopayUrl())
            ->setId('chronopay_standard_checkout')
            ->setName('chronopay_standard_checkout')
            ->setMethod(Zend_Http_Client::POST)
            ->setUseContainer(true);
        foreach ($standard->setOrder($this->getOrder())->getStandardCheckoutFormFields() as $field => $value) {
            $form->addField($field, 'hidden', array('name' => $field, 'value' => $value));
        }
        $html = '<html><body>';
        $html.= $this->__('You will be redirected to ChronoPay in a few seconds.');
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("chronopay_standard_checkout").submit();</script>';
        $html.= '</body></html>';

        return $html;
    }
}