<?php

class Df_Cms_Model_Handler_ContentsMenu_Insert extends Df_Core_Model_Handler {



	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		foreach ($this->getContentsMenus()->getPositions() as $position) {

			/** @var Df_Cms_Model_ContentsMenu_Collection $position */
			df_assert ($position instanceof Df_Cms_Model_ContentsMenu_Collection);


			foreach ($position as $contentsMenu) {

				/** @var Df_Cms_Model_ContentsMenu $contentsMenu */
				df_assert ($contentsMenu instanceof Df_Cms_Model_ContentsMenu);

				$contentsMenu->insertIntoLayout();

			}

		}

	}


	
	
	
	/**
	 * @return Df_Cms_Model_ContentsMenu_Collection
	 */
	private function getContentsMenus () {
	
		if (!isset ($this->_contentsMenus)) {
	
			/** @var Df_Cms_Model_ContentsMenu_Collection $result  */
			$result = 
				df_model (
					Df_Cms_Model_ContentsMenu_Collection::getNameInMagentoFormat()
				)
			;
	
			df_assert ($result instanceof Df_Cms_Model_ContentsMenu_Collection);


			$result->loadItemsForTheCurrentPage();

	
			$this->_contentsMenus = $result;
	
		}
	
	
		df_assert ($this->_contentsMenus instanceof Df_Cms_Model_ContentsMenu_Collection);
	
		return $this->_contentsMenus;
	
	}
	
	
	/**
	* @var Df_Cms_Model_ContentsMenu_Collection
	*/
	private $_contentsMenus;	
		






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
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_Handler_ContentsMenu_Insert';
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


