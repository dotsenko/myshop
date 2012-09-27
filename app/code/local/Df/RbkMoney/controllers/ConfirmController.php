<?php


class Df_RbkMoney_ConfirmController extends Mage_Core_Controller_Front_Action {

	/**
	 * Платёжная система присылает сюда подтверждение приёма оплаты от покупателя.
	 *
	 * @return void
	 */
    public function indexAction() {

		/** @var Df_RbkMoney_Model_Action_Confirm $action */
		$action =
			df_model (
				Df_RbkMoney_Model_Action_Confirm::getNameInMagentoFormat()
				,
				array (
					Df_RbkMoney_Model_Action_Confirm::PARAM__CONTROLLER => $this
				)
			)
		;

		df_assert ($action instanceof Df_RbkMoney_Model_Action_Confirm);


		$action->process();

    }



}



