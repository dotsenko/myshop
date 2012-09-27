<?php

class Df_Cms_Model_ContentsMenu_Collection extends Df_Varien_Data_Collection {



	/**             
	 * Идентификатор нам нужен для формирования коллекции коллекций
	 * (а коллекции коллекций нужны для группировки коллекций меню 
	 * по местам их размещения на экране)
	 * 
	 * @return string
	 */
	public function getId () {

		/** @var string $result  */
		$result = $this->getFlag (self::PARAM__ID);

		df_result_string ($result);

		return $result;

	}





	/**
	 * Коллекции всех меню для конкретных мест на экране
	 *
	 * @return Df_Cms_Model_ContentsMenu_Collection
	 */
	public function getPositions () {

		if (!isset ($this->_positions)) {

			/** @var Df_Cms_Model_ContentsMenu_Collection $result  */
			$result =
				df_model (
					Df_Cms_Model_ContentsMenu_Collection::getNameInMagentoFormat()
				)
			;


			df_assert ($result instanceof Df_Cms_Model_ContentsMenu_Collection);

			$this->_positions = $result;

		}


		df_assert ($this->_positions instanceof Df_Cms_Model_ContentsMenu_Collection);

		return $this->_positions;

	}


	/**
	* @var Df_Cms_Model_ContentsMenu_Collection
	*/
	private $_positions;








	/**
	 * Обратите внимание, что для загрузки всех меню текущей страницы
	 * мы не переопределяем метод Varien_Data_Collection::loadData(),
	 * потому что у нас будут существовать и другие коллекции меню,
	 * которые будут содержать не все меню текущей страницы, а набор меню
	 * по другим критериям: в частности, все меню для конкретного места текущей страницы.
	 *
	 * @return Df_Cms_Model_ContentsMenu_Collection
	 */
	public function loadItemsForTheCurrentPage () {

		if (!$this->isLoaded ()) {

			$this
				->addItemsForTheCurrentPageApplicators ()

				->mergeItemsWithTheSamePosition ()

				->_setIsLoaded (true)
			;
		}

		return $this;

	}





	/**
	 * Этот метод предназначен не для коллекции всех меню,
	 * а для коллекции меню текущего места на экране.
	 *
	 * Метод объединяет меню, которые на экране расположены рядом.
	 *
	 * @return Df_Cms_Model_ContentsMenu_Collection
	 */
	public function mergeItems () {

		/** @var array $verticalOrderings  */
		$verticalOrderings = array ();

		foreach ($this->getItems() as $item) {

			/** @var Df_Cms_Model_ContentsMenu $item */
			df_assert ($item instanceof Df_Cms_Model_ContentsMenu);


			/** @var bool $merged  */
			$merged = false;


			foreach ($verticalOrderings as $currentVerticalOrdering => $currentMenu) {

				/** @var int $currentVerticalOrdering */
				/** @var Df_Cms_Model_ContentsMenu $currentMenu */

				if (2 > abs ($item->getVerticalOrdering() - $currentVerticalOrdering)) {

					$currentMenu->merge ($item);

					$this->removeItemByKey ($item->getId());

					$merged = true;

					break;
				}
			}


			if (!$merged) {

				$verticalOrderings [$item->getVerticalOrdering()] = $item;

			}
		}

		return $this;

	}








