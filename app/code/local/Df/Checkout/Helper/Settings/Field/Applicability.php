<?php

class Df_Checkout_Helper_Settings_Field_Applicability extends Df_Core_Helper_Settings {



	/**
	 * @return Df_Checkout_Model_Settings_Field_Applicability
	 */
	public function billing () {

		/** @var Df_Checkout_Model_Settings_Field_Applicability $result */
		$result = $this->getApplicabilityByAddressType ('billing');

		df_assert ($result instanceof Df_Checkout_Model_Settings_Field_Applicability);

		return $result;

	}




	/**
	 * @return Df_Checkout_Model_Settings_Field_Applicability
	 */
	public function shipping () {

		/** @var Df_Checkout_Model_Settings_Field_Applicability $result */
		$result = $this->getApplicabilityByAddressType ('shipping');;

		df_assert ($result instanceof Df_Checkout_Model_Settings_Field_Applicability);

		return $result;

	}




	/**
	 * @param string $addressType
	 * @return Df_Checkout_Model_Settings_Field_Applicability
	 */
	public function getApplicabilityByAddressType ($addressType) {

		df_param_string ($addressType, 0);

		if (!isset ($this->_applicabilityByAddressType [$addressType])) {

			/** @var Df_Checkout_Model_Settings_Field_Applicability $result  */
			$result =
				df_model (
					Df_Checkout_Model_Settings_Field_Applicability::getNameInMagentoFormat()
					,
					array (
						Df_Checkout_Model_Settings_Field_Applicability
							::PARAM__ADDRESS_TYPE => $addressType
					)
				)
			;


			df_assert ($result instanceof Df_Checkout_Model_Settings_Field_Applicability);

			$this->_applicabilityByAddressType [$addressType] = $result;

		}


		df_assert (
				$this->_applicabilityByAddressType [$addressType]
			instanceof
				Df_Checkout_Model_Settings_Field_Applicability
		);

		return $this->_applicabilityByAddressType [$addressType];

	}


	/**
	* @var Df_Checkout_Model_Settings_Field_Applicability
	*/
	private $_applicabilityByAddressType;






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Helper_Settings_Field_Applicability';
	}


	/**
	 * Например, для класса Df_checkoutRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_checkout_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()->getModelNameInMagentoFormat (
				self::getClass()
			)
		;
	}


}