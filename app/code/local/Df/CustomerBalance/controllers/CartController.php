<?php


/**
 * Customer balance controller for shopping cart
 *
 */
class Df_CustomerBalance_CartController extends Mage_Core_Controller_Front_Action
{
    /**
     * Only logged in users can use this functionality,
     * this function checks if user is logged in before all other actions
     *
     */
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    /**
     * Remove Store Credit from current quote
     *
     */
    public function removeAction()
    {
        if (!df_helper()->customer()->balance()->isEnabled()) {
            $this->_redirect('customer/account/');
            return;
        }

        $quote = Mage::getSingleton('checkout/session')->getQuote();
        if ($quote->getUseCustomerBalance()) {
            Mage::getSingleton('checkout/session')->addSuccess(
                $this->__('Store Credit payment was successfully removed from your shopping cart.')
            );
            $quote->setUseCustomerBalance(false)->collectTotals()->save();
        } else {
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Store Credit payment is not being used in your shopping cart.')
            );
        }

        $this->_redirect('checkout/cart');
    }
}
