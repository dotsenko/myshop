<?php

class Df_1C_Model_Cml2_Action_Login extends Df_1C_Model_Cml2_Action {


	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Action_Login
	 * @throws Exception
	 */
	protected function processInternal () {

		try {

			/** @var Mage_Core_Helper_Http $httpHelper  */
			$httpHelper = Mage::helper('core/http');

			df_assert ($httpHelper instanceof Mage_Core_Helper_Http);



			/** @var string $userName */
			/** @var string $password */
			list ($userName, $password) = $httpHelper->authValidate();



			df_assert_string (
				$userName
				,
				'Администратор пытается авторизоваться с пустым системным именем, что недопустимо.'
			);



			df_assert_string (
				$password
				,
				sprintf (
					'Администратор «%s» пытается авторизоваться с пустым паролем, что недопустимо.'
					,
					$userName
				)
			);


			$this->getSession()->start ($sessionName = null);


			/** @var Mage_Api_Model_User $apiUser */
			$apiUser = $this->getSession()->login ($userName, $password);

			df_assert (
				$apiUser instanceof Mage_Api_Model_User
				,
				sprintf (
					'Авторизация не удалась: неверно системное имя «%s», либо пароль к нему.'
					,
					$userName
				)
			);



			df_assert (
				1 === intval ($apiUser->getIsActive())
				,
				sprintf (
					'Администратор «%s» не допущен к работе'
					,
					$userName
				)
			);




			if (!$this->getSession()->isAllowed('rm/_1c')) {

				df_error (
					sprintf (
						"Администратор «%s»
						не допущен к обмену данными между Magento и 1С: Управление торговлей.
						\nДля допуска администратора к данной работе
						наделите администратора должностью, которая обладает полномочием
						«Российская сборка» → «1С: Управление торговлей»."
						,
						$userName
					)
				);
			}

			$this
				->setResponseBodyAsArrayOfStrings (
					array (
						'success'
						,
						self::SESSION_NAME
						,
						$this->getSession()->getSessionId()
						,
						Df_Core_Const::T_EMPTY
					)
				)
			;

		}

		catch (Exception $e) {

			df_log_exception ($e);

			$this->getResponse()
				->setHeader('HTTP/1.1','401 Unauthorized')
			;

			throw $e;
		}

		return $this;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Action_Login';
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
