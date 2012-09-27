<?php


class Df_Checkout_Block_Frontend_Ergonomic_Address_Row extends Df_Core_Block_Abstract {


	
	/**
	 * @return Df_Checkout_Model_Collection_Ergonomic_Address_Field
	 */
	public function getFields () {
	
		if (!isset ($this->_fields)) {
	
			/** @var Df_Checkout_Model_Collection_Ergonomic_Address_Field $result  */
			$result = 
				df_model (
					Df_Checkout_Model_Collection_Ergonomic_Address_Field::getNameInMagentoFormat()
				)
			;
	
	
			df_assert ($result instanceof Df_Checkout_Model_Collection_Ergonomic_Address_Field);
	
			$this->_fields = $result;
	
		}
	
	
		df_assert ($this->_fields instanceof Df_Checkout_Model_Collection_Ergonomic_Address_Field);
	
		return $this->_fields;
	
	}
	
	
	/**
	* @var Df_Checkout_Model_Collection_Ergonomic_Address_Field
	*/
	private $_fields;	
	
	
	



	/**
	 * @param string $fieldAsHtml
	 * @param string $fieldType
	 * @param int $ordering
	 * @param int $totalCount
	 * @return string
	 */
	public function wrapField ($fieldAsHtml, $fieldType, $ordering, $totalCount) {

		/** @var string $result  */
		$result =
//				$this->hasSingleField()
//			?
//				$fieldAsHtml
//			:
				Df_Core_Model_Format_Html_Tag::output (
					$fieldAsHtml
					,
					'div'
					,
					array (
						'class' =>
							df_output()->getCssClassesAsString (
								df_clean (
									array (
										'field'
										,
												($ordering === $totalCount)
											&&
												!$this->hasSingleField()
											?
												'df-field-last'
											:
												null
										,
												(1 === $ordering)
											&&
												!$this->hasSingleField()
											?
												'df-field-first'
											:
												null
										,
										sprintf (
											'df-field-%s'
											,
											$fieldType
										)
									)
								)
							)
					)
				)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	protected function _toHtml () {

		/** @var string $result  */
		$result =
				(0 === $this->getFields()->count())
			?
				Df_Core_Const::T_EMPTY
			:
				Df_Core_Model_Format_Html_Tag::output (
					implode (
						"\n"
						,
						array_map (
							array ($this, 'wrapField')
							,
							$this->getFields()->walk ('toHtml')
							,
							$this->getFields()->walk ('getType')
							,
							range (1, $this->getFields()->count())
							,
							df_array_fill (0, $this->getFields()->count(), $this->getFields()->count())
						)
					)
					,
					'li'
					,
					array (
						'class' => $this->getCssClassesAsText()
					)
				)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getCssClassesAsText () {

		if (!isset ($this->_cssClassesAsText)) {

			/** @var string $result  */
			$result =
				df_output()->getCssClassesAsString (
					array (
							!$this->hasSingleField()
						?
							'fields'
						:
							'wide'
					)
				)
			;


			df_assert_string ($result);

			$this->_cssClassesAsText = $result;

		}


		df_result_string ($this->_cssClassesAsText);

		return $this->_cssClassesAsText;

	}


	/**
	* @var string
	*/
	private $_cssClassesAsText;




	/**
	 * @return bool
	 */
	private function hasSingleField () {

		if (!isset ($this->_singleField)) {

			/** @var bool $result  */
			$result =
				(1 === $this->getFields()->count())
			;


			df_assert_boolean ($result);

			$this->_singleField = $result;

		}


		df_result_boolean ($this->_singleField);

		return $this->_singleField;

	}


	/**
	* @var bool
	*/
	private $_singleField;

	





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address_Row';
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


