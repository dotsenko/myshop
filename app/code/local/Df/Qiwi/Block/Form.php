<?php

class Df_Qiwi_Block_Form extends Df_Payment_Block_Form {


    /**
     * Перекрываем метод лишь для того,
	 * чтобы среда разработки знала класс способа оплаты
     *
	 * @override
     * @return Df_Qiwi_Model_Payment
     */
	public function getMethod() {

		/** @var Df_Qiwi_Model_Payment $result  */
		$result = parent::getMethod();

		df_assert ($result instanceof Df_Qiwi_Model_Payment);

		return $result;
	}
	
	
	
	
	
	/**
	 * @return string
	 */
	public function getQiwiCustomerPhoneNetworkCode () {
	
		if (!isset ($this->_qiwiCustomerPhoneNetworkCode)) {
	
			/** @var string $result  */
			$result = 
				substr (
					$this->getQiwiCustomerPhone ()
					,
					0
					,
					3
				)
			;

			if (false === $result) {
				$result = Df_Core_Const::T_EMPTY;
			}
	
			df_assert_string ($result);
	
			$this->_qiwiCustomerPhoneNetworkCode = $result;
	
		}
	
	
		df_result_string ($this->_qiwiCustomerPhoneNetworkCode);
	
		return $this->_qiwiCustomerPhoneNetworkCode;
	
	}
	
	
	/**
	* @var string
	*/
	private $_qiwiCustomerPhoneNetworkCode;	
	
	
	
	
	
	/**
	 * @return string
	 */
	public function getQiwiCustomerPhoneSuffix () {
	
		if (!isset ($this->_qiwiCustomerPhoneSuffix)) {
	
			/** @var string $result  */
			$result = 
				substr (
					$this->getQiwiCustomerPhone ()
					,
					3
				)
			;

			if (false === $result) {
				$result = Df_Core_Const::T_EMPTY;
			}
	
			df_assert_string ($result);
	
			$this->_qiwiCustomerPhoneSuffix = $result;
	
		}
	
	
		df_result_string ($this->_qiwiCustomerPhoneSuffix);
	
		return $this->_qiwiCustomerPhoneSuffix;
	
	}
	
	
	/**
	* @var string
	*/
	private $_qiwiCustomerPhoneSuffix;		
	





	/**
	 * @return string
	 */
	public function getQiwiCustomerPhoneNetworkCodeCssClassesAsString () {

		/** @var string $result  */
		$result =
			df_output()->getCssClassesAsString (
				array (
					'input-text'
					,
					'df-phone-network-code'
					,
					'required-entry'
					,
					'validate-digits'
					,
					'validate-length'
					,
					'minimum-length-3'
					,
					'maximum-length-3'
				)
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	public function getQiwiCustomerPhoneSuffixCssClassesAsString () {

		/** @var string $result  */
		$result =
			df_output()->getCssClassesAsString (
				array (
					'input-text'
					,
					'df-phone-suffix'
					,
					'required-entry'
					,
					'validate-digits'
					,
					'validate-length'
					,
					'minimum-length-7'
					,
					'maximum-length-7'
				)
			)
		;


		df_result_string ($result);

		return $result;

	}






	/**
	 * @override
	 * @return string
	 */
	public function getTemplate () {

		/** @var string $result  */
		$result = self::RM__TEMPLATE;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return Df_Core_Model_Format_MobilePhoneNumber
	 */
	private function getBillingAddressPhone () {

		if (!isset ($this->_billingAddressPhone)) {

			/** @var Df_Core_Model_Format_MobilePhoneNumber $result  */
			$result =
				Df_Core_Model_Format_MobilePhoneNumber::fromQuoteAddress (
					$this->getQuote()->getBillingAddress()
				)
			;


			df_assert ($result instanceof Df_Core_Model_Format_MobilePhoneNumber);

			$this->_billingAddressPhone = $result;

		}


		df_assert ($this->_billingAddressPhone instanceof Df_Core_Model_Format_MobilePhoneNumber);

		return $this->_billingAddressPhone;

	}


	/**
	* @var Df_Core_Model_Format_MobilePhoneNumber
	*/
	private $_billingAddressPhone;





	/**
	 * @return string
	 */
	private function getQiwiCustomerPhone () {

		/** @var string $result  */
		$result = $this->getMethod()->getQiwiCustomerPhone();

		if (df_empty ($result)) {

			if ($this->getBillingAddressPhone()->isValid()) {
				$result = $this->getBillingAddressPhone()->getOnlyDigitsWithoutCallingCode();
			}
			elseif ($this->getShippingAddressPhone()->isValid()) {
				$result = $this->getShippingAddressPhone()->getOnlyDigitsWithoutCallingCode();
			}
			else {
				$result = Df_Core_Const::T_EMPTY;
			}
		}

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Df_Core_Model_Format_MobilePhoneNumber
	 */
	private function getShippingAddressPhone () {

		if (!isset ($this->_shippingAddressPhone)) {

			/** @var Df_Core_Model_Format_MobilePhoneNumber $result  */
			$result =
				df_model (
					Df_Core_Model_Format_MobilePhoneNumber::getNameInMagentoFormat()
					,
					array (
						Df_Core_Model_Format_MobilePhoneNumber::PARAM__VALUE =>
							$this->getQuote()->getShippingAddress()->getTelephone()
						,
						Df_Core_Model_Format_MobilePhoneNumber::PARAM__COUNTRY
					)
				)
			;

			df_assert ($result instanceof Df_Core_Model_Format_MobilePhoneNumber);

			$this->_shippingAddressPhone = $result;

		}


		df_assert ($this->_shippingAddressPhone instanceof Df_Core_Model_Format_MobilePhoneNumber);

		return $this->_shippingAddressPhone;

	}


	/**
	* @var Df_Core_Model_Format_MobilePhoneNumber
	*/
	private $_shippingAddressPhone;




	const RM__TEMPLATE = 'df/qiwi/form.phtml';

	const T_FIELD_LABEL__QIWI_CUSTOMER_ID = 'Номер телефона';


	const T_FIELD_LABEL__QIWI_CUSTOMER_PHONE__NETWORK_CODE = 'Код:';
	const T_FIELD_LABEL__QIWI_CUSTOMER_PHONE__SUFFIX = 'Номер:';


	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Qiwi_Block_Form';
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


