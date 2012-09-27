<?php


abstract class Df_Checkout_Block_Frontend_Address_Element extends Df_Core_Block_Template {




	/**
	 * @return Mage_Checkout_Block_Onepage_Abstract
	 */
	protected function getAddressBlock () {

		/** @var Mage_Checkout_Block_Onepage_Abstract $result  */
		$result =
			$this->cfg (self::PARAM__ADDRESS_BLOCK)
		;

		df_assert ($result instanceof Mage_Checkout_Block_Onepage_Abstract);

		return $result;

	}




	const PARAM__ADDRESS_BLOCK = 'address_block';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Address_Element';
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


