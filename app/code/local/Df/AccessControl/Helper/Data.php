<?php


class Df_AccessControl_Helper_Data extends Mage_Core_Helper_Abstract {


	/**
	 * @return Df_AccessControl_Model_Role|null
	 */
	public function getCurrentRole () {

		if (!isset ($this->_currentRole) && !$this->_currentRoleIsNull) {

			/** @var Df_AccessControl_Model_Role|null $result  */
			$result = null;

			/** @var int|null $roleId  */
			$roleId = null;

			/**
			 * Здесь вполне может произойти исключительная ситуация
			 * по вине некачественных сторонних модулей,
			 * которые неправильно авторизуются в административной части
			 * (замечен дефект модуля Zizio Social Daily Deal)
			 */
			try {
				$roleId = $this->getCurrentRoleId();
			}
			catch (Exception $e) {
				Mage::log (
						'Модуль «Управление административным доступом к товарным разделам» '
					.
						'не в состоянии работать по причине некачественного стороннего модуля.'
				);
			}

			if (!is_null ($roleId)) {

				df_assert_integer ($roleId);

				/** @var Df_AccessControl_Model_Role $result  */
				$result =
					df_model (
						Df_AccessControl_Model_Role::getNameInMagentoFormat()
					)
				;

				df_assert ($result instanceof Df_AccessControl_Model_Role);

				$result->load ($this->getCurrentRoleId());
			}

			if (!is_null ($result)) {
				df_assert ($result instanceof Df_AccessControl_Model_Role);
			}
			else {
				$this->_currentRoleIsNull = true;
			}

			$this->_currentRole = $result;
		}


		if (!is_null ($this->_currentRole)) {
			df_assert ($this->_currentRole instanceof Df_AccessControl_Model_Role);
		}

		return $this->_currentRole;
	}


	/**
	* @var Df_AccessControl_Model_Role|null
	*/
	private $_currentRole;

	/**
	 * @var bool
	 */
	private $_currentRoleIsNull = false;




	/**
	 * @return int
	 */
	public function getLastSavedRoleId () {

		/** @var int $result  */
		$result = $this->_lastSavedRoleId;

		df_result_integer ($result);

		return $result;
	}


	/**
	 * @param int $roleId
	 * @return Df_AccessControl_Helper_Data
	 */
	public function setLastSavedRoleId ($roleId) {

		df_param_integer ($roleId, 0);

		$this->_lastSavedRoleId = $roleId;

		$this->getLastSavedRoleId();

		return $this;
	}


	/**
	* @var int
	*/
	private $_lastSavedRoleId;


	


	/**
	 * @throws Exception
	 * @return int
	 */
	private function getCurrentRoleId () {

		if (!isset ($this->_currentRoleId)) {


			/** @var Mage_Admin_Model_User $user */
			$user =
				df_mage()->admin()->session()->getDataUsingMethod (
					Df_Admin_Const::SESSION__PARAM__USER
				)
			;

			/**
			 * Здесь вполне может произойти исключительная ситуация
			 * по вине некачественных сторонних модулей,
			 * которые неправильно авторизуются в административной части
			 * (замечен дефект модуля Zizio Social Daily Deal)
			 */
			df_assert ($user instanceof Mage_Admin_Model_User);



			/** @var Mage_Admin_Model_Roles $role  */
			$role = $user->getRole ();

			df_assert ($role instanceof Mage_Admin_Model_Roles);


			/** @var int $result  */
			$result =
				$role->getId ()
			;

			df_assert_integer ($result);

			$this->_currentRoleId = $result;

		}


		df_result_integer ($this->_currentRoleId);

		return $this->_currentRoleId;

	}


	/**
	* @var int
	*/
	private $_currentRoleId;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_AccessControl_Helper_Data';
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