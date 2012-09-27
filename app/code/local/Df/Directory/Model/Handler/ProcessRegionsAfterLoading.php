<?php


class Df_Directory_Model_Handler_ProcessRegionsAfterLoading extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		self::addTypeToNameStatic ($this->getEvent()->getCollection());

		if (true !== ($this->getEvent()->getCollection()->getFlag (self::FLAG__PREVENT_REORDERING))) {
			$this->reorder();
		}

	}




	/**
	 * @return Df_Directory_Model_Handler_ProcessRegionsAfterLoading
	 */
	private function reorder () {


		/** @var Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection $originalCollection */
		$originalCollection = clone $this->getEvent()->getCollection();

		df_helper()->directory()->assert()->regionCollection ($originalCollection);



		/** @var array $priorityRegions  */
		$priorityRegions = $this->getPriorityRegions();

		df_assert_array ($priorityRegions);



		$this->clearCollection ();



		foreach ($priorityRegions as $priorityRegion) {

			/** @var Mage_Directory_Model_Region $priorityRegion */
			df_assert ($priorityRegion instanceof Mage_Directory_Model_Region);

			$originalCollection->removeItemByKey ($priorityRegion->getId ());

			$this->getEvent()->getCollection()->addItem ($priorityRegion);

		}


		foreach ($originalCollection as $region) {

			/** @var Mage_Directory_Model_Region $region */
			df_assert ($region instanceof Mage_Directory_Model_Region);

			$this->getEvent()->getCollection()->addItem ($region);

		}


		return $this;

	}




	/**
	 * @return Df_Directory_Model_Handler_ProcessRegionsAfterLoading
	 */
	private function clearCollection () {

		/**
		 * Очищаем коллекцию, но не используем для этого clear(),
		 * потому что clear() переводит коллекцию в незагруженное состояние
		 */
		foreach ($this->getEvent()->getCollection() as $region) {

			/** @var Mage_Directory_Model_Region $region */
			df_assert ($region instanceof Mage_Directory_Model_Region);

			$this->getEvent()->getCollection()->removeItemByKey ($region->getId ());

		}

		return $this;

	}




	/**
	 * @return array
	 */
	private function getPriorityRegions () {

		if (!isset ($this->_priorityRegions)) {


			/** @var array $ids  */
			$ids = array ();

			for ($i=1; $i <= Df_Directory_Helper_Settings_Regions_Ru::NUM_PRIORITY_REGIONS; $i++) {
				$ids []=
					df_cfg()->directory()->regions()->ru()
						->getPriorityRegionIdAtPosition ($i)
				;
			}

			$ids = df_clean ($ids, array (0));


			/** @var array $result  */
			$result =
				array ()
			;


			foreach ($ids as $id) {
				/** @var int $id */
				df_assert_integer ($id);

				$result []= $this->getEvent()->getCollection()->getItemById ($id);
			}


			$result = df_clean ($result);

			df_assert_array ($result);

			$this->_priorityRegions = $result;

		}


		df_result_array ($this->_priorityRegions);

		return $this->_priorityRegions;

	}


	/**
	* @var array
	*/
	private $_priorityRegions;





	/**
	 * @param string $name
	 * @return Mage_Directory_Model_Region|null
	 */
	private function getRegionByName ($name) {

		df_param_string ($name, 0);

		/** @var Mage_Directory_Model_Region|null $result  */
		$result = null;

		$name = mb_strtoupper ($name);

		df_assert_string ($name);

		foreach ($this->getEvent()->getCollection() as $region) {

			/** @var Mage_Directory_Model_Region $region */
			df_assert ($region instanceof Mage_Directory_Model_Region);

			$currentName = self::getRegionName ($region);

			df_assert_string ($currentName);


			$currentName = mb_strtoupper ($currentName);

			df_assert_string ($currentName);


			if (false !== strpos ($currentName, $name)) {
				$result = $region;
				break;
			}


		}

		if (!is_null ($result)) {
			df_assert ($result instanceof Mage_Directory_Model_Region);
		}

		return $result;

	}



	



	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter);

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
		$result = Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter::getClass();

		df_result_string ($result);

		return $result;

	}




	/**
	 * @param Varien_Data_Collection_Db $regions
	 */
	public static function addTypeToNameStatic (Varien_Data_Collection_Db $regions) {

		df_helper()->directory()->assert()->regionCollection ($regions);

		foreach ($regions as $region) {

			/** @var Mage_Directory_Model_Region $region */
			df_assert ($region instanceof Mage_Directory_Model_Region);


			/** @var string $originalName  */
			$originalName = self::getRegionName ($region);

			df_assert_string ($originalName);


			/** @var int $typeAsInteger  */
			$typeAsInteger = intval ($region->getData ('df_type'));

			df_assert_integer ($typeAsInteger);




			/** @var array $typesMap  */
			$typesMap =
				array (
					1 => 'Республика'
					,
					2 => 'край'
					,
					3 => 'область'
					,
					5 => 'автономная область'
					,
					6 => 'автономный округ'
				)
			;

			df_assert_array ($typesMap);




			/** @var $typeAsString $result  */
			$typeAsString =
				df_a ($typesMap, $typeAsInteger, Df_Core_Const::T_EMPTY)
			;

			df_assert_string ($typeAsString);


			/** @var array $nameParts  */
			$nameParts = array ($originalName, $typeAsString);


			/** @var string $processedName  */
			$processedName =
				implode (
					Df_Core_Const::T_SPACE
					,
					df_clean ($nameParts)
				)
			;


			$region
				->addData (
					array (
						'name' => $processedName
						,
						'default_name' => $processedName
						,
						'original_name' => $originalName
					)
				)
			;

		}
	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Model_Handler_ProcessRegionsAfterLoading';
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



	/**
	 * @param Mage_Directory_Model_Region $region
	 * @return string
	 */
	public static function getRegionName (Mage_Directory_Model_Region $region) {

		/** @var string $result  */
		$result = $region->getData ('name');

		if (df_empty ($result)) {
			$result = $region->getData ('default_name');
		}

		df_result_string ($result);

		return $result;

	}




	const FLAG__PREVENT_REORDERING = 'df_directory_handler_processRegionsAfterLoading.preventReordering';



}


