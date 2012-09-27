<?php


class Df_Reports_Model_Handler_GroupResultsByWeek_PrepareCollection
	extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		if (
				$this->isItReportCollection()
			&&
				df_enabled (Df_Core_Feature::REPORTS)
			&&
				df_cfg()->reports()->common()->enableGroupByWeek()
			&&
				df_helper()->reports()->groupResultsByWeek()->isSelectedInFilter()
		) {

			if (!$this->getReportCollection()->isTotals()) {
				$this->adjustGroupPart ();
			}


			$this->adjustColumns ();

		}
	}




	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore);

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
		$result = Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore::getClass();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return Df_Reports_Model_Handler_GroupResultsByWeek_PrepareCollection
	 */
	private function adjustColumns () {

		/** @var array $partColumns  */
		$partColumns= $this->getSelect()->getPart (Zend_Db_Select::COLUMNS);

		df_assert_array ($partColumns);


		$this->getSelect()->reset (Zend_Db_Select::COLUMNS);



		foreach ($partColumns as &$column) {

			/** @var array $column */
			df_assert_array ($column);


			/** @var string|null $columnName  */
			$columnName = df_a ($column, 2);

			if (!is_null ($columnName)) {

				df_assert_string ($columnName);

				if ('period' === $columnName) {
					$column [1] = new Zend_Db_Expr ('WEEK(period)');
				}

			}

		}



		$this->getSelect()->setPart (Zend_Db_Select::COLUMNS, $partColumns);


		return $this;

	}







	/**
	 * @return Df_Reports_Model_Handler_GroupResultsByWeek_PrepareCollection
	 */
	private function adjustGroupPart () {

		/** @var array $partGroup  */
		$partGroup = $this->getSelect()->getPart (Zend_Db_Select::GROUP);

		df_assert_array ($partGroup);


		$this->getSelect()->reset (Zend_Db_Select::GROUP);


		$cleanedPartGroup = array ();

		foreach ($partGroup as $partGroupItem) {

			if (is_string ($partGroupItem)) {

				if (false !== strpos ($partGroupItem, 'period')) {
					continue;
				}

			}

			$cleanedPartGroup []= $partGroupItem;

		}


		$cleanedPartGroup []= 'WEEK(period)';


		foreach ($cleanedPartGroup as $cleanedPartGroupItem) {

			$this->getSelect()->group ($cleanedPartGroupItem);

		}

		return $this;

	}





	/**
	 * @return Varien_Db_Select
	 */
	private function getSelect () {

		/** @var Varien_Db_Select $result  */
		$result =
			$this->getReportCollection()->getSelect()
		;

		df_assert ($result instanceof Varien_Db_Select);

		return $result;

	}








	/**
	 * @return Mage_Sales_Model_Resource_Report_Collection_Abstract|Mage_Sales_Model_Mysql4_Report_Collection_Abstract
	 */
	private function getReportCollection () {

		/** @var Object $result  */
		$result =
			$this->getEvent()->getCollection()
		;

		df_assert ($this->isItReportCollection());

		return $result;

	}


	
	
	
	
	
	/**
	 * @return bool
	 */
	private function isItReportCollection () {
	
		if (!isset ($this->_itReportCollection)) {
	
			/** @var bool $result  */
			$result = 
					@class_exists ('Mage_Sales_Model_Resource_Report_Collection_Abstract')
				?
					($this->getEvent()->getCollection() instanceof Mage_Sales_Model_Resource_Report_Collection_Abstract)
				:
					($this->getEvent()->getCollection() instanceof Mage_Sales_Model_Mysql4_Report_Collection_Abstract)
			;
	
	
			df_assert_boolean ($result);
	
			$this->_itReportCollection = $result;
	
		}
	
	
		df_result_boolean ($this->_itReportCollection);
	
		return $this->_itReportCollection;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_itReportCollection;		
		


	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Model_Handler_GroupResultsByWeek_PrepareCollection';
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


