<?php


class Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Dropdown
	extends Df_Checkout_Block_Frontend_Ergonomic_Address_Field {


	/**
	 * @override
	 * @return string
	 */
	public function getType () {

		/** @var string $result  */
		$result =
			sprintf (
				'%s_id'
				,
				parent::getType()
			)
		;


		df_result_string ($result);

		return $result;

	}



	/**
	 * @override
	 * @return string
	 */
	protected function getConfigShortKey () {

		/** @var string $result  */
		$result =
			str_replace (
				'_id'
				,
				''
				,
				$this->getType ()
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
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Dropdown';
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


