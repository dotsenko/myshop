<?php


/**
 * Модуль «Удобное оформление заказа».
 * При отключении администратором видимости или обязательности заполнения
 * полей «Пароль» и «Пароль повторно»
 * система должна сама выбирать для покупателя пароль и отсылать его покупателю.
 */
class Df_Checkout_Model_Filter_Ergonomic_SetDefaultPassword
	extends Df_Core_Model_Abstract
	implements Zend_Filter_Interface {


    /**
     * @param  array $value
     * @return array
     */
    public function filter ($value) {

		df_param_array ($value, 0);

		if (
				df_cfg()->checkout()->_interface()->needShowAllStepsAtOnce()
			&&
				/**
				 * Убеждаемся, что покупатель неавторизован
				 */
				!df_mage()->customer()->isLoggedIn()
			&&
				/**
				 * Убеждаемся, что поля «Пароль» и «Пароль повторно» необязательны для заполнения
				 */
				(
						df_cfg()->checkout()->field()->applicability()->billing()->customer_password()
					!==
						Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__REQUIRED
				)
			&&
				(
						df_cfg()->checkout()->field()->applicability()->billing()->confirm_password()
					!==
						Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__REQUIRED
				)
			&&
				/**
				 * Убеждаемся, что поля «Пароль» и «Пароль повторно» не заполнены
				 */
				df_empty (df_a ($value, Df_Checkout_Const_Field::CUSTOMER_PASSWORD))
			&&
				df_empty (df_a ($value, Df_Checkout_Const_Field::CONFIRM_PASSWORD))

		) {


			$value [Df_Checkout_Const_Field::CUSTOMER_PASSWORD] = $this->getGeneratedPassword ();
			$value [Df_Checkout_Const_Field::CONFIRM_PASSWORD] = $this->getGeneratedPassword ();

			
			df_mage()->customer()->session()
				->setData (
					Df_Customer_Const_Session::GENERATED_PASSWORD, $this->getGeneratedPassword ()
				)
			;
		}

		df_result_array ($value);

        return $value;
    }

	
	

	
	
	/**
	 * @return string
	 */
	private function getGeneratedPassword () {
	
		if (!isset ($this->_generatedPassword)) {

			/** @var Mage_Customer_Model_Customer $customer */
			$customer = df_model (Df_Customer_Const::CUSTOMER_CLASS_MF);

			df_assert ($customer instanceof Mage_Customer_Model_Customer);


			/** @var string $result  */
			$result = $customer->generatePassword();
	
	
			df_assert_string ($result);
	
			$this->_generatedPassword = $result;
	
		}
	
	
		df_result_string ($this->_generatedPassword);
	
		return $this->_generatedPassword;
	
	}
	
	
	/**
	* @var string
	*/
	private $_generatedPassword;	
	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Filter_Ergonomic_SetDefaultPassword';
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

