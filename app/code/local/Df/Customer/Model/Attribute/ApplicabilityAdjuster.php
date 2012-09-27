<?php

class Df_Customer_Model_Attribute_ApplicabilityAdjuster extends Df_Core_Model_Abstract {



	/**
	 * @return Df_Customer_Model_Attribute_ApplicabilityAdjuster
	 */
	public function adjust () {

		if (!is_null ($this->getApplicability())) {

			$this->getAttribute()
				->addData (
					array (
						'is_required' => intval ($this->isRequired())
						,
						'scope_is_required' => intval ($this->isRequired())
						,
						'is_visible' => intval ($this->isVisible())
						,
						'scope_is_visible' => intval ($this->isVisible())
					)
				)
			;
		}

		return $this;

	}




	/**
	 * @return bool
	 */
	private function isRequired () {
	
		if (!isset ($this->_required)) {
	
			/** @var bool $result  */
			$result =
					Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__REQUIRED
				===
					$this->getApplicability()
			;
	
	
			df_assert_boolean ($result);
	
			$this->_required = $result;
	
		}
	
	
		df_result_boolean ($this->_required);
	
		return $this->_required;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_required;





	/**
	 * @return bool
	 */
	private function isVisible () {

		if (!isset ($this->_visible)) {

			/** @var bool $result  */
			$result =
					Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__NO
				!==
					$this->getApplicability()
			;


			df_assert_boolean ($result);

			$this->_visible = $result;

		}


		df_result_boolean ($this->_visible);

		return $this->_visible;

	}


	/**
	* @var bool
	*/
	private $_visible;





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
	 * @return string|null
	 */
	private function getApplicability () {
	
		if (!isset ($this->_applicability) && !$this->_applicabilityIsNull) {
	
			/** @var string|null $result  */
			$result =
					(
							is_null ($this->getApplicabilityManager())
						||
							!$this->getApplicabilityManager()->isEnabled()
					)
				?
					null
				:
					$this->getApplicabilityManager()->getValue (
						$this->getAttribute()->getAttributeCode()
					)
			;

			if (!is_null ($result)) {
				df_assert_string ($result);
			}
			else {
				$this->_applicabilityIsNull = true;
			}

			$this->_applicability = $result;
		}

		if (!is_null ($this->_applicability)) {
			df_result_string ($this->_applicability);
		}
	
		return $this->_applicability;
	
	}
	
	
	/**
	* @var string|null
	*/
	private $_applicability;

	/**
	 * @var bool
	 */
	private $_applicabilityIsNull = false;




	
	
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
	 * @return Mage_Customer_Model_Attribute
	 */
	private function getAttribute () {

		/** @var Mage_Customer_Model_Attribute $result  */
		$result = $this->cfg (self::PARAM__ATTRIBUTE);

		df_assert ($result instanceof Mage_Customer_Model_Attribute);

		return $result;

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
			->validateClass (
				self::PARAM__ATTRIBUTE, Df_Customer_Const::ATTRIBUTE_CLASS
			)
		;
	}



	const PARAM__ADDRESS = 'address';
	const PARAM__ATTRIBUTE = 'attribute';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Customer_Model_Attribute_ApplicabilityAdjuster';
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


