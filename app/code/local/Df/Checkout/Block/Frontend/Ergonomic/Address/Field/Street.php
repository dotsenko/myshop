<?php


class Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Street
	extends Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Text {




	/**
	 * @override
	 * @return string
	 */
	public function getDomId () {

		/** @var string $result  */
		$result =
			$this->getDomIdForStreetLine (1)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @param int $lineOrdering
	 * @return string
	 */
	public function getDomIdForStreetLine ($lineOrdering) {

		df_param_integer ($lineOrdering, 0);

		/** @var string $result  */
		$result =
			sprintf (
				'%s%d'
				,
				parent::getDomId()
				,
				$lineOrdering
			)
		;

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	public function getDomName () {

		if (!isset ($this->_domName)) {

			/** @var string $result  */
			$result =
				sprintf (
					'%s[]'
					,
					parent::getDomName()
				)
			;


			df_assert_string ($result);

			$this->_domName = $result;

		}


		df_result_string ($this->_domName);

		return $this->_domName;

	}


	/**
	* @var string
	*/
	private $_domName;


	
	
	
	
	/**
	 * @return int
	 */
	public function getLinesCount () {
	
		if (!isset ($this->_linesCount)) {
	
			/** @var int $result  */
			$result = df_mage()->customer()->addressHelper()->getStreetLines();
	
	
			df_assert_integer ($result);
	
			$this->_linesCount = $result;
	
		}
	
	
		df_result_integer ($this->_linesCount);
	
		return $this->_linesCount;
	
	}
	
	
	/**
	* @var int
	*/
	private $_linesCount;	






	/**
	 * @override
	 * @return string
	 */
	public function getValue () {

		/** @var mixed $result  */
		$result =
			$this->getValueForStreetLine (1)
		;

		return $result;

	}




	/**
	 * @param int $lineOrdering
	 * @return string|null
	 */
	public function getValueForStreetLine ($lineOrdering) {

		df_param_integer ($lineOrdering, 0);

		/** @var string $result  */
		$result =
			$this->getAddress()->getAddress()->getStreet ($lineOrdering)
		;


		if (!is_null ($result)) {
			df_result_string ($result);
		}

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



	const DEFAULT_TEMPLATE = 'df/checkout/ergonomic/address/field/street.phtml';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Street';
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


