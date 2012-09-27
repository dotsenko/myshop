<?php

abstract class Df_Pec_Model_Method extends Df_Shipping_Model_Method {



	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getTitleBase ();




	/**
	 * @override
	 * @return float
	 */
	public function getCost () {

		if (!isset ($this->_cost)) {

			/** @var float $result  */
			$result =
				df_helper()->directory()->currency()->convertFromRoublesToBase (
					$this->getCostInRoubles ()
				)
			;

			df_assert_float ($result);

			$this->_cost = $result;

		}


		df_result_float ($this->_cost);

		return $this->_cost;

	}


	/**
	* @var float
	*/
	private $_cost;






	/**
	 * @override
	 * @return string
	 */
	public function getMethodTitle () {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;

		if (!is_null ($this->getRequest())) {
			$result =
				sprintf (
					'%s: %s'
					,
					$this->getTitleBase()
					,
					$this->formatTimeOfDelivery (
						$this->getTimeOfDeliveryMin()
						,
						$this->getTimeOfDeliveryMax()
					)
				)
			;
		}

		df_result_string ($result);

		return $result;

	}




	/**
	 * @param float $value
	 * @return Df_Spsr_Model_Method
	 */
	public function setCostInRoubles ($value) {

		df_param_float ($value, 0);

		$this->setData (self::PARAM__COST_IN_ROUBLES, $value);

		return $this;

	}






	/**
	 * @param int $value
	 * @return Df_Spsr_Model_Method
	 */
	public function setTimeOfDeliveryMax ($value) {

		df_param_integer ($value, 0);

		$this->setData (self::PARAM__TIME_OF_DELIVERY__MAX, $value);

		return $this;

	}




	/**
	 * @param int $value
	 * @return Df_Spsr_Model_Method
	 */
	public function setTimeOfDeliveryMin ($value) {

		df_param_integer ($value, 0);

		$this->setData (self::PARAM__TIME_OF_DELIVERY__MIN, $value);

		return $this;

	}





	/**
	 * @return float
	 */
	private function getCostInRoubles () {

		/** @var float $result */
		$result = $this->getData (self::PARAM__COST_IN_ROUBLES);

		df_result_float ($result);

		return $result;

	}





	/**
	 * @return int
	 */
	private function getTimeOfDeliveryMax () {

		/** @var int $result */
		$result = $this->getData (self::PARAM__TIME_OF_DELIVERY__MAX);

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return int
	 */
	private function getTimeOfDeliveryMin () {

		/** @var int $result */
		$result = $this->getData (self::PARAM__TIME_OF_DELIVERY__MIN);

		df_result_integer ($result);

		return $result;

	}







	const PARAM__COST_IN_ROUBLES = 'cost_in_roubles';
	const PARAM__TIME_OF_DELIVERY__MAX = 'time_of_delivery__max';
	const PARAM__TIME_OF_DELIVERY__MIN = 'time_of_delivery__min';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pec_Model_Method';
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


