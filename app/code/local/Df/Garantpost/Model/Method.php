<?php

abstract class Df_Garantpost_Model_Method extends Df_Shipping_Model_Method {



	/**
	 * @abstract
	 * @return int
	 */
	abstract protected function getCostInRoubles ();





	/**
	 * @override
	 * @return float
	 */
	public function getCost () {

		if (!isset ($this->_cost)) {

			if (0 === $this->getCostInRoubles ()) {
				df_error (
					'Служба Гарантпост не может доставить данный груз в указанное место.'
				);
			}

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

		if (!is_null ($this->getRequest()) && (0 !== $this->getTimeOfDeliveryMin())) {
			$result =
				sprintf (
					'%s'
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
	 * @return bool
	 */
	public function isApplicable () {


		/** @var bool $result  */
		$result =
			(
					Df_Directory_Helper_Country::ISO_2_CODE__RUSSIA
				===
					$this->getRequest()->getOriginCountryId()
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return bool
	 */
	protected function isDeliveryFromMoscow () {
		return $this->getRequest()->isOriginMoscow();
	}





	/**
	 * @return int
	 */
	protected function getTimeOfDeliveryMax () {
		return 0;
	}




	/**
	 * @return int
	 */
	protected function getTimeOfDeliveryMin () {
		return 0;
	}

	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Method';
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


