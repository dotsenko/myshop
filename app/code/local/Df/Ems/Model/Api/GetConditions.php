<?php

class Df_Ems_Model_Api_GetConditions extends Df_Ems_Model_Request {



	/**
	 * @return float
	 */
	public function getRate () {

		$this->responseFailureDetect();

		/** @var float $result  */
		$result =
				self::NO_INTERNET
			?
				520.0
			:
				$this->getResponseParam (self::RESPONSE_PARAM__PRICE)
		;

		df_result_float ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getTimeOfDeliveryMax () {

		$this->responseFailureDetect();

		/** @var int $result  */
		$result =
				self::NO_INTERNET
			?
				6
			:
				$this->getResponseParam (self::RESPONSE_PARAM__TIME_OF_DELIVERY__MAX)
		;

		/**
		 * Для международных отправлений калькулятор EMS не сообщает сроки
		 */
		if (is_null ($result)) {
			$result = 0;
		}


		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return int
	 */
	public function getTimeOfDeliveryMin () {

		$this->responseFailureDetect();

		/** @var int $result  */
		$result =
				self::NO_INTERNET
			?
				3
			:
				$this->getResponseParam (self::RESPONSE_PARAM__TIME_OF_DELIVERY__MIN)
		;


		/**
		 * Для международных отправлений калькулятор EMS не сообщает сроки
		 */
		if (is_null ($result)) {
			$result = 0;
		}

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @override
	 * @return array
	 */
	protected function getQueryParams () {

		/** @var array $result  */
		$result =
			array (
				'method' => 'ems.calculate'
				,
				'from' => $this->getSource()
				,
				'to' => $this->getDestination()
				,
				'weight' => $this->getWeight()
				,
				'type' => $this->getPostingType()
			)
		;


		df_result_array ($result);

		return $result;

	}




	/**
	 * @override
	 * @return bool
	 */
	protected function needCacheResponse () {
		return true;
	}





	/**
	 * @return string
	 */
	private function getDestination () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__DESTINATION);

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string|null
	 */
	private function getPostingType () {

		/** @var string|null $result  */
		$result = $this->cfg (self::PARAM__POSTING_TYPE);

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}




	/**
	 * @return string
	 */
	private function getSource () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__SOURCE);

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return float
	 */
	private function getWeight () {

		/** @var float $result  */
		$result = $this->cfg (self::PARAM__WEIGHT);

		df_result_float ($result);

		return $result;

	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__DESTINATION, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__POSTING_TYPE, new Df_Zf_Validate_String(), false
			)
			->addValidator (
				self::PARAM__SOURCE, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__WEIGHT, new Zend_Validate_Float ()
			)
		;
	}



	const PARAM__DESTINATION = 'destination';
	const PARAM__POSTING_TYPE = 'posting_type';
	const PARAM__SOURCE = 'source';
	const PARAM__WEIGHT = 'weight';


	const RESPONSE_PARAM__PRICE = 'price';
	const RESPONSE_PARAM__TIME_OF_DELIVERY__MAX = 'term/max';
	const RESPONSE_PARAM__TIME_OF_DELIVERY__MIN = 'term/min';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Ems_Model_Api_GetConditions';
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


