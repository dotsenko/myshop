<?php

class Df_Reward_CartController extends Mage_Core_Controller_Front_Action
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
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    /**
     * Remove Reward Points payment from current quote
     *
     */
    public function removeAction()
    {
        if (!df_helper()->reward()->isEnabledOnFront()
            || !df_helper()->reward()->getHasRates()) {
            return $this->_redirect('customer/account/');
        }

        $quote = Mage::getSingleton('checkout/session')->getQuote();

        if ($quote->getUseRewardPoints()) {
            $quote->setUseRewardPoints(false)->collectTotals()->save();
            Mage::getSingleton('checkout/session')->addSuccess(
                $this->__('Reward Points were successfully removed from your order.')
            );
        } else {
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Reward Points will not be used in this order.')
            );
        }

        $this->_redirect('checkout/cart');
    }
}
