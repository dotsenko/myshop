<?php


class Df_Reports_Model_Handler_RemoveTimezoneNotice extends Df_Core_Model_Handler {



	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		if ($this->getMessagesCollection()) {

			/** @var Mage_Core_Model_Message_Collection $newCollection */
			$newCollection = clone $this->getMessagesCollection();

			df_assert ($newCollection instanceof Mage_Core_Model_Message_Collection);


			$this->getMessagesCollection()->clear();


			foreach ($newCollection->getItems() as $message) {

				/** @var Mage_Core_Model_Message_Abstract $message */
				df_assert ($message instanceof Mage_Core_Model_Message_Abstract);

				if (
						(Mage_Core_Model_Message::NOTICE === $message->getType ())
					&&
						(
								FALSE
							!==
								mb_strpos (
									$message->getCode ()
									,
									df_mage()->adminhtmlHelper()->__ (
										'This report depends on timezone configuration. Once timezone is changed, the lifetime statistics need to be refreshed.'
									)
								)
						)

				) {
					continue;
				}

				$this->getMessagesCollection()->add ($message);

			}

		}
	}
	



	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter);

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
		$result = Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter::getClass();

		df_result_string ($result);

		return $result;

	}
	
	
	
	
	
	
	/**
	 * @return Mage_Core_Model_Message_Collection|null
	 */
	private function getMessagesCollection () {
	
		if (!isset ($this->_messagesCollection)) {
	
			/** @var Mage_Core_Model_Message_Collection|null $result  */
			$result = 
					is_null ($this->getMessagesBlock())
				?
					null
				:
					$this->getMessagesBlock()->getMessageCollection()
			;

			if (!is_null ($result)) {
				df_assert ($result instanceof Mage_Core_Model_Message_Collection);
			}
	
			$this->_messagesCollection = $result;
	
		}


		if (!is_null ($this->_messagesCollection)) {
			df_assert ($this->_messagesCollection instanceof Mage_Core_Model_Message_Collection);
		}

	
		return $this->_messagesCollection;
	
	}
	
	
	/**
	* @var Mage_Core_Model_Message_Collection|null
	*/
	private $_messagesCollection;	
	
	

	
	
	
	
	/**
	 * @return Mage_Core_Block_Messages|null
	 */
	private function getMessagesBlock () {
	
		if (!isset ($this->_messagesBlock)) {
	
			/** @var Mage_Core_Block_Messages|null $result  */
			$result = 
				$this->getEvent()->getLayout()->getBlock('messages')
			;

			if (false === $result) {
				$result = null;
			}

			if (!is_null ($result)) {
				df_assert ($result instanceof Mage_Core_Block_Messages);
			}
	
			$this->_messagesBlock = $result;
	
		}


		if (!is_null ($this->_messagesBlock)) {
			df_assert ($this->_messagesBlock instanceof Mage_Core_Block_Messages);
		}

	
		return $this->_messagesBlock;
	
	}
	
	
	/**
	* @var Mage_Core_Block_Messages|null
	*/
	private $_messagesBlock;	
	





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Model_Handler_RemoveTimezoneNotice';
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


