<?php


/**
 * @singleton
 * Система создаёт объект-одиночку для потомков этого класса.
 * Не забывайте об этом при реализации кеширования результатов вычислений внутри этого класса!
 */
class Df_Pec_Model_Config_Source_MoscowCargoReceptionPoint extends Df_Admin_Model_Config_Source {




	/**
	 * @override
	 * @param bool $isMultiSelect
	 * @return array
	 */
    protected function toOptionArrayInternal ($isMultiSelect = false) {

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
	
			
			/** @var array $result  */
			$result =
				array (

					array (
						self::OPTION_KEY__LABEL => 'за пределами перечисленного в других пунктах'
						,
						self::OPTION_KEY__VALUE => self::OPTION_VALUE__OUTSIDE
					)

					,
					array (
						self::OPTION_KEY__LABEL => 'внутри малого кольца Московской железной дороги'
						,
						self::OPTION_KEY__VALUE => self::OPTION_VALUE__INSIDE_LITTLE_RING_RAILWAY
					)

					,
					array (
						self::OPTION_KEY__LABEL => 'внутри Третьего транспортнонр кольца'
						,
						self::OPTION_KEY__VALUE => self::OPTION_VALUE__INSIDE_THIRD_RING_ROAD
					)

					,
					array (
						self::OPTION_KEY__LABEL => 'внутри Садового кольца'
						,
						self::OPTION_KEY__VALUE => self::OPTION_VALUE__INSIDE_GARDEN_RING
					)

				)
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



	const OPTION_VALUE__OUTSIDE = 'outside';
	const OPTION_VALUE__INSIDE_LITTLE_RING_RAILWAY = 'inside_little_ring_railway';
	const OPTION_VALUE__INSIDE_THIRD_RING_ROAD = 'inside_third_ring_road';
	const OPTION_VALUE__INSIDE_GARDEN_RING = 'inside_garden_ring';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pec_Model_Config_Source_MoscowCargoReceptionPoint';
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