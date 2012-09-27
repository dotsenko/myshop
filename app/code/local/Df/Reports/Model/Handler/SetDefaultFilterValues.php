<?php


class Df_Reports_Model_Handler_SetDefaultFilterValues extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		$this->setEndDateToYesterday ();

		if ($this->getPeriodDuration ()) {

			$this->setStartDate ();

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
	 * @return Df_Reports_Model_Handler_SetDefaultFilterValues
	 */
	private function setStartDate () {

		/** @var Varien_Data_Form_Element_Date|null $elementStartDate  */
		$elementStartDate =
			$this->getBlockAsReportFilterForm()->getForm()
				->getElement (
					self::FORM_ELEMENT__FROM
				)
		;

		if ($elementStartDate instanceof Varien_Data_Form_Element_Date) {

			if (
				df_empty (
					$elementStartDate->getValueInstance ()
				)
			) {

				/** @var Varien_Data_Form_Element_Date|null $elementEndDate  */
				$elementEndDate =
					$this->getBlockAsReportFilterForm()->getForm()
						->getElement (
							self::FORM_ELEMENT__TO
						)
				;

				if ($elementEndDate instanceof Varien_Data_Form_Element_Date) {

					/** @var Zend_Date|null $endDate  */
					$endDate =
						$elementEndDate->getValueInstance()
					;

					if (!df_empty ($endDate)) {

						df_assert ($endDate instanceof Zend_Date);

						/** @var Zend_Date $startDate  */
						$startDate = new Zend_Date ($endDate);

						df_assert ($startDate instanceof Zend_Date);


						$startDate
							->sub (
								df_a (
									$this->getPeriodDuration ()
									,
									Df_Reports_Model_System_Config_Source_Duration
										::OPTION_PARAM__DURATION__VALUE
								)
								,
								df_a (
									$this->getPeriodDuration ()
									,
									Df_Reports_Model_System_Config_Source_Duration
										::OPTION_PARAM__DURATION__DATEPART
								)
							)
						;

						$elementStartDate->setValue ($startDate);

					}

				}


			}

		}

		return $this;

	}







	/**
	 * @return array|null
	 */
	private function getPeriodDuration () {
	
		if (!isset ($this->_periodDuration)) {
	
			/** @var array|null $result  */
			$result = null;



			if (
					Df_Reports_Model_System_Config_Source_Duration::UNDEFINED
				!==
					df_cfg()->reports()->common()->getPeriodDuration()
			) {


				/** @var Df_Reports_Model_System_Config_Source_Duration $configDuration  */
				$configDuration =
					df_model (
						Df_Reports_Model_System_Config_Source_Duration::getNameInMagentoFormat()
					)
				;

				df_assert ($configDuration instanceof Df_Reports_Model_System_Config_Source_Duration);



				foreach ($configDuration->toOptionArray() as $option) {

					/** @var array $option */
					if (
							df_cfg()->reports()->common()->getPeriodDuration()
						===
							df_a ($option, Df_Admin_Model_Config_Source::OPTION_KEY__VALUE)
					)  {

						/** @var array $duration  */
						$result =
							df_a (
								$option
								,
								Df_Reports_Model_System_Config_Source_Duration
									::OPTION_PARAM__DURATION
							)
						;

						df_assert_array ($result);

						break;
					}

				}

			}


	

			if (!is_null ($result)) {
				df_assert_array ($result);
			}

	
			$this->_periodDuration = $result;
	
		}

		if (!is_null ($this->_periodDuration)) {
			df_result_array ($this->_periodDuration);
		}

		return $this->_periodDuration;
	
	}
	
	
	/**
	* @var array|null
	*/
	private $_periodDuration;








	/**
	 * @return Df_Reports_Model_Handler_SetDefaultFilterValues
	 */
	private function setEndDateToYesterday () {

		/** @var Varien_Data_Form_Element_Date|null $elementEndDate  */
		$elementEndDate =
			$this->getBlockAsReportFilterForm()->getForm()
				->getElement (
					self::FORM_ELEMENT__TO
				)
		;

		if ($elementEndDate instanceof Varien_Data_Form_Element_Date) {

			if (
				df_empty (
					$elementEndDate->getValueInstance()
				)
			) {
				$elementEndDate->setValue (df_helper()->zf()->date()->getYesterday());
			}

		}

		return $this;

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
		return 'Df_Reports_Model_Handler_SetDefaultFilterValues';
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




	const FORM_ELEMENT__TO = 'to';
	const FORM_ELEMENT__FROM = 'from';


}


