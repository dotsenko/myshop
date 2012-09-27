<?php


class Df_Tweaks_Model_Handler_Footer_AdjustLinks extends Df_Core_Model_Handler {



	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		/**
		 * Обратите внимание, что мы не вынесли условие !is_null ($this->getBlock()
		 * вверх, потому что не хотим, чтобы его программный код исполнялся
		 * при отключенных функциях модуля Df_Tweaks
		 */

		if (
				df_cfg()->tweaks()->footer ()->getRemoveAdvancedSearchLink ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->removeLinkByUrl (
					df_mage()->catalogSearchHelper()->getAdvancedSearchUrl ()
				)
			;
		}


		if (
				df_cfg()->tweaks()->footer ()->getRemoveSearchTermsLink ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->removeLinkByUrl (
					df_mage()->catalogSearchHelper()->getSearchTermUrl ()
				)
			;
		}

	}




	
	


	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @override
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
	 * @return Df_Page_Block_Template_Links|null
	 */
	private function getBlock () {
	
		if (!isset ($this->_block) && !$this->_blockIsNull) {
	
			/** @var Df_Page_Block_Template_Links|null $result  */
			$result = 
				$this->getEvent()->getLayout()->getBlock ('footer_links')
			;

			if (false === $result) {
				$result = null;
			}


			if (!($result instanceof Df_Page_Block_Template_Links)) {
				/**
				 * Кто-то перекрыл класс Mage_Page_Block_Template_Links
				 */
				$result = null;
			}
	
	
			if (!is_null ($result)) {
				df_assert ($result instanceof Df_Page_Block_Template_Links);
			}
			else {
				$this->_blockIsNull = true;
			}
	
			$this->_block = $result;
	
		}
	
	
		if (!is_null ($this->_block)) {
			df_assert ($this->_block instanceof Df_Page_Block_Template_Links);
		}		
		
	
		return $this->_block;
	
	}
	
	
	/**
	* @var Df_Page_Block_Template_Links|null
	*/
	private $_block;	
	
	/**
	 * @var bool
	 */
	private $_blockIsNull = false;	
	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Model_Handler_Footer_AdjustLinks';
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


