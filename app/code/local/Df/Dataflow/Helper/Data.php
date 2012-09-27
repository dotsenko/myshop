<?php


class Df_Dataflow_Helper_Data extends Mage_Core_Helper_Abstract {


	/**
	 * @return Df_Dataflow_Helper_Import
	 */
	public function import () {

		/** @var Df_Dataflow_Helper_Import $result  */
		$result =
			Mage::helper (Df_Dataflow_Helper_Import::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Dataflow_Helper_Import);

		return $result;
	}





	/**
	 * @return Df_Dataflow_Helper_Registry
	 */
	public function registry () {

		/** @var Df_Dataflow_Helper_Registry $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Dataflow_Helper_Registry $result  */
			$result = Mage::helper (Df_Dataflow_Helper_Registry::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Dataflow_Helper_Registry);

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



	const DF_PARENT_MODULE = 'Mage_Dataflow';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Helper_Data';
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