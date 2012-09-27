<?php

class Df_Reports_Helper_Data extends Mage_Core_Helper_Abstract {
	
	
	
	/**
	 * @return Df_Reports_Helper_GroupResultsByWeek
	 */
	public function groupResultsByWeek () {

		/** @var Df_Reports_Helper_GroupResultsByWeek $result  */
		$result =
			Mage::helper (Df_Reports_Helper_GroupResultsByWeek::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Reports_Helper_GroupResultsByWeek);

		return $result;

	}	
	
	
	


	/**
	 * @return Df_Reports_Helper_Settings
	 */
	public function settings () {

		/** @var Df_Reports_Helper_Settings $result  */
		$result =
			Mage::helper (Df_Reports_Helper_Settings::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Reports_Helper_Settings);

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



	const DF_PARENT_MODULE = 'Mage_Reports';






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Helper_Data';
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