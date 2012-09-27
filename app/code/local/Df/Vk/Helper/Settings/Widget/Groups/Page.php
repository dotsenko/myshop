<?php

class Df_Vk_Helper_Settings_Widget_Groups_Page extends Df_Core_Helper_Settings {




	/**
	 * @return boolean
	 */
	public function getEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				$this->getConfigKey ('show')
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return int
	 */
	public function getPosition () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					$this->getConfigKey ('vertical_ordering')
				)
			)
		;

		df_result_integer ($result);

		return $result;
	}





	/**
	 * @return string
	 */
	public function getColumn () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				$this->getConfigKey ('column')
			)
		;

		df_result_string ($result);

		return $result;
	}







	/**
	 * @param string $type
	 * @return Df_Vk_Helper_Settings_Widget_Groups_Page
	 */
	public function setType ($type) {

		df_param_string ($type, 0);

		$this->_type = $type;


		return $this;

	}





	/**
	 * @return string
	 */
	public function getType () {

		/** @var string $result  */
		$result = $this->_type;

		df_result_string ($result);

		return $result;

	}




	/**
	* @var string
	*/
	private $_type;







	/**
	 * @param string $uniquePart
	 * @return string
	 */
	private function getConfigKey ($uniquePart) {

		df_param_string ($uniquePart, 0);

		/** @var string $result  */
		$result =
			df()->config()->implodeKey (
				array (
					'df_vk'
					,
					'groups'
					,
					implode (
						Df_Core_Const::T_CONFIG_WORD_SEPARATOR
						,
						array (
							$this->getType()
							,
							'page'
							,
							$uniquePart
						)
					)
				)
			)
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Vk_Helper_Settings_Widget_Groups_Page';
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