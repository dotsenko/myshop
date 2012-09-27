<?php


class Df_Reports_Model_Handler_GroupResultsByWeek_SetColumnRenderer
	extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		/** @var Varien_Object|false $periodColumn  */
		$periodColumn = $this->getBlockAsReportGrid()->getColumn ('period');

		if ($periodColumn instanceof Varien_Object) {

			$periodColumn->unsetData ('renderer');

		}

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
	 * @return Mage_Adminhtml_Block_Report_Grid_Abstract
	 */
	private function getBlockAsReportGrid () {

		/** @var Mage_Adminhtml_Block_Report_Grid_Abstract $result  */
		$result =
			$this->getEvent()->getBlock()
		;

		df_assert ($result instanceof Mage_Adminhtml_Block_Report_Grid_Abstract);

		return $result;

	}


	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Model_Handler_GroupResultsByWeek_SetColumnRenderer';
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


