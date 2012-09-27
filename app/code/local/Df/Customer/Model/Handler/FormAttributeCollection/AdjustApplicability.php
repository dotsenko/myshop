<?php

class Df_Customer_Model_Handler_FormAttributeCollection_AdjustApplicability extends Df_Core_Model_Handler {



	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		if (!is_null ($this->getAddress ())) {

			foreach ($this->getAttributes() as $attribute) {

				/** @var Mage_Customer_Model_Attribute $attribute */
				df_assert ($attribute instanceof Mage_Customer_Model_Attribute);

				$this->adjust ($attribute);

			}

		}

	}





	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @override
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
	 * @param Mage_Customer_Model_Attribute $attribute
	 * @return Df_Customer_Model_Handler_FormAttributeCollection_AdjustApplicability
	 */
	private function adjust (Mage_Customer_Model_Attribute $attribute) {

		/** @var Df_Customer_Model_Attribute_ApplicabilityAdjuster $adjuster */
		$adjuster =
			df_model (
				Df_Customer_Model_Attribute_ApplicabilityAdjuster::getNameInMagentoFormat()
				,
				array (
					Df_Customer_Model_Attribute_ApplicabilityAdjuster
						::PARAM__ATTRIBUTE => $attribute
					,
					Df_Customer_Model_Attribute_ApplicabilityAdjuster
						::PARAM__ADDRESS => $this->getAddress()
				)
			)
		;

		$adjuster->adjust ();

		return $this;

	}





	/**
	 * @return Mage_Customer_Model_Resource_Form_Attribute_Collection|Mage_Customer_Model_Entity_Form_Attribute_Collection
	 */
	private function getAttributes () {

		/** @var Mage_Customer_Model_Resource_Form_Attribute_Collection|Mage_Customer_Model_Entity_Form_Attribute_Collection $result  */
		$result = $this->getEvent()->getCollection();

		df_helper()->customer()->assert()->formAttributeCollection ($result);

		return $result;

	}




	/**
	 * @return Mage_Customer_Model_Address_Abstract|null
	 */
	private function getAddress () {

		/** @var Mage_Customer_Model_Address_Abstract|null $result  */
		$result =
			$this->getAttributes()->getFlag (
				Df_Customer_Const_Form_Attribute_Collection::PARAM__ADDRESS
			)
		;

		if (!is_null ($result)) {
			df_assert ($result instanceof Mage_Customer_Model_Address_Abstract);
		}

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Customer_Model_Handler_FormAttributeCollection_AdjustApplicability';
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


