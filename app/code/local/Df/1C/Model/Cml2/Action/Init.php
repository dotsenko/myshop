<?php

class Df_1C_Model_Cml2_Action_Init extends Df_1C_Model_Cml2_Action {


	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Action_Init
	 */
	protected function processInternal () {

		$this
			->setResponseBodyAsArrayOfStrings (
				array (
					/**
					 * @todo надо добавить поддержку формата ZIP
					 */
					$this->implodeResponseParam ('zip', 'no')
					,
					$this->implodeResponseParam ('file_limit', -1)
					,
					Df_Core_Const::T_EMPTY
				)
			)
		;

		return $this;

	}




	/**
	 * @param string $paramName
	 * @param string|int $paramValue
	 * @return string
	 */
	private function implodeResponseParam ($paramName, $paramValue) {

		df_param_string ($paramName, 0);

		if (!is_int ($paramValue)) {
			df_param_string($paramValue, 1);
		}

		/** @var string $result  */
		$result =
			implode (
				'='
				,
				array (
					$paramName
					,
					$paramValue
				)
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Action_Init';
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
