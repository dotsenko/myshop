<?php


class Df_Dataflow_Helper_Settings extends Df_Core_Helper_Settings {


	/**
	 * @return Df_Dataflow_Helper_Settings_Common
	 */
	public function common () {

		/** @var Df_Dataflow_Helper_Settings_Common $result  */
		$result =
			Mage::helper (Df_Dataflow_Helper_Settings_Common::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Dataflow_Helper_Settings_Common);

		return $result;

	}


	/**
	 * @return Df_Dataflow_Helper_Settings_Products
	 */
	public function products () {

		/** @var Df_Dataflow_Helper_Settings_Products $result  */
		$result =
			Mage::helper (Df_Dataflow_Helper_Settings_Products::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Dataflow_Helper_Settings_Products);

		return $result;

	}


	/**
	 * @return Df_Dataflow_Helper_Settings_Patches
	 */
	public function patches () {

		/** @var Df_Dataflow_Helper_Settings_Patches $result  */
		$result =
			Mage::helper (Df_Dataflow_Helper_Settings_Patches::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Dataflow_Helper_Settings_Patches);

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Helper_Settings';
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