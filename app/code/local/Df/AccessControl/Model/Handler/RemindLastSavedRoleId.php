<?php


class Df_AccessControl_Model_Handler_RemindLastSavedRoleId
	extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		if (
				df_enabled (Df_Core_Feature::ACCESS_CONTROL)
			&&
				df_cfg()->admin()->access_control()->getEnabled ()
		) {

			df_helper()->accessControl()
				->setLastSavedRoleId (
					$this->getEvent()->getRole()->getId ()
				)
			;

		}

	}






	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Admin_Model_Event_Roles_Save_After
	 */
	protected function getEvent () {

		/** @var Df_Admin_Model_Event_Roles_Save_After $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Admin_Model_Event_Roles_Save_After);

		return $result;
	}






	/**
	 * Класс события (для валидации события)
	 *
	 * @override
	 * @return string
	 */
	protected function getEventClass () {

		/** @var string $result  */
		$result = Df_Admin_Model_Event_Roles_Save_After::getClass();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_AccessControl_Model_Handler_RemindLastSavedRoleId';
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


