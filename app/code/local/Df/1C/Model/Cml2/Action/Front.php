<?php

class Df_1C_Model_Cml2_Action_Front extends Df_1C_Model_Cml2_Action {


	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Action
	 */
	protected function processInternal () {

		if (
				Df_1C_Model_Cml2_InputRequest_Generic::MODE__CHECK_AUTH
			===
				$this->getRmRequest()->getMode()
		) {
			$this->action_login();
		}
		else {
			$this->checkLoggedIn();

			if (
					Df_1C_Model_Cml2_InputRequest_Generic::MODE__INIT
				===
					$this->getRmRequest()->getMode()
			) {
				$this->action_init ();
			}
			else {

				if (
						Df_1C_Model_Cml2_InputRequest_Generic::TYPE__CATALOG
					===
						$this->getRmRequest()->getType()
				) {

					switch ($this->getRmRequest()->getMode()) {
						case Df_1C_Model_Cml2_InputRequest_Generic::MODE__FILE:
							$this->action_catalogUpload ();
							break;

						case Df_1C_Model_Cml2_InputRequest_Generic::MODE__IMPORT:
							$this->action_catalogImport ();
							break;
					}

				}

				else if (
						Df_1C_Model_Cml2_InputRequest_Generic::TYPE__ORDERS
					===
						$this->getRmRequest()->getType()
				) {

					switch ($this->getRmRequest()->getMode()) {
						case Df_1C_Model_Cml2_InputRequest_Generic::MODE__QUERY:
							$this->action_ordersExport ();
							break;

						case Df_1C_Model_Cml2_InputRequest_Generic::MODE__SUCCESS:
							$this->action_ordersExportSuccess ();
							break;

						case Df_1C_Model_Cml2_InputRequest_Generic::MODE__FILE:
							$this->action_ordersImport ();
							break;
					}
				}
			}
		}

		return $this;

	}




	/**
	 * @return Df_1C_Model_Cml2_Action_Front
	 */
	private function action_catalogImport () {

		/** @var Df_1C_Model_Cml2_Action_Catalog_Import $action  */
		$action =
			df_model (
				Df_1C_Model_Cml2_Action_Catalog_Import::getNameInMagentoFormat()
				,
				$this->getData()
			)
		;

		df_assert ($action instanceof Df_1C_Model_Cml2_Action_Catalog_Import);

		$action->process();

		return $this;

	}




	/**
	 * @return Df_1C_Model_Cml2_Action_Front
	 */
	private function action_catalogUpload () {

		/** @var Df_1C_Model_Cml2_Action_Catalog_Upload $action  */
		$action =
			df_model (
				Df_1C_Model_Cml2_Action_Catalog_Upload::getNameInMagentoFormat()
				,
				$this->getData()
			)
		;

		df_assert ($action instanceof Df_1C_Model_Cml2_Action_Catalog_Upload);

		$action->process();

		return $this;

	}






	/**
	 * @return Df_1C_Model_Cml2_Action_Front
	 */
	private function action_init () {

		/** @var Df_1C_Model_Cml2_Action_Init $action  */
		$action =
			df_model (
				Df_1C_Model_Cml2_Action_Init::getNameInMagentoFormat()
				,
				$this->getData()
			)
		;

		df_assert ($action instanceof Df_1C_Model_Cml2_Action_Init);

		$action->process();

		return $this;

	}





	/**
	 * @return Df_1C_Model_Cml2_Action_Front
	 */
	private function action_login () {

		/** @var Df_1C_Model_Cml2_Action_Login $action  */
		$action =
			df_model (
				Df_1C_Model_Cml2_Action_Login::getNameInMagentoFormat()
				,
				$this->getData()
			)
		;

		df_assert ($action instanceof Df_1C_Model_Cml2_Action_Login);

		$action->process();

		return $this;

	}





	/**
	 * @return Df_1C_Model_Cml2_Action_Front
	 */
	private function action_ordersExport () {

		/** @var Df_1C_Model_Cml2_Action_Orders_Export $action  */
		$action =
			df_model (
				Df_1C_Model_Cml2_Action_Orders_Export::getNameInMagentoFormat()
				,
				$this->getData()
			)
		;

		df_assert ($action instanceof Df_1C_Model_Cml2_Action_Orders_Export);

		$action->process();

		return $this;

	}




	/**
	 * @return Df_1C_Model_Cml2_Action_Front
	 */
	private function action_ordersExportSuccess () {

		$this
			->setResponseBodyAsArrayOfStrings (
				array (
					'success'
					,
					Df_Core_Const::T_EMPTY
				)
			)
		;

		return $this;

	}





	/**
	 * @return Df_1C_Model_Cml2_Action_Front
	 */
	private function action_ordersImport () {

		/** @var Df_1C_Model_Cml2_Action_Orders_Import $action  */
		$action =
			df_model (
				Df_1C_Model_Cml2_Action_Orders_Import::getNameInMagentoFormat()
				,
				$this->getData()
			)
		;

		df_assert ($action instanceof Df_1C_Model_Cml2_Action_Orders_Import);

		$action->process();

		return $this;

	}






	/**
	 * @return Df_1C_Model_Cml2_Action_Front
	 */
	private function checkLoggedIn () {

		$this->getSession()
			->setSessionId (
				$this->getController()->getRequest()->getCookie (self::SESSION_NAME)
			)
		;

		df_assert (
			$this->getSession()->isLoggedIn (
				$this->getSession()->getSessionId()
			)
			,
			'Доступ к данной операции запрещён,
			потому что система не смогла распознать администратора (неверная сессия)'
		);

		return $this;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Action_Front';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {

		/** @var string $result */
		static $result;

		if (!isset ($result)) {
			$result = df()->reflection()->getModelNameInMagentoFormat (self::getClass());
		}

		return $result;
	}

}

