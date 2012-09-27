<?php



class Df_Pel_Helper_Lib extends Df_Core_Helper_Lib_Abstract {

	public function __construct () {
		parent::__construct();
		if (0 != intval (ini_get ("mbstring.func_overload"))) {
			throw new Exception ("Df_Pel: you must disable mbstring.func_overload!");
		}
		return $this;
	}


	/**
	 * @return int
	 */
	protected function getIncompatibleErrorLevels () {
		if (!defined('E_DEPRECATED')) {
			define('E_DEPRECATED', 8192);
		}
		return E_STRICT | E_NOTICE | E_WARNING | E_DEPRECATED;
	}


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pel_Helper_Lib';
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
