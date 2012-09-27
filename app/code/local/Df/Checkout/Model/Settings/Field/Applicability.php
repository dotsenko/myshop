<?php

class Df_Checkout_Model_Settings_Field_Applicability extends Df_Core_Model_Settings_Group {


	/**
	 * @return bool
	 */
	public function isEnabled () {

		/** @var bool $result  */
		$result = $this->getYesNo ('enabled');

		df_result_boolean ($result);

		return $result;

	}



	/**
	 * @return string
	 */
	public function confirm_password () {

		/** @var string $result  */
		$result = $this->getValue (Df_Checkout_Const_Field::CONFIRM_PASSWORD);

		df_result_string ($result);

		return $result;

	}



	/**
	 * @return string
	 */
	public function customer_password () {

		/** @var string $result  */
		$result = $this->getValue (Df_Checkout_Const_Field::CUSTOMER_PASSWORD);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function region () {

		/** @var string $result  */
		$result = $this->getValue (Df_Checkout_Const_Field::REGION);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getGroup () {

		if (!isset ($this->_group)) {

			/** @var string $result  */
			$result =
				sprintf (
					'%s_field_applicability'
					,
					$this->getAddressType()
				)
			;


			df_assert_string ($result);

			$this->_group = $result;

		}


		df_result_string ($this->_group);

		return $this->_group;

	}


	/**
	* @var string
	*/
	private $_group;






	/**
	 * @return string
	 */
	private function getAddressType () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__ADDRESS_TYPE);

		df_result_string ($result);

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
				self::PARAM__ADDRESS_TYPE, new Df_Zf_Validate_String()
			)
			->addData (
				array (
					self::PARAM__SECTION => 'df_checkout'
				)
			)
		;

	}


	const PARAM__ADDRESS_TYPE = 'address_type';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Settings_Field_Applicability';
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
