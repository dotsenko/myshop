<?php

abstract class Df_Vk_Helper_Settings_Widget extends Df_Core_Helper_Settings {


	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getWidgetType ();


	/**
	 * @return boolean
	 */
	public function getEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				$this->getConfigKey ('enabled')
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return string|null
	 */
	public function getCode () {

		/** @var string|null $result  */
		$result =
			Mage::getStoreConfig (
				$this->getConfigKey ('code')
			)
		;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;
	}




	/**
	 * @param string $configKeyShort
	 * @return string
	 */
	private function getConfigKey ($configKeyShort) {

		/** @var string $result  */
		$result =
			df()->config()->implodeKey (
				array (
					'df_vk'
					,
					$this->getWidgetType()
					,
					$configKeyShort
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
		return 'Df_Vk_Helper_Settings_Widget';
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