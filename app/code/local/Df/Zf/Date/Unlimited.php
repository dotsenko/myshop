<?php


class Df_Zf_Date_Unlimited extends Zend_Date {


	/**
	 * @static
	 * @param Zend_Date $date
	 * @return bool
	 */
	public static function isUnlimited (Zend_Date $date) {
		return $date->equals (self::i());
	}


	/**
	 * @var Df_Zf_Date_Unlimited
	 */
	private static $_i;

	/**
	 * @static
	 * @return Df_Zf_Date_Unlimited
	 */
	public static function i () {
		if (!isset (self::$_i)) {
			$className = get_class ();
		    self::$_i = new $className ();
		}
	    return self::$_i;
	}


	public function __construct () {

		/** @var string $timezone  */
		$timezone = date_default_timezone_get ();

		date_default_timezone_set (Mage_Core_Model_Locale::DEFAULT_TIMEZONE);

		parent::__construct ("2050-01-01");

		date_default_timezone_set ($timezone);
	}


}