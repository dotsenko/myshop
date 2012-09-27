<?php

/**
 * @singleton
 * В этом классе нельзя кешировать результаты вычислений!
 */
class Df_Spsr_Model_Config_Source_Insurer extends Df_Admin_Model_Config_Source {



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
						self::OPTION_KEY__LABEL => 'служба доставки'
						,
						self::OPTION_KEY__VALUE => self::OPTION_VALUE__CARRIER
					)

					,
					array (
						self::OPTION_KEY__LABEL => 'страховая компания'
						,
						self::OPTION_KEY__VALUE => self::OPTION_VALUE__INSURANCE_COMPANY
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




	const OPTION_VALUE__CARRIER = 'carrier';
	const OPTION_VALUE__INSURANCE_COMPANY = 'insurance_company';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Config_Source_Insurer';
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


