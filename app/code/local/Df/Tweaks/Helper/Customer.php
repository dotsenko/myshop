<?php


class Df_Tweaks_Helper_Customer extends Mage_Core_Helper_Abstract {


	/**
	 * @param Mage_Customer_Model_Customer|null $customer
	 * @return string
	 */
	public function getFirstNameWithPrefix (Mage_Customer_Model_Customer $customer = NULL) {

		if (!$customer) {
			$customer = df_mage()->customer()->session()->getCustomer();
		}

        $result = '';

		$config = Mage::getSingleton('eav/config');
		/** @var Mage_Eav_Model_Config $config */

        if ($config->getAttribute('customer', 'prefix')->getIsVisible() && $customer->getPrefix()) {
            $result .= $customer->getPrefix() . ' ';
        }
        $result .= $customer->getFirstname();

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Customer';
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