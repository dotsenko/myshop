<?php


class Df_IPay_GetPaymentAmountController extends Mage_Core_Controller_Front_Action {

	/**
	 * @return void
	 */
    public function indexAction() {

		/** @var Df_IPay_Model_Action_GetPaymentAmount $action */
		$action =
			df_model (
				Df_IPay_Model_Action_GetPaymentAmount::getNameInMagentoFormat()
				,
				array (
					Df_IPay_Model_Action_GetPaymentAmount::PARAM__CONTROLLER => $this
				)
			)
		;

		df_assert ($action instanceof Df_IPay_Model_Action_GetPaymentAmount);


		$action->process();

    }
	

}



