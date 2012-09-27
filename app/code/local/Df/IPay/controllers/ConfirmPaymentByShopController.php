<?php


class Df_IPay_ConfirmPaymentByShopController extends Mage_Core_Controller_Front_Action {

	/**
	 * @return void
	 */
    public function indexAction() {

		/** @var Df_IPay_Model_Action_ConfirmPaymentByShop $action */
		$action =
			df_model (
				Df_IPay_Model_Action_ConfirmPaymentByShop::getNameInMagentoFormat()
				,
				array (
					Df_IPay_Model_Action_ConfirmPaymentByShop::PARAM__CONTROLLER => $this
				)
			)
		;

		df_assert ($action instanceof Df_IPay_Model_Action_ConfirmPaymentByShop);


		$action->process();

    }
	

}



