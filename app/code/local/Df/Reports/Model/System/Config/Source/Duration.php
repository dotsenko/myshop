<?php


/**
 * @singleton
 * Система создаёт объект-одиночку для потомков этого класса.
 * Не забывайте об этом при реализации кеширования результатов вычислений внутри этого класса!
 */
class Df_Reports_Model_System_Config_Source_Duration extends Df_Admin_Model_Config_Source {




	/**
	 * @override
	 * @param bool $isMultiSelect
	 * @return array
	 */
	protected function toOptionArrayInternal ($isMultiSelect = false) {

		/** @var array $result  */
		$result = $this->getAsOptionArray();

		df_result_array ($result);

		return $result;

    }




	
	
	/**
	 * @return array
	 */
	private function getAsOptionArray () {
	
		if (!isset ($this->_asOptionArray)) {
	
			
			/** @var array $result  */
			$result =
				array (

					array (
						self::OPTION_KEY__LABEL => 'не устанавливать'
						,
						self::OPTION_KEY__VALUE => self::UNDEFINED
					)

					,
					array (
						self::OPTION_KEY__LABEL => '1 день'
						,
						self::OPTION_KEY__VALUE => self::ONE_DAY
						,
						self::OPTION_PARAM__DURATION =>
							array (
								self::OPTION_PARAM__DURATION__DATEPART => Zend_Date::DAY_SHORT
								,
								self::OPTION_PARAM__DURATION__VALUE => 1
							)
					)

					,
					array (
						self::OPTION_KEY__LABEL => '1 неделя'
						,
						self::OPTION_KEY__VALUE => self::ONE_WEEK
						,
						self::OPTION_PARAM__DURATION =>
							array (
								self::OPTION_PARAM__DURATION__DATEPART => Zend_Date::DAY_SHORT
								,
								self::OPTION_PARAM__DURATION__VALUE => 7
							)
					)

					,
					array (
						self::OPTION_KEY__LABEL => '1 месяц'
						,
						self::OPTION_KEY__VALUE => self::ONE_MONTH
						,
						self::OPTION_PARAM__DURATION =>
							array (
								self::OPTION_PARAM__DURATION__DATEPART => Zend_Date::MONTH_SHORT
								,
								self::OPTION_PARAM__DURATION__VALUE => 1
							)
					)


					,
					array (
						self::OPTION_KEY__LABEL => '1 квартал'
						,
						self::OPTION_KEY__VALUE => self::ONE_QUARTER
						,
						self::OPTION_PARAM__DURATION =>
							array (
								self::OPTION_PARAM__DURATION__DATEPART => Zend_Date::MONTH_SHORT
								,
								self::OPTION_PARAM__DURATION__VALUE => 3
							)
					)


					,
					array (
						self::OPTION_KEY__LABEL => '1 год'
						,
						self::OPTION_KEY__VALUE => self::ONE_YEAR
						,
						self::OPTION_PARAM__DURATION =>
							array (
								self::OPTION_PARAM__DURATION__DATEPART => Zend_Date::YEAR
								,
								self::OPTION_PARAM__DURATION__VALUE => 1
							)
					)

				)
			;


			df_assert_array ($result);
	
			$this->_asOptionArray = $result;
	
		}
	
	
		df_result_array ($this->_asOptionArray);
	
		return $this->_asOptionArray;
	
	}
	
	
	/**
	* @var array
	*/
	private $_asOptionArray;	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Model_System_Config_Source_Duration';
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


	const OPTION_PARAM__DURATION = 'duration';

	const OPTION_PARAM__DURATION__DATEPART = 'datePart';

	const OPTION_PARAM__DURATION__VALUE = 'value';


	const UNDEFINED = 'undefined';
	const ONE_DAY = 'one_day';
	const ONE_WEEK = 'one_week';
	const ONE_MONTH = 'one_month';
	const ONE_QUARTER = 'one_quarter';
	const ONE_YEAR = 'one_year';

}