<?php


class Df_AccessControl_Model_Handler_Permissions_Role_Saverole_UpdateCatalogAccessRights
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

			if (
				/**
				 * true, если расширенное управление доступом включено для данной конкретной должности
				 */
				$this->getEvent()->isModuleEnabledForRole ()
			) {

				$this->getRole()
					->setCategoryIds (
						$this->getEvent()->getSelectedCategoryIds ()
					)
				;

				if (is_null ($this->getRole ()->getId ())) {
					$this->getRole ()
						->prepareForInsert ($this->getEvent()->getRoleId())
					;
				}


				$this->getRole()
					->save ()

					/**
					 * Небольшой хак.
					 * Если ранее вызывался метод prepareForInsert(), то id сбрасывается.
					 * Лень разбираться.
					 */
					->setId ($this->getEvent()->getRoleId())
				;

				df_assert ($this->getEvent()->getRoleId() == $this->getRole()->getId ());

			}

			else {

				if ($this->getRole()->isModuleEnabled()) {

					$this->getRole()
						->delete ()
						->setId (null)
					;

				}

			}

		}

	}





	/**
	 * @return Df_AccessControl_Model_Role
	 */
	private function getRole () {

		if (!isset ($this->_role)) {

			/** @var Df_AccessControl_Model_Role $result  */
			$result =
				df_model (
					Df_AccessControl_Model_Role::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_AccessControl_Model_Role);


			$result->load ($this->getEvent()->getRoleId());


			df_assert ($result instanceof Df_AccessControl_Model_Role);

			$this->_role = $result;

		}


		df_assert ($this->_role instanceof Df_AccessControl_Model_Role);

		return $this->_role;

	}


	/**
	* @var Df_AccessControl_Model_Role
	*/
	private $_role;






	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_AccessControl_Model_Event_Permissions_Role_Saverole
	 */
	protected function getEvent () {

		/** @var Df_AccessControl_Model_Event_Permissions_Role_Saverole $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_AccessControl_Model_Event_Permissions_Role_Saverole);

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
		$result = Df_AccessControl_Model_Event_Permissions_Role_Saverole::getClass();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_AccessControl_Model_Handler_Permissions_Role_Saverole_UpdateCatalogAccessRights';
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


