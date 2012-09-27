<?php

class Df_Localization_Model_Settings_Area extends Df_Core_Model_Settings_Group {



	/**
	 * @return string
	 */
	public function allowInterference () {

		/** @var string $result  */
		$result = $this->getValue ('allow_interference');

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return boolean
	 */
	public function isEnabled () {

		/** @var bool $result  */
		$result = $this->getYesNo ('enabled', 'rm_translation');

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return boolean
	 */
	public function needHideDecimals () {

		/** @var bool $result  */
		$result = $this->getYesNo ('hide_decimals');

		df_result_boolean ($result);

		return $result;

	}






	/**
	 * @return boolean
	 */
	public function needSetAsPrimary () {

		/** @var bool $result  */
		$result =
				$this->isEnabled()
			&&
				$this->getYesNo (
					'set_as_primary'
					,
					array ('rm_translation')
				)
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @return boolean
	 */
	public function needTranslateDropdownOptions () {

		/** @var bool $result  */
		$result =
				$this->isEnabled()
			&&
				$this->getYesNo (
					'translate_dropdown_options'
					,
					array ('rm_translation')
				)
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addData (
				array (
					self::PARAM__SECTION => 'df_localization'
				)
			)
		;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Model_Settings_Area';
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

