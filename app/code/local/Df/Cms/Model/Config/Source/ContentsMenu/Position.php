<?php


/**
 * @singleton
 * Система создаёт объект-одиночку для потомков этого класса.
 * Не забывайте об этом при реализации кеширования результатов вычислений внутри этого класса!
 */
class Df_Cms_Model_Config_Source_ContentsMenu_Position extends Df_Admin_Model_Config_Source {




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
						self::OPTION_KEY__LABEL => df_helper()->cms()->__('Content')
						,
						self::OPTION_KEY__VALUE => self::CONTENT
					)

					,
					array (
						self::OPTION_KEY__LABEL => df_helper()->cms()->__('Left Column')
						,
						self::OPTION_KEY__VALUE => self::LEFT
					)

					,
					array (
						self::OPTION_KEY__LABEL => df_helper()->cms()->__('Right Column')
						,
						self::OPTION_KEY__VALUE => self::RIGHT
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



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_Config_Source_ContentsMenu_Position';
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



	const CONTENT = 'content';
	const LEFT = 'left';
	const RIGHT = 'right';


}