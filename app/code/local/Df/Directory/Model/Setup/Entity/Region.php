<?php


class Df_Directory_Model_Setup_Entity_Region extends Df_Core_Model_Abstract {


	/**
	 * @return string
	 */
	public function getCapital () {

		/** @var string $result  */
		$result =
			$this->cfg (self::PARAM__CAPITAL)
		;

		df_result_string ($result);

		return $result;

	}



	/**
	 * @return string
	 */
	public function getName () {

		/** @var string $result  */
		$result =
			$this->cfg (self::PARAM__NAME)
		;

		df_result_string ($result);

		return $result;

	}



	/**
	 * @return string
	 */
	public function getCode () {

		/** @var string $result  */
		$result =
			$this->cfg (self::PARAM__CODE)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getType () {

		/** @var int $result  */
		$result =
			$this->cfg (self::PARAM__TYPE)
		;

		df_result_integer ($result);

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
				self::PARAM__NAME, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__CODE, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__TYPE, new Df_Zf_Validate_Int()
			)
			->addValidator (
				self::PARAM__CAPITAL, new Df_Zf_Validate_String()
			)
		;
	}	
	
	
	
	const PARAM__NAME = 'name';
	const PARAM__CODE = 'code';
	const PARAM__TYPE = 'type';
	const PARAM__CAPITAL = 'capital';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Model_Setup_Entity_Region';
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

