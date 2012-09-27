<?php


class Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Text
	extends Df_Checkout_Block_Frontend_Ergonomic_Address_Field {




	/**
	 * @override
	 * @return array
	 */
	protected function getCssClasses () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getCssClasses()
				,
				array (
					'input-text'
				)
			)
		;


		df_result_array ($result);

		return $result;

	}




	/**
	 * @return string|null
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		df_result_string ($result);

		return $result;
	}





	const DEFAULT_TEMPLATE = 'df/checkout/ergonomic/address/field/text.phtml';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Text';
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


