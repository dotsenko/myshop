<?php


class Df_Zf_Helper_Db extends Mage_Core_Helper_Abstract {


	/**
	 * @static
	 * @param Zend_Db_Expr|string|null $value
	 * @return bool
	 */
	public static function isNull ($value) {

		/** @var bool $result */
		$result = is_null ($value);

		if (!$result) {

			if (!is_string ($value)) {

				df_assert ($value instanceof Zend_Db_Expr);

				$value = $value->__toString ();

			}


			/** @var string $value */
			df_assert_string ($value);


			/** @var bool $result */
			$result = (Df_Zf_Const::NULL_UC == mb_strtoupper ($value));

		}

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Zf_Helper_Db';
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