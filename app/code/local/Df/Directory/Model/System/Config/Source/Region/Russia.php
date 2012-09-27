<?php



class Df_Directory_Model_System_Config_Source_Region_Russia extends Df_Core_Model_Abstract {




	/**
	 * @return array
	 */
    public function toOptionArray() {

		/** @var array $result  */
		$result = $this->getAsOptionArray();

		df_result_array ($result);

		return $result;

    }






	/**
	 * @return array
	 */
	private function getAsOptionArray () {

		if (!isset ($this->_asOptionArray)) {

			/** @var Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection $regions */
			$regions = df_helper()->directory()->country()->getRussia()->getRegionCollection();

			df_helper()->directory()->assert()->regionCollection($regions);


			$regions
				->setFlag (
					Df_Directory_Model_Handler_ProcessRegionsAfterLoading::FLAG__PREVENT_REORDERING
					,
					true
				)
			;


			/** @var array $result  */
			$result =
				$regions->toOptionArray()
			;


			df_assert_array ($result);

			$this->_asOptionArray = $result;

		}


		df_result_array ($this->_asOptionArray);

		return $this->_asOptionArray;

	}


	/**
	* @var array
	*/
	private $_asOptionArray;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Model_System_Config_Source_Region_Russia';
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

