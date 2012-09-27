<?php


class Df_AccessControl_Model_Resource_Role extends Mage_Core_Model_Mysql4_Abstract {


	/**
	 * @return Df_AccessControl_Model_Resource_Role
	 */
	public function prepareForInsert () {

		$this->_useIsObjectNew = true;

		return $this;

	}





	/**
	 * @param int $roleId
	 * @param bool $on
	 * @return Df_AccessControl_Model_Resource_Role
	 */
	public function setEnabled ($roleId, $on) {

		df_param_integer ($roleId, 0);
		df_param_boolean ($on, 1);



		/** @var Df_AccessControl_Model_Role $role  */
		$role =
			df_model (
				Df_AccessControl_Model_Role::getNameInMagentoFormat()
			)
		;

		df_assert ($role instanceof Df_AccessControl_Model_Role);


		$role->load ($roleId);




		/** @var bool $alreadyEnabled  */
		$isAlreadyEnabled = $role->isModuleEnabled ();

		df_assert_boolean ($isAlreadyEnabled);


		if ($on && !$isAlreadyEnabled) {

			$role
				->setId ($roleId)
				->save ()
			;

		}


		else if (!$on && $isAlreadyEnabled) {

			$role->delete();

		}


		return $this;

	}





	/**
	 * @override
	 * @return void
	 */
    protected function _construct() {

		/**
		 * Нельзя вызывать parent::_construct(),
		 * потому что это метод в родительском классе — абстрактный.
		 */

        $this->_init (self::MAIN_TABLE, self::PRIMARY_KEY);
		$this->_isPkAutoIncrement = false;
    }


	const PRIMARY_KEY = 'role_id';
	const FIELD__ROLE_ID = 'role_id';
	const MAIN_TABLE = 'df_access_control/role';



}


