<?php


class Df_Customer_Model_Address extends Mage_Customer_Model_Address {


	/**
	 * @return string|null
	 */
	public function getAccountNumber () {

		/** @var string|null $result  */
		$result =
			$this->getData (self::PARAM__ACCOUNT_NUMBER)
		;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}



	/**
	 * @override
	 * @return bool|array
	 */
	public function validate () {

		/** @var string|null $addressType  */
		$addressType = $this->getDataUsingMethod ('address_type');

		/** @var bool|array $result  */
		$result =
					is_null ($addressType)
				||
					!df_cfg()->checkout()->field()->applicability()->getApplicabilityByAddressType (
						$addressType
					)->isEnabled()
			?
				parent::validate()
			:
				$this->getValidator()->validate()
		;

		if (!is_array ($result)) {
			df_result_boolean ($result);
		}

		return $result;

	}



	/**
	 * @return Df_Customer_Model_Address_Validator
	 */
	private function getValidator () {

		if (!isset ($this->_validator)) {

			/** @var Df_Customer_Model_Address_Validator $result  */
			$result =
				df_model (
					Df_Customer_Model_Address_Validator::getNameInMagentoFormat()
					,
					array (
						Df_Customer_Model_Address_Validator::PARAM__ADDRESS => $this
					)
				)
			;


			df_assert ($result instanceof Df_Customer_Model_Address_Validator);

			$this->_validator = $result;

		}


		df_assert ($this->_validator instanceof Df_Customer_Model_Address_Validator);

		return $this->_validator;

	}


	/**
	* @var Df_Customer_Model_Address_Validator
	*/
	private $_validator;





	const PARAM__ACCOUNT_NUMBER = 'df_account_number';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Customer_Model_Address';
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


