<?php


class Df_Sales_Helper_Data extends Mage_Core_Helper_Abstract {

	
	
	/**
	 * @return Df_Sales_Helper_Assert
	 */
	public function assert () {

		/** @var Df_Sales_Helper_Assert $result  */
		$result =
			Mage::helper (
				Df_Sales_Helper_Assert::getNameInMagentoFormat()
			)
		;


		df_assert ($result instanceof Df_Sales_Helper_Assert);

		return $result;

	}




	/**
	 * @return Df_Sales_Helper_Check
	 */
	public function check () {

		/** @var Df_Sales_Helper_Check $result  */
		$result =
			Mage::helper (
				Df_Sales_Helper_Check::getNameInMagentoFormat()
			)
		;


		df_assert ($result instanceof Df_Sales_Helper_Check);

		return $result;

	}





	/**
	 * @return Df_Sales_Helper_Order
	 */
	public function order () {

		/** @var Df_Sales_Helper_Order $result  */
		$result = Mage::helper (Df_Sales_Helper_Order::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Sales_Helper_Order);

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



	const DF_PARENT_MODULE = 'Mage_Sales';




	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Helper_Data';
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