<?php

class Df_Zf_Form extends Zend_Form {


	/**
	 * @param array $data
	 * @return Df_Zf_Form
	 */
	public function setValues (array $data) {
		/**
		 * Обратите внимание, что isValid не только проверяет допустимость данных,
		 * но и устанавливает эти данные в форму
		 */
		if (!$this->isValid ($data)) {
			$this->throwValidationException ();
		}
		return $this;
	}




	/**
	 * @throws Df_Core_Exception_InvalidUserInput
	 * @return void
	 */
	protected function throwValidationException () {

		/** @var Df_Core_Exception_InvalidUserInput $result  */
		$exception = new Df_Core_Exception_InvalidUserInput ();

		df_assert ($exception instanceof Df_Core_Exception_InvalidUserInput);


		foreach ($this->getMessages() as $elementName => $messages) {

			/** @var string $elementName */
			df_assert_string ($elementName);

			/** @var array $messages */
			df_assert_array ($messages);


			//getAttrib

			if (!df_empty ($messages)) {

				/** @var Zend_Form_Element|null $element  */
				$element = $this->getElement ($elementName);

				df_assert ($element instanceof Zend_Form_Element);



				/** @var string|null $message  */
				$message =
					$element->getAttrib(
						Df_Zf_Form::FORM_ELEMENT_ATTRIB__MESSAGE_FOR_INVALID_VALUE_CASE
					)
				;

				if (!is_null ($message)) {
					df_assert_string ($message);
				}



				if (is_null ($message)) {
					$message =
						sprintf (
							"Вы указали недопустимое значение для поля «%s»."
							,
							$this->getElement ($elementName)->getLabel ()
						)
					;

					foreach ($messages as $concreteMessage) {

						/** @var string $concreteMessage  */
						df_assert_string ($concreteMessage);

						$message =
							implode (
								'<br/>'
								,
								array (
									$message
									,
									$concreteMessage
								)
							)
						;
					}
				}

				df_assert_string ($message);



				/** @var Mage_Core_Model_Message $coreMessage  */
				$coreMessage = df_model ("core/message");

				df_assert ($coreMessage instanceof Mage_Core_Model_Message);



				/** @var Df_Core_Model_Message_InvalidUserInput $invalidUserInputMessage  */
				$invalidUserInputMessage =
					new Df_Core_Model_Message_InvalidUserInput (
						$message
					)
				;

				df_assert (
						$invalidUserInputMessage
					instanceof
						Df_Core_Model_Message_InvalidUserInput
				);


				$invalidUserInputMessage->setElement ($element);


				$exception
					->addMessage(
						$invalidUserInputMessage
					)
				;
			}
		}



		throw $exception;
	}




	const FORM_ELEMENT_ATTRIB__MESSAGE_FOR_INVALID_VALUE_CASE = 'messageForInvalidValueCase';


}