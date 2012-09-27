<?php


class Df_Tweaks_Model_Handler_Footer_AdjustCopyright extends Df_Core_Model_Handler {



	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		if (
				df_cfg()->tweaks()->footer ()->getUpdateYearInCopyright ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->setCopyright (
					strtr (
						$this->getBlock()->getCopyright ()
						,
						array (
							'{currentYear}' =>
								Zend_Date::now ()->toString (
									Zend_Date::YEAR
								)
						)
					)
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
	 * @return Mage_Page_Block_Html_Footer|null
	 */
	private function getBlock () {
	
		if (!isset ($this->_block) && !$this->_blockIsNull) {
	
			/** @var Mage_Page_Block_Html_Footer|null $result  */
			$result = 
				$this->getEvent()->getLayout()->getBlock ('footer')
			;

			if (false === $result) {
				$result = null;
			}
	
	
			if (!is_null ($result)) {
				df_assert ($result instanceof Mage_Page_Block_Html_Footer);
			}
			else {
				$this->_blockIsNull = true;
			}
	
			$this->_block = $result;
	
		}
	
	
		if (!is_null ($this->_block)) {
			df_assert ($this->_block instanceof Mage_Page_Block_Html_Footer);
		}		
		
	
		return $this->_block;
	
	}
	
	
	/**
	* @var Mage_Page_Block_Html_Footer|null
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
		return 'Df_Tweaks_Model_Handler_Footer_AdjustCopyright';
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