	/**
	 * @override
	 * @return string
	 */
	protected function getItemClass () {

		/** @var string $result */
		$result = Df_Cms_Model_ContentsMenu::getClass();

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return Df_Cms_Model_ContentsMenu_Collection
	 */
	private function addItemsForTheCurrentPageApplicators () {


		foreach ($this->getApplicators() as $applicator) {

			/** @var Df_Cms_Model_ContentsMenu_Applicator $applicator */
			df_assert ($applicator instanceof Df_Cms_Model_ContentsMenu_Applicator);

			/**
			 * Должна ли данная рубрика
			 * отображаться в каком-либо меню на текущей странице?
			 */

			if ($applicator->isApplicableToTheCurrentPage()) {


				/**
				 * Итак, рубрика должна отображаться в некем меню на текущей странице:
				 * либо в одном из уже присутствующик в коллекции меню, либо в новом меню.
				 *
				 * Нам проще для каждой рубрики добавлять новое меню,
				 * и лишь потом объединить несколько меню в одно.
				 */

				/** @var Df_Cms_Model_ContentsMenu $menu  */
				$menu =
					df_model (
						Df_Cms_Model_ContentsMenu::getNameInMagentoFormat()
						,
						array (
							Df_Cms_Model_ContentsMenu
								::PARAM__POSITION => $applicator->getPosition()
							,
							Df_Cms_Model_ContentsMenu
								::PARAM__VERTICAL_ORDERING => $applicator->getVerticalOrdering()
						)
					)
				;


				$menu->getApplicators()->addItem ($applicator);


				$this->addItem ($menu);

				$this->getPosition ($applicator->getPosition())->addItem ($menu);

			}

		}

		return $this;

	}



	
	
	
	
	/**
	 * @return array
	 */
	private function getApplicators () {
	
		if (!isset ($this->_applicators)) {
	
			/** @var array $result  */
			$result = array ();


			foreach ($this->getCmsRootNodes() as $cmsRootNode) {

				/** @var Df_Cms_Model_Hierarchy_Node $cmsRootNode */
				df_assert ($cmsRootNode instanceof Df_Cms_Model_Hierarchy_Node);


				/** @var Df_Cms_Model_ContentsMenu_Applicator $applicator  */
				$applicator =
					df_model (
						Df_Cms_Model_ContentsMenu_Applicator::getNameInMagentoFormat()
						,
						array (
							Df_Cms_Model_ContentsMenu_Applicator::PARAM__NODE => $cmsRootNode
						)
					)
				;

				df_assert ($applicator instanceof Df_Cms_Model_ContentsMenu_Applicator);


				$result []= $applicator;


				/**
				 * Должна ли данная рубрика
				 * отображаться в каком-либо меню на текущей странице?
				 */

			}
	
	
			df_assert_array ($result);
	
			$this->_applicators = $result;
	
		}
	
	
		df_result_array ($this->_applicators);
	
		return $this->_applicators;
	
	}
	
	
	/**
	* @var array
	*/
	private $_applicators;	
	
	
	





	/**
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node_Collection
	 */
	private function getCmsRootNodes () {

		if (!isset ($this->_cmsRootNodes)) {

			/** @var Df_Cms_Model_Mysql4_Hierarchy_Node_Collection $result  */
			$result =
				Mage::getResourceModel (
					Df_Cms_Model_Mysql4_Hierarchy_Node_Collection::CLASS_MF
				)
			;

			df_assert ($result instanceof Df_Cms_Model_Mysql4_Hierarchy_Node_Collection);


			$result
				->addStoreFilter (
					Mage::app()->getStore()
					,
					false
				)
				->addRootNodeFilter()
				->joinMetaData ()
				->joinCmsPage()
			;



			$this->_cmsRootNodes = $result;

		}


		df_assert ($this->_cmsRootNodes instanceof Df_Cms_Model_Mysql4_Hierarchy_Node_Collection);

		return $this->_cmsRootNodes;

	}


	/**
	* @var Df_Cms_Model_Mysql4_Hierarchy_Node_Collection
	*/
	private $_cmsRootNodes;





	/**
	 * @param string $position
	 * @return Df_Cms_Model_ContentsMenu_Collection
	 */
	private function getPosition ($position) {

		df_param_string ($position, 0);

		/** @var Df_Cms_Model_ContentsMenu_Collection $result  */
		$result =
			$this->getPositions()->getItemById (
				$position
			)
		;

		if (is_null ($result)) {

			$result =
				df_model (
					Df_Cms_Model_ContentsMenu_Collection::getNameInMagentoFormat()
				)
			;

			$result->setFlag (Df_Cms_Model_ContentsMenu_Collection::PARAM__ID, $position);

			$this->getPositions()->addItemNotVarienObject ($result);
		}

		df_assert ($result instanceof Df_Cms_Model_ContentsMenu_Collection);

		return $result;

	}






	/**
	 * Ранее мы создали отдельное меню для каждой из корневых рубрик,
	 * которые должны отображаться в меню на текущей странице.
	 *
	 * Теперь надо объединить те меню,
	 * которые на экране будут расположены на одном и том же месте.
	 *
	 * @return Df_Cms_Model_ContentsMenu_Collection
	 */
	private function mergeItemsWithTheSamePosition () {


		foreach ($this->getPositions() as $position) {

			/** @var Df_Cms_Model_ContentsMenu_Collection $position */
			df_assert ($position instanceof Df_Cms_Model_ContentsMenu_Collection);

			$position->mergeItems ();

		}

		return $this;

	}







	const PARAM__ID = 'id';







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_ContentsMenu_Collection';
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
