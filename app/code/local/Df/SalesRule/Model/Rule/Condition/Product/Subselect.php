<?php


class Df_SalesRule_Model_Rule_Condition_Product_Subselect extends Mage_SalesRule_Model_Rule_Condition_Product_Subselect {




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_SalesRule_Model_Rule_Condition_Product_Subselect';
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










	const DF_FIELD_VALUE_OPTION = 'value_option';

	/**
	 * @return Df_SalesRule_Model_Rule_Condition_Product_Subselect
	 */
	public function loadValueOptions() {
		parent::loadValueOptions();

		if (
				df_enabled (Df_Core_Feature::TWEAKS_ADMIN)
			&&
				(df_cfg()->admin()->promotions()->getFixProductsSubselection ())
		) {
			$valueOptions = $this [self::DF_FIELD_VALUE_OPTION];


			if (
				!(
						is_array($valueOptions)
					||
						($valueOptions instanceof Traversable)
				)
			) {
				$this [self::DF_FIELD_VALUE_OPTION] =
					array (
						1 => df_mage()->ruleHelper()->__('TRUE'),
						0 => df_mage()->ruleHelper()->__('FALSE'),
					)
				;
			}
		}

		return $this;
	}

}


