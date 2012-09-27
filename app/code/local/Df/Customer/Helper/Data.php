<?php

class Df_Customer_Helper_Data extends Mage_Core_Helper_Abstract {



	/**
	 * @return Df_Customer_Helper_Assert
	 */
	public function assert () {

		/** @var Df_Customer_Helper_Assert $result  */
		static $result;

		if (!isset ($result)) {
			$result = Mage::helper (Df_Customer_Helper_Assert::getNameInMagentoFormat());
		}

		return $result;

	}




	/**
	 * @return Df_Customer_Helper_Check
	 */
	public function check () {

		/** @var Df_Customer_Helper_Check $result  */
		static $result;

		if (!isset ($result)) {
			$result = Mage::helper (Df_Customer_Helper_Check::getNameInMagentoFormat());
		}

		return $result;

	}

	



	/**
	 * @return Df_CustomerBalance_Helper_Data
	 */
	public function balance () {

		/** @var Df_CustomerBalance_Helper_Data $result  */
		static $result;

		if (!isset ($result)) {
			$result = Mage::helper (Df_CustomerBalance_Helper_Data::getNameInMagentoFormat());
		}

		return $result;

	}




    /**
	 * @param array $args
     * @return string
     */
    public function translateByParent (array $args) {

		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByModule (
				$args, self::DF_PARENT_MODULE
			)
		;

		df_result_string ($result);

	    return $result;
    }



	const DF_PARENT_MODULE = 'Mage_Customer';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Customer_Helper_Data';
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