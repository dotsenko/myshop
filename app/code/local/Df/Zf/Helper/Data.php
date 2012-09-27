<?php


class Df_Zf_Helper_Data extends Mage_Core_Helper_Abstract {


	/**
	 * @return Df_Zf_Helper_Date
	 */
	public function date () {

		/** @var Df_Zf_Helper_Date $result  */
		$result =
			Mage::helper (Df_Zf_Helper_Date::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Zf_Helper_Date);

		return $result;

	}



	/**
	 * @return Df_Zf_Helper_Db
	 */
	public function db () {

		/** @var Df_Zf_Helper_Db $result  */
		$result =
			Mage::helper (Df_Zf_Helper_Db::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Zf_Helper_Db);

		return $result;

	}


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Zf_Helper_Data';
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
