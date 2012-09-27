<?php


class Df_Zf_Date_Least extends Zend_Date {


	/**
	 * @static
	 * @param Zend_Date $date
	 * @return bool
	 */
	public static function isLeast (Zend_Date $date) {
		return $date->equals (self::i());
	}


	/**
	 * @var Df_Zf_Date_Least
	 */
	private static $_i;

	/**
	 * @static
	 * @return Df_Zf_Date_Least
	 */
	public static function i () {
		if (!isset (self::$_i)) {
			$className = get_class ();
		    self::$_i = new $className ();
		}
	    return self::$_i;
	}


	public function __construct () {
		parent::__construct (0);
	}


}