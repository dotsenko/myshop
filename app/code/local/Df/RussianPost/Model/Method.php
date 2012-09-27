<?php

abstract class Df_RussianPost_Model_Method extends Df_Shipping_Model_Method {



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
	 * @return string
	 */
	public function getMethodTitle () {

		if (!isset ($this->_methodTitle)) {

			/** @var string $result  */
			$result =
				sprintf (
					'%s: %d %s,'
					,
					$this->getTitleBase()
					,
					$this->getTimeOfDelivery()
					,
					$this->getTimeOfDeliveryNounForm (
						$this->getTimeOfDelivery()
					)
				)
			;


			df_assert_string ($result);

			$this->_methodTitle = $result;

		}


		df_result_string ($this->_methodTitle);

		return $this->_methodTitle;

	}


	/**
	* @var string
	*/
	private $_methodTitle;





	/**
	 * @return float
	 */
	private function getCostInRoubles () {

		if (!isset ($this->_costInRoubles)) {


			/** @var string $pattern */
			$pattern = '#([\d\.]+) руб#u';


			/** @var array $matches  */
			$matches = array ();

			/** @var int|bool $numMatches */
			$numMatches = preg_match ($pattern, $this->getRateAsText(), $matches);

			df_assert_integer ($numMatches);

			df_assert (1 === $numMatches);

			/** @var float $result  */
			$result = floatval (df_a ($matches, 1));


			df_assert_float ($result);

			$this->_costInRoubles = $result;

		}


		df_result_float ($this->_costInRoubles);

		return $this->_costInRoubles;

	}


	/**
	* @var float
	*/
	private $_costInRoubles;

	
	
	
	
	/**
	 * @return int
	 */
	private function getTimeOfDelivery () {
	
		if (!isset ($this->_timeOfDelivery)) {

			/** @var string $pattern */
			$pattern = '#(\d+)\* дн#u';

			/** @var array $matches  */
			$matches = array ();

			/** @var int|bool $numMatches */
			$numMatches = preg_match ($pattern, $this->getRateAsText(), $matches);

			df_assert_integer ($numMatches);

			df_assert (1 === $numMatches);

			/** @var int $result  */
			$result = intval (df_a ($matches, 1));
	
			df_assert_integer ($result);
	
			$this->_timeOfDelivery = $result;
	
		}
	
	
		df_result_integer ($this->_timeOfDelivery);
	
		return $this->_timeOfDelivery;
	
	}
	
	
	/**
	* @var int
	*/
	private $_timeOfDelivery;






	/**
	 * строка вида:
	 * Доставка Почтой России: 347.6 руб. Контрольный срок: 14* дн.
	 *
	 * или:
	 * Доставка Почтой России 1 класс: 382.44 руб. Контрольный срок: 4* дн
	 *
	 *
	 * @return string
	 */
	public function getRateAsText () {

		/** @var string $result  */
		$result = $this->getData (self::PARAM__RATE_AS_TEXT);

		df_result_string ($result);

		return $result;

	}




	/**
	 *
	 * строка вида:
	 * Доставка Почтой России: 347.6 руб. Контрольный срок: 14* дн.
	 *
	 * или:
	 * Доставка Почтой России 1 класс: 382.44 руб. Контрольный срок: 4* дн
	 *
	 *
	 * @param string $value
	 * @return Df_RussianPost_Model_Method
	 */
	public function setRateAsText ($value) {

		df_param_string ($value, 0);

		$this->setData (self::PARAM__RATE_AS_TEXT, $value);

		return $this;

	}




	const PARAM__RATE_AS_TEXT = 'rate_as_text';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_RussianPost_Model_Method';
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


