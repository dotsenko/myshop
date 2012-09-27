<?php

class Df_Checkout_Model_Filter_Ergonomic_Address_Field_Collection_Order_ByWeight
	extends Df_Core_Model_Abstract
	implements Zend_Filter_Interface {




	/**
	 *
	 * @param  mixed $value
	 * @throws Zend_Filter_Exception If filtering $value is impossible
	 * @return Df_Checkout_Model_Collection_Ergonomic_Address_Field
	 */
	public function filter ($value) {


		/** @var Df_Checkout_Model_Collection_Ergonomic_Address_Field $value */

		df_assert ($value instanceof Df_Checkout_Model_Collection_Ergonomic_Address_Field);


		/** @var Df_Checkout_Model_Collection_Ergonomic_Address_Field $result */
		$result =
			df_model (
				Df_Checkout_Model_Collection_Ergonomic_Address_Field::getNameInMagentoFormat()
			)
		;


		/** @var array $sourceAsArray  */
		$sourceAsArray = $value->toArrayOfObjects ();

		df_assert_array ($sourceAsArray);


		usort ($sourceAsArray, array ($this, 'sort'));


		df_assert_array ($sourceAsArray);


		foreach ($sourceAsArray as $fieldConfig) {
			/** @var Df_Checkout_Block_Frontend_Ergonomic_Address_Field $fieldConfig */

			df_assert ($fieldConfig instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Field);

			$result->addItem ($fieldConfig);

		}


		return $result;
	}




	/**
	 * @param Df_Checkout_Block_Frontend_Ergonomic_Address_Field $field1
	 * @param Df_Checkout_Block_Frontend_Ergonomic_Address_Field $field2
	 * @return int
	 */
	public function sort (
		Df_Checkout_Block_Frontend_Ergonomic_Address_Field $field1
		,
		Df_Checkout_Block_Frontend_Ergonomic_Address_Field $field2
	) {

		df_assert ($field1 instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Field);

		df_assert ($field2 instanceof Df_Checkout_Block_Frontend_Ergonomic_Address_Field);



		/** @var int $result  */
		$result =
			$field1->getOrderingWeight() - $field2->getOrderingWeight()
		;

		if (0 === $result) {
			$result =
				$field1->getOrderingInConfig() - $field2->getOrderingInConfig()
			;
		}


		df_result_integer ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Filter_Ergonomic_Address_Field_Collection_Order_ByWeight';
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


