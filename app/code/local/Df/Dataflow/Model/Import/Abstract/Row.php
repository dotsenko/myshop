<?php



class Df_Dataflow_Model_Import_Abstract_Row extends Df_Core_Model_Abstract {




	/**
	 * @return array
	 */
	public function getAsArray () {

		/** @var array $result  */
		$result =
			$this->cfg (self::PARAM_ROW_AS_ARRAY)
		;

		df_assert_array ($result);

		return $result;

	}








	/**
	 * @return int
	 */
	public function getOrdering () {

		/** @var int $result  */
		$result =
			$this->cfg (self::PARAM_ORDERING)
		;

		df_result_integer ($result);

		return $result;

	}






	/**
	 * @param  string $value
	 * @return float
	 */
    public function parseAsNumber ($value) {

		df_param_string ($value, 0);


		/** @var array $allowedSymbols  */
        $allowedSymbols  =
			array (
				'0',1,2,3,4,5,6,7,8,9
				,
				'-'
				,
				$this->getConfig()->getDecimalSeparator()
			)
		;

		df_assert_array ($allowedSymbols);


		/** @var string $resultAsString  */
        $resultAsString = Df_Core_Const::T_EMPTY;

        for ($i = 0; $i < strlen($value); $i ++) {
            if (in_array($value[$i], $allowedSymbols)) {
                $resultAsString .= $value[$i];
            }
        }

        if (Df_Core_Const::T_PERIOD !== $this->getConfig()->getDecimalSeparator()) {

            $resultAsString =
				str_replace (
					$this->getConfig()->getDecimalSeparator()
					,
					Df_Core_Const::T_PERIOD
					,
					$resultAsString
				)
			;

        }


		/** @var float $result  */
		$result = floatval($resultAsString);

		df_result_float ($result);

        return $result;
    }








	/**
	 * @param string $fieldName
	 * @param bool $isRequired [optional]
	 * @param string|null $default [optional]
	 * @return string|null
	 */
	public function getFieldValue ($fieldName, $isRequired = false, $default = null) {

		df_param_string ($fieldName, 0);

		df_param_boolean ($isRequired, 1);

		if (!is_null ($default)) {
			df_param_string ($default, 2);
		}

		/** @var string|null $result  */
		$result =
			df_a ($this->getAsArray (), $fieldName, $default)
		;

		df_assert (
			!is_null ($result) || !$isRequired
			,
			sprintf (
				'В строке импортируемых данных №%d требуется (и сейчас отсутствует) поле «%s»'
				,
				$this->getOrdering()
				,
				$fieldName
			)
		)
		;



		if (!is_null ($result)) {

			if (!df_check_string ($result)) {
				df_error (
					sprintf (
						'Значение поля «%s» должно быть строкой, однако получено значение типа «%s»'
						,
						$fieldName
						,
						gettype ($result)
					)
				);
			}
		}


		return $result;

	}





	/**
	 * @return Df_Dataflow_Model_Import_Config
	 */
	protected function getConfig () {

		/** @var Df_Dataflow_Model_Import_Config $result  */
		$result =
			df_helper()->dataflow()->import()->getConfig()
		;

		df_assert ($result instanceof Df_Dataflow_Model_Import_Config);

		return $result;

	}







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Import_Abstract_Row';
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



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM_ROW_AS_ARRAY, new Df_Zf_Validate_Array ()
			)
			->addValidator (
				self::PARAM_ORDERING, new Df_Zf_Validate_Int ()
			)
		;
	}



	const PARAM_ROW_AS_ARRAY = 'rowAsArray';
	const PARAM_ORDERING = 'ordering';

}


