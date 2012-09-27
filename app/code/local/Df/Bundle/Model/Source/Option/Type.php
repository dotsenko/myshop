<?php

class Df_Bundle_Model_Source_Option_Type extends Mage_Bundle_Model_Source_Option_Type {


	/**
	 * @return array
	 */
	public function toOptionArray () {

		/** @var array $result  */
		$result = parent::toOptionArray();

		df_assert_array ($result);

		foreach ($result as &$item) {
			/** @var array $item */
			df_assert_array ($item);


			/** @var string $label */
			$label = df_a ($item, 'label');

			df_assert_string ($label);


			$item['label'] = Mage::helper('bundle')->__ ($label);
		}

		df_result_array ($result);

		return $result;
	}


}


