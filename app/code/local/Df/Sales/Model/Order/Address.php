<?php

class Df_Sales_Model_Order_Address extends Mage_Sales_Model_Order_Address {


	/**
	 * @override
	 * @return bool|array
	 */
	public function validate () {

		/** @var bool|array $result  */
		$result =
				!$this->isCustomValidationNeeded ()
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
	 * @return bool
	 */
	private function isCustomValidationNeeded () {

		if (!isset ($this->_customValidationNeeded)) {

			/** @var bool $result  */
			$result =
					df_cfg()->checkout()->_interface()->needShowAllStepsAtOnce()
				&&
					!df_empty ($this->getAddressType())
			;


			df_assert_boolean ($result);

			$this->_customValidationNeeded = $result;

		}


		df_result_boolean ($this->_customValidationNeeded);

		return $this->_customValidationNeeded;

	}


	/**
	* @var bool
	*/
	private $_customValidationNeeded;







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



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Order_Address';
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


