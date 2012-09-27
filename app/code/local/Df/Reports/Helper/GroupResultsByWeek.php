<?php

class Df_Reports_Helper_GroupResultsByWeek extends Mage_Core_Helper_Abstract {


	/**
	 * @return bool
	 */
	public function isSelectedInFilter () {
	
		if (!isset ($this->_selectedInFilter)) {
	
			/** @var bool $result  */
			$result = 
				('week' === df_a ($this->getFilterAsArray (), 'period_type'))
			;
	
	
			df_assert_boolean ($result);
	
			$this->_selectedInFilter = $result;
	
		}
	
	
		df_result_boolean ($this->_selectedInFilter);
	
		return $this->_selectedInFilter;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_selectedInFilter;






	/**
	 * @return array
	 */
	private function getFilterAsArray () {

		if (!isset ($this->_filterAsArray)) {

			/** @var array $result  */
			$result = array ();


			/** @var string|null $f$filterAsStringilter */
			$filterAsString = df_request ('filter');

			if (!is_null ($filterAsString)) {

				df_assert_string ($filterAsString);


				/** @var array $result */
				$result =
					df_mage()->adminhtml()->helper()->prepareFilterString (
						$filterAsString
					)
				;

			}


			df_assert_array ($result);

			$this->_filterAsArray = $result;

		}


		df_result_array ($this->_filterAsArray);

		return $this->_filterAsArray;

	}


	/**
	* @var array
	*/
	private $_filterAsArray;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Helper_GroupResultsByWeek';
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