<?php


class Df_Eav_Model_Handler_TranslateAttributeLabelsInCollection extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		foreach ($this->getEntityAttributeCollection () as $attribute) {

			/** @var Mage_Eav_Model_Entity_Attribute $attribute  */
			df_assert ($attribute instanceof Mage_Eav_Model_Entity_Attribute);

			df_helper()->eav()->translateAttribute ($attribute);

		}

	}



	/**
	 * @return Mage_Eav_Model_Resource_Entity_Attribute_Collection|Mage_Eav_Model_Mysql4_Entity_Attribute_Collection
	 */
	private function getEntityAttributeCollection () {

		/** @var Mage_Eav_Model_Resource_Entity_Attribute_Collection|Mage_Eav_Model_Mysql4_Entity_Attribute_Collection $result  */
		$result =
			$this->getEvent()->getCollection()
		;

		df_helper()->eav()->assert()->entityAttributeCollection ($result);

		return $result;

	}





	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter);

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
		$result = Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter::getClass();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Eav_Model_Handler_TranslateAttributeLabelsInCollection';
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


