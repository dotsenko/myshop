<?php

class Df_1C_Cml2Controller extends Mage_Core_Controller_Front_Action {


	/**
	 * @return void
	 */
    public function indexAction() {

		/**
		 * Обратите внимание, что проверку на наличие и доступности лицензии
		 * мы выполняем не здесь, а в классе Df_1C_Model_Cml2_Action,
		 * потому что данные проверки должны при необходимости возбуждать исключительные ситуации,
		 * и именно в том классе расположен блок try... catch, который обрабатывает их
		 * надлежащим для 1C: Управление торговлей способом
		 * (возвращает диагностическое сообщение в 1C: Управление торговлей
		 * по стандарту CommerceML 2)
		 */

		/** @var Df_1C_Model_Cml2_Action_Front $action */
		$action =
			df_model (
				Df_1C_Model_Cml2_Action_Front::getNameInMagentoFormat()
				,
				array (
					Df_1C_Model_Cml2_Action_Front::PARAM__CONTROLLER => $this
				)
			)
		;

		df_assert ($action instanceof Df_1C_Model_Cml2_Action_Front);


		$action->process();

    }


}


