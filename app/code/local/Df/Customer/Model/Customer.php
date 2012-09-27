<?php

class Df_Customer_Model_Customer extends Mage_Customer_Model_Customer {


	/**
	 * @return Zend_Date|null
	 */
	public function getDateOfBirth () {

		/** @var string|null $dateAsString  */
		$dateAsString = $this->getData ('dob');

		/** @var Zend_Date $result  */
		$result = null;

		if (!df_empty ($dateAsString)) {
			$result =
				df_parse_mysql_datetime (
					$dateAsString
				)
			;
		}

		if (!is_null ($result)) {
			df_assert ($result instanceof Zend_Date);
		}

		return $result;

	}




	/**
	 * @return string
	 */
	public function getEmail () {
		return $this->getData('email');
	}





	/**
	 * @return string|null
	 */
	public function getGender () {

		/** @var string|null $result  */
		$result = $this->getData ('gender');

		if (!
			in_array (
				$result
				,
				array (
					self::GENDER__FEMALE
					,
					self::GENDER__MALE
				)
			)
		) {
			$result = null;
		}

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;
	}




	/**
	 * @return string
	 */
	public function getInn () {

		/** @var string $result  */
		$result =
			df_convert_null_to_empty_string (
				$this->getData ('taxvat')
			)
		;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return string|null
	 */
	public function getNameFirst () {
		return $this->getData('firstname');
	}




	/**
	 * @return string|null
	 */
	public function getNameLast () {
		return $this->getData('lastname');
	}




	/**
	 * @return string|null
	 */
	public function getNameMiddle () {
		return $this->getData('middlename');
	}




	/**
	 * @override
	 * @return bool|array
	 */
	public function validate () {

        if (!Zend_Validate::is($this->getDataUsingMethod('email'), 'EmailAddress')) {
			$this->setData ('email', $this->getFakedEmail());
        }

        if (!Zend_Validate::is( trim($this->getDataUsingMethod('firstname')) , 'NotEmpty')) {
			$this->setData ('firstname', 'Аноним');
        }

        if (!Zend_Validate::is( trim($this->getDataUsingMethod('lastname')) , 'NotEmpty')) {
			$this->setData ('lastname', 'Анонимов');
        }


		/** @var bool|array $result  */
		$result = parent::validate();

		return $result;

	}
	
	
	
	
	/**
	 * @return string
	 */
	private function getFakedEmail () {

		if (!isset ($this->_fakedEmail)) {

			/** @var string $result  */
			$result =
				implode (
					'@'
					,
					array (
								$this->getDataUsingMethod ('increment_id')
							?
								$this->getDataUsingMethod ('increment_id')
							:
								time()
						,
							Mage::app()->getStore() ->getConfig (
								self::XML_PATH_DEFAULT_EMAIL_DOMAIN
							)
					)
				)
			;


			df_assert_string ($result);

			$this->_fakedEmail = $result;

		}


		df_result_string ($this->_fakedEmail);

		return $this->_fakedEmail;

	}


	/**
	* @var string
	*/
	private $_fakedEmail;




	const GENDER__FEMALE = 'Female';
	const GENDER__MALE = 'Male';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Customer_Model_Customer';
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


