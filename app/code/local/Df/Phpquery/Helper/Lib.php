<?php



class Df_Phpquery_Helper_Lib extends Df_Core_Helper_Lib_Abstract {

	/**
	 * @return Df_Phpquery_Helper_Lib
	 */
	public function init () {


		static $initialized = false;

		if (!$initialized) {
			$this->includeScript("phpQuery");
			$initialized = true;
		}

		return $this;
	}


	/**
	 * @return int
	 */
	protected function getIncompatibleErrorLevels () {
		return E_NOTICE;
	}


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Phpquery_Helper_Lib';
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
