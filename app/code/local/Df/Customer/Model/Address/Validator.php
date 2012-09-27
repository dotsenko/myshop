<?php

class Df_Customer_Model_Address_Validator extends Df_Core_Model_Abstract {
	
	

	/**
	 * @return bool|array
	 */
	public function validate () {

		df_assert (!is_null ($this->getApplicabilityManager ()));

		/** @var bool|array $result  */
		$result = true;

		$this->getAddress()->implodeStreetAddress();

		foreach ($this->getValidationMap() as $field => $errorMessage) {
			$this->validateNotEmpty ($field, $errorMessage);
		}

        if (
				(
						Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__REQUIRED
					===
						$this->getApplicabilityManager()->getValue(Df_Checkout_Const_Field::STREET)
				)
			&&
				df_empty (
					$this->getAddress()->getStreet(1)
				)
		) {
			$this->getException()
				->addMessage (
					df_mage()->core()->message()->error (
						'Please enter the street.'
					)
				)
			;
        }


		/** @var array $_havingOptionalZip  */
		$havingOptionalZip = df_mage()->directoryHelper()->getCountriesWithOptionalZip();
		if (
				(
						Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__REQUIRED
					===
						$this->getApplicabilityManager()->getValue(Df_Checkout_Const_Field::POSTCODE)
				)
			&&
				!in_array($this->getAddress()->getDataUsingMethod ('country_id'), $havingOptionalZip)
			&&
				df_empty (
					$this->getAddress()->getDataUsingMethod (Df_Checkout_Const_Field::POSTCODE)
				)
		) {
			$this->getException()
				->addMessage (
					df_mage()->core()->message()->error (
						'Please enter the zip/postal code.'
					)
				)
			;
		}


        if (
				(
						Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__REQUIRED
					===
						$this->getApplicabilityManager()->getValue(Df_Checkout_Const_Field::COUNTRY)
				)
			&&
				df_empty (
					$this->getAddress()->getDataUsingMethod ('country_id')
				)
		) {
			$this->getException()
				->addMessage (
					df_mage()->core()->message()->error (
						'Please enter the country.'
					)
				)
			;
        }


        if (
				(
						Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__REQUIRED
					===
						$this->getApplicabilityManager()->getValue(Df_Checkout_Const_Field::REGION)
				)
			&&
				count ($this->getAddress()->getCountryModel()->getRegionCollection())
			&&
				df_empty ($this->getAddress()->getRegionId())
		) {
			$this->getException()
				->addMessage (
					df_mage()->core()->message()->error (
						'Please enter the state/province.'
					)
				)
			;
        }



		if (0 < count ($this->getException()->getMessages())) {
			$result = array ();
			foreach ($this->getException()->getMessages() as $message) {
				/** @var Mage_Core_Model_Message_Abstract $message */
				df_assert ($message instanceof Mage_Core_Model_Message_Abstract);

				$result []=
					df_mage()->customerHelper()->__ (
						$message->getText()
					)
				;
			}
		}

		if (!is_array ($result)) {
			df_result_boolean ($result);
		}

		return $result;

	}

	
	
	


	/**
	 * @return Mage_Customer_Model_Address_Abstract
	 */
	private function getAddress () {

		/** @var Mage_Customer_Model_Address_Abstract $result  */
		$result = $this->cfg (self::PARAM__ADDRESS);

		df_assert ($result instanceof Mage_Customer_Model_Address_Abstract);

		return $result;

	}



	/**
	 * @return string|null
	 */
	private function getAddressType () {

		/** @var string|null $result  */
		$result = $this->getAddress()->getDataUsingMethod ('address_type');

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}

	
	
	
	
	/**
	 * @return Df_Checkout_Model_Settings_Field_Applicability|null
	 */
	private function getApplicabilityManager () {
	
		if (!isset ($this->_applicabilityManager) && !$this->_applicabilityManagerIsNull) {
	
			/** @var Df_Checkout_Model_Settings_Field_Applicability|null $result  */
			$result =
					df_empty ($this->getAddressType ())
				?
					null
				:
					df_cfg()->checkout()->field()->applicability()->getApplicabilityByAddressType (
						$this->getAddressType ()
					)
			;
	
	
			if (!is_null ($result)) {
				df_assert ($result instanceof Df_Checkout_Model_Settings_Field_Applicability);
			}
			else {
				$this->_applicabilityManagerIsNull = true;
			}
	
			$this->_applicabilityManager = $result;
	
		}
	
	
		if (!is_null ($this->_applicabilityManager)) {
			df_assert ($this->_applicabilityManager instanceof Df_Checkout_Model_Settings_Field_Applicability);
		}		
		
	
		return $this->_applicabilityManager;
	
	}
	
	
	/**
	* @var Df_Checkout_Model_Settings_Field_Applicability|null
	*/
	private $_applicabilityManager;	
	
	/**
	 * @var bool
	 */
	private $_applicabilityManagerIsNull = false;	
	
	




	/**
	 * @return Mage_Core_Exception
	 */
	private function getException () {
	
		if (!isset ($this->_exception)) {
	
			/** @var Mage_Core_Exception $result  */
			$result = new Mage_Core_Exception ();
		
			df_assert ($result instanceof Mage_Core_Exception);
	
			$this->_exception = $result;
	
		}
	
	
		df_assert ($this->_exception instanceof Mage_Core_Exception);
	
		return $this->_exception;
	
	}
	
	
	/**
	* @var Mage_Core_Exception
	*/
	private $_exception;	
	
	
	
	
	/**
	 * @return array
	 */
	private function getValidationMap () {
	
		if (!isset ($this->_validationMap)) {
	
			/** @var array $result  */
			$result =
				array (
					Df_Checkout_Const_Field::FIRSTNAME => 'Please enter the first name.'
					,
					Df_Checkout_Const_Field::LASTNAME => 'Please enter the last name.'
					,
					Df_Checkout_Const_Field::CITY => 'Please enter the city.'
					,
					Df_Checkout_Const_Field::TELEPHONE => 'Please enter the telephone number.'
				)
			;
	
	
			df_assert_array ($result);
	
			$this->_validationMap = $result;
	
		}
	
	
		df_result_array ($this->_validationMap);
	
		return $this->_validationMap;
	
	}
	
	
	/**
	* @var array
	*/
	private $_validationMap;	
	
	
	

	
	
	
	
	/**
	 * @param string $fieldName
	 * @param string $errorMessage
	 * @return Df_Customer_Model_Address_Validator
	 */
	private function validateNotEmpty ($fieldName, $errorMessage) {

        if (
				(
						Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__REQUIRED
					===
						$this->getApplicabilityManager()->getValue($fieldName)
				)
			&&
				!Zend_Validate::is (
					$this->getAddress()->getDataUsingMethod (
						$fieldName
					)
					,
					'NotEmpty'
				)
		) {
			$this->getException()
				->addMessage (
					df_mage()->core()->message()->error (
						$errorMessage					
					)
				)
			;
        }

		return $this;

	}	
	




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__ADDRESS, Df_Customer_Const::ADDRESS_ABSTRACT_CLASS
			)
		;
	}



	const PARAM__ADDRESS = 'address';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Customer_Model_Address_Validator';
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


