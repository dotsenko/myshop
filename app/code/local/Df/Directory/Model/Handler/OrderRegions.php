<?php


class Df_Directory_Model_Handler_OrderRegions extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		$this->getEvent()->getCollection()->getSelect()
			->order(
				array (
					'rname.name ASC'
					,
					'main_table.default_name ASC'
				)
			)
		;

	}
	



	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore);

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
		$result = Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore::getClass();

		df_result_string ($result);

		return $result;

	}
	





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Model_Handler_OrderRegions';
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


