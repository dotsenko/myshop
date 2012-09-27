<?php


class Df_Reports_Model_Handler_GroupResultsByWeek_AddOptionToFilter
	extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		/** @var Varien_Data_Form $form */
		$form = $this->getBlockAsReportFilterForm()->getForm();

		df_assert ($form instanceof Varien_Data_Form);


		/** @var Varien_Data_Form_Element_Fieldset $fieldset  */
		$fieldset = $form->getElement ('base_fieldset');

		df_assert ($fieldset instanceof Varien_Data_Form_Element_Fieldset);


		/** @var Varien_Data_Form_Element_Select $fieldPeriodType */
		$fieldPeriodType = $fieldset->getElements()->searchById ('period_type');

		df_assert ($fieldPeriodType instanceof Varien_Data_Form_Element_Select);


		/** @var array $options  */
		$options = $fieldPeriodType->getData ('options');

		df_assert_array ($options);


		$options ['week'] = df_mage()->reportsHelper()->__('Week');


		$fieldPeriodType->setData ('options', $options);



		/** @var array $values  */
		$values = $fieldPeriodType->getData ('values');

		df_assert_array ($values);


			array_splice (
				$values
				,
				1
				,
				0
				,
				array (
					array (
						'value' => 'week'
						,
						'label' => df_mage()->reportsHelper()->__('Week')
					)
				)
			)
		;


		$fieldPeriodType->setData ('values', $values);

	}




	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Core_Model_Event_Adminhtml_Block_HtmlBefore
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Adminhtml_Block_HtmlBefore $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Adminhtml_Block_HtmlBefore);

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
		$result = Df_Core_Model_Event_Adminhtml_Block_HtmlBefore::getClass();

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Mage_Adminhtml_Block_Report_Filter_Form
	 */
	private function getBlockAsReportFilterForm () {

		/** @var Mage_Adminhtml_Block_Report_Filter_Form $result  */
		$result =
			$this->getEvent()->getBlock()
		;

		df_assert ($result instanceof Mage_Adminhtml_Block_Report_Filter_Form);

		return $result;

	}


	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Model_Handler_GroupResultsByWeek_AddOptionToFilter';
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


