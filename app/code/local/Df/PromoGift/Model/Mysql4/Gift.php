<?php

class Df_PromoGift_Model_Mysql4_Gift extends Mage_Core_Model_Mysql4_Abstract {


	/**
	 * @override
	 * @return void
	 */
    protected function _construct() {
		/**
		 * Нельзя вызывать parent::_construct(),
		 * потому что это метод в родительском классе — абстрактный.
		 */

        $this->_init (self::MAIN_TABLE, self::PRIMARY_KEY);
    }


	const PRIMARY_KEY = 'gift_id';
	const MAIN_TABLE = 'df_promo_gift/gift';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Mysql4_Gift';
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


