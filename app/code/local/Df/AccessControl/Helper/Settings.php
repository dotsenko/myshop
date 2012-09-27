<?php

class Df_AccessControl_Helper_Settings extends Df_Core_Helper_Settings {

	/**
	 * @return boolean
	 */
	public function getEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks_admin/access_control/enabled'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function getAutoExpandAll () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks_admin/access_control/auto_expand_all'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return boolean
	 */
	public function getAutoSelectAncestors () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks_admin/access_control/auto_select_ancestors'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return boolean
	 */
	public function getAutoSelectDescendants () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks_admin/access_control/auto_select_descendants'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_AccessControl_Helper_Settings';
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