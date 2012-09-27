<?php


class Df_1C_Helper_Data extends Mage_Core_Helper_Abstract {



	/**
	 * @param int $attributeSetId
	 * @return Df_1C_Helper_Data
	 */
	public function create1CAttributeGroupIfNeeded ($attributeSetId) {

		df_param_integer ($attributeSetId, 0);
		df_param_between ($attributeSetId, 0, 1);

		df_helper()->catalog()->product()
			->addGroupToAttributeSetIfNeeded (
				$attributeSetId
				,
				Df_1C_Const::PRODUCT_ATTRIBUTE_GROUP_NAME
				,
				$sortOrder = 2
			)
		;

		return $this;
	}




	/**
	 * @return Df_1C_Helper_Cml2
	 */
	public function cml2 () {

		/** @var Df_1C_Helper_Cml2 $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_1C_Helper_Cml2 $result  */
			$result = Mage::helper (Df_1C_Helper_Cml2::getNameInMagentoFormat());

			df_assert ($result instanceof Df_1C_Helper_Cml2);

		}

		return $result;

	}




	/**
	 * @param string $message
	 * @return Df_1C_Helper_Data
	 */
	public function log ($message) {

		df_param_string ($message, 0);

		/** @var bool $needLogging */
		static $needLogging;

		if (!isset ($needLogging)) {
			$needLogging = df_cfg()->_1c()->general()->needLogging();
		}

		if ($needLogging) {
			Mage::log ($message, null, 'rm.1c.log', true);
		}

		return $this;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Helper_Data';
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