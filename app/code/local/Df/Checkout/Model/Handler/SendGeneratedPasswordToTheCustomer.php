<?php

class Df_Checkout_Model_Handler_SendGeneratedPasswordToTheCustomer extends Df_Core_Model_Handler {



	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		if (
				df_cfg()->checkout()->_interface()->needShowAllStepsAtOnce()
			&&
				!df_empty ($this->getGeneratedPassword ())
		) {

 			$this->getMailer()->send ();

			/**
			 * Важно!
			 * Удаляем пароль из сессии после отсылки,
			 * чтобы потом система не пыталась создавать клиенту пароль повторно.
			 */
			df_mage()->customer()->session()
				->unsetData (
					Df_Customer_Const_Session::GENERATED_PASSWORD
				)
			;

		}

	}





	/**
	 * @return Mage_Customer_Model_Customer
	 */
	private function getCustomer () {

		if (!isset ($this->_customer)) {

			/** @var Mage_Customer_Model_Customer $result  */
			$result =
				df_model (
					Df_Customer_Const::CUSTOMER_CLASS_MF
				)
			;

			$result->load ($this->getEvent()->getOrder()->getCustomerId());

			df_assert ($result instanceof Mage_Customer_Model_Customer);

			$this->_customer = $result;

		}


		df_assert ($this->_customer instanceof Object);

		return $this->_customer;

	}


	/**
	* @var Mage_Customer_Model_Customer
	*/
	private $_customer;






	
	/**
	 * @return Mage_Core_Model_Email_Template_Mailer
	 */
	private function getMailer () {
	
		if (!isset ($this->_mailer)) {
	
			/** @var Mage_Core_Model_Email_Template_Mailer $result  */
			$result = 
				df_model (
					'core/email_template_mailer'
				)
			;
	
	
			df_assert ($result instanceof Mage_Core_Model_Email_Template_Mailer);


			$result->addEmailInfo ($this->getMailInfo());

			$result->setSender ($this->getMailSender());

			$result->setTemplateId ($this->getMailTemplateId());

			$result
				->setTemplateParams (
					array (
						'password' => $this->getGeneratedPassword()
						,
						'email' => $this->getEvent()->getOrder()->getCustomerEmail()
						,
						'name' => $this->getEvent()->getOrder()->getCustomerName()
					)
				)
			;
	
			$this->_mailer = $result;
	
		}
	
	
		df_assert ($this->_mailer instanceof Mage_Core_Model_Email_Template_Mailer);
	
		return $this->_mailer;
	
	}
	
	
	/**
	* @var Mage_Core_Model_Email_Template_Mailer
	*/
	private $_mailer;





	/**
	 * @return Mage_Core_Model_Email_Info
	 */
	private function getMailInfo () {

		if (!isset ($this->_mailInfo)) {

			/** @var Mage_Core_Model_Email_Info $result  */
			$result =
				df_model (
					'core/email_info'
				)
			;

			df_assert ($result instanceof Mage_Core_Model_Email_Info);


			$result
				->addTo (
					$this->getEvent()->getOrder()->getCustomerEmail()
					,
					$this->getEvent()->getOrder()->getCustomerName()
				)
			;

			$this->_mailInfo = $result;

		}

		df_assert ($this->_mailInfo instanceof Mage_Core_Model_Email_Info);

		return $this->_mailInfo;

	}


	/**
	* @var Mage_Core_Model_Email_Info
	*/
	private $_mailInfo;





	/**
	 * @return string
	 */
	private function getMailSender () {

		/** @var string $result  */
		$result = Mage::getStoreConfig (Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY, $this->getStore());

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getMailTemplateId () {

		/** @var string $result  */
		$result = Mage::getStoreConfig ('df_checkout/email/generated_password', $this->getStore());

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Mage_Core_Model_Store
	 */
	private function getStore () {

		/** @var Mage_Core_Model_Store $result  */
		$result =
			$this->getEvent()->getOrder()->getStore()
		;

		df_assert ($result instanceof Mage_Core_Model_Store);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getGeneratedPassword () {

		/** @var string $result  */
		$result =
			df_string (
				df_mage()->customer()->session()->getData (
					Df_Customer_Const_Session::GENERATED_PASSWORD
				)
			)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @override
	 * @return Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrderAfter
	 */
	protected function getEvent () {

		/** @var Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrderAfter $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrderAfter);

		return $result;
	}





	/**
	 * Класс события (для валидации события)
	 *
	 * @override
	 * @return string
	 */
	protected function getEventClass () {

		/** @var string $result  */
		$result = Df_Checkout_Model_Event_CheckoutTypeOnepage_SaveOrderAfter::getClass();

		df_result_string ($result);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Handler_SendGeneratedPasswordToTheCustomer';
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


