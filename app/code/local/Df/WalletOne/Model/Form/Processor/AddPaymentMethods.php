<?php

class Df_WalletOne_Model_Form_Processor_AddPaymentMethods extends Df_Varien_Data_Form_Processor {


	/**
	 * @return Df_WalletOne_Model_Form_Processor_AddPaymentMethods
	 */
	public function process () {

		foreach ($this->getFieldValues() as $subFieldName => $subFieldValue) {

			/** @var string|int $subFieldName */
			/** @var string|array $subFieldValue */

			if (!is_int ($subFieldName)) {
				df_assert_string ($subFieldName);
			}

			if (!is_array ($subFieldValue)) {
				df_assert_string ($subFieldValue);
			}

			$this->getForm()
				->addHiddenField (
					implode (
						'_'
						,
						array (
							$this->getFieldName()
							,
							df_string ($subFieldName)
						)
					)
					,
					$this->getFieldName()
					,
					$subFieldValue
				)
			;

		}


		return $this;

	}




	/**
	 * @return string
	 */
	private function getFieldName () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__FIELD_NAME);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return array
	 */
	private function getFieldValues () {

		/** @var array $result  */
		$result = $this->cfg (self::PARAM__FIELD_VALUES);

		df_result_array ($result);

		return $result;

	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__FIELD_NAME
				,
				new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__FIELD_VALUES
				,
				new Df_Zf_Validate_Array()
			)
		;
	}



	const PARAM__FIELD_NAME = 'field_name';
	const PARAM__FIELD_VALUES = 'field_values';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WalletOne_Model_Form_Processor_AddPaymentMethods';
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


