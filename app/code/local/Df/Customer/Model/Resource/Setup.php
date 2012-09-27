<?php


class Df_Customer_Model_Resource_Setup extends Mage_Customer_Model_Entity_Setup {
	
	

/**
 * @return array
 */
private function getDfAttributes () {

	if (!isset ($this->_dfAttributes)) {

		/** @var array $result  */
		$result =
			array (
				'customer' => array (
					'attributes' => array (
						'df_taxpayer_identification_number' =>
							array (
								'type'               => 'varchar'
								,
								'label'              => 'Tax/VAT Number'
								,
								'input'              => 'text'
								,
								'required'           => false
								,
								'sort_order'         => 100
								,
								'visible'            => false
								,
								'system'             => false
								,
								'validate_rules'     => 'a:1:{s:15:"max_text_length";i:255;}'
								,
								'position'           => 100
								,
								'admin_checkout'     => 1
							)
					)
				)
			)
		;


		df_assert_array ($result);

		$this->_dfAttributes = $result;

	}


	df_result_array ($this->_dfAttributes);

	return $this->_dfAttributes;

}


/**
* @var array
*/
private $_dfAttributes;
	
	

}