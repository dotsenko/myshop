<?php



class Df_Dataflow_Model_Import_Config extends Df_Core_Model_Abstract {




	/**
	 * @return string
	 */
	public function getDecimalSeparator () {

		/** @var string $result  */
		$result =
			$this->getParam ('decimal_separator', Df_Core_Const::T_PERIOD)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @param string $paramName
	 * @param string|null $defaultValue [optional]
	 * @return string|null
	 */
	public function getParam ($paramName, $defaultValue = null) {

		df_param_string ($paramName, 0);

		if (!is_null ($defaultValue)) {
			df_param_string ($defaultValue, 1);
		}


		/** @var string $result  */
		$result =
			df_a ($this->getParams(), $paramName, $defaultValue)
		;

		if (!is_null ($result)) {
			df_result_string ($result);
		}


		return $result;

	}







	/**
	 * @return array
	 */
	public function getParams () {

		/** @var array $result  */
		$result =
				is_null (df_mage()->dataflow()->batch()->getId())
			?
				array ()
			:
				df_mage()->dataflow()->batch()->getParams()
		;

		df_result_array ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Import_Config';
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




	const DATAFLOW_PARAM__STORE = 'store';

	const DATAFLOW_PARAM__PRODUCT_TYPE = 'type';

}


