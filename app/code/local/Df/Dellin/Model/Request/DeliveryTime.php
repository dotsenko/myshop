<?php

class Df_Dellin_Model_Request_DeliveryTime extends Df_Dellin_Model_Request {
	
	
	
	/**
	 * @return int
	 */
	public function getResult () {
	
		if (!isset ($this->_result)) {

			/** @var phpQueryObject $pqResult  */
			$pqResult = df_pq ('.result', $this->getResponseAsPq());

			df_assert ($pqResult instanceof phpQueryObject);



			/** @var phpQueryObject $deliveryTimeParagraphs  */
			$deliveryTimeParagraphs = df_pq ('p', $pqResult);

			df_assert ($deliveryTimeParagraphs instanceof phpQueryObject);



			if (0 === count ($deliveryTimeParagraphs)) {
				/**
				 * Если в качестве пункта доставки указан областной центр,
				 * то вёрстка блока .result не содержит параграфов.
				 */
				$deliveryTimeParagraphs = $pqResult;
			}



			/** @var phpQueryObject $paragraph1 */
			$paragraph1 = $deliveryTimeParagraphs->eq (0);

			df_assert ($paragraph1 instanceof phpQueryObject);



			/** @var string $paragraph1AsText  */
			$paragraph1AsText = $paragraph1->text ();

			df_assert_string ($paragraph1AsText);



			/** @var string $patternDate  */
			$patternDate = '#(\d{2})\.(\d{2})\.(\d{4})#';


			/** @var string $matches  */
			$matches = array ();


			/** @var int|bool $r */
			$r = preg_match_all ($patternDate, $paragraph1AsText, $matches, PREG_SET_ORDER);

			df_assert_integer ($r);

			df_assert (2 === $r);



			/** @var array $dateOfSendingAsArray  */
			$dateOfSendingAsArray = df_a ($matches, 0);

			df_assert_array ($dateOfSendingAsArray);



			/** @var Zend_Date $dateOfSending  */
			$dateOfSending =
				$this->createDateFromMatches (
					$dateOfSendingAsArray
				)
			;

			df_assert ($dateOfSending instanceof Zend_Date);



			/** @var array $dateOfArrivalToTerminalAsArray  */
			$dateOfArrivalToTerminalAsArray = df_a ($matches, 1);

			df_assert_array ($dateOfArrivalToTerminalAsArray);


			/** @var Zend_Date $dateOfArrivalToTerminal  */
			$dateOfArrivalToTerminal =
				$this->createDateFromMatches (
					$dateOfArrivalToTerminalAsArray
				)
			;



			/** @var int $intervalFromOriginToTerminalAsInteger  */
			$intervalFromOriginToTerminalAsInteger =
				Df_Zf_Date::getNumberOfDaysBetweenTwoDates (
					$dateOfArrivalToTerminal
					,
					$dateOfSending
				)
			;

			df_assert_integer ($intervalFromOriginToTerminalAsInteger);



			/** @var int $daysFromTerminalToDestination  */
			$daysFromTerminalToDestination =
					1 === count ($deliveryTimeParagraphs)
				?
					1
				:
					7
			;

			df_assert_integer ($daysFromTerminalToDestination);


	
			/** @var int $result  */
			$result = 
					$intervalFromOriginToTerminalAsInteger
				+
					$daysFromTerminalToDestination
			;
	
	
			df_assert_integer ($result);
	
			$this->_result = $result;
	
		}
	
	
		df_result_integer ($this->_result);
	
		return $this->_result;
	
	}
	
	
	/**
	* @var int
	*/
	private $_result;	
	
	



	/**
	 * @override
	 * @return array
	 */
	protected function getHeaders () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getHeaders()
				,
				array (
					'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
					,
					'Referer' => 'http://dellin.ru/calculator/?mode=time'
				)
			)
		;


		df_result_array ($result);

		return $result;

	}


	
	
	/**
	 * @return array
	 */
	protected function getPostParameters () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getPostParameters()
				,
				array (
					'calculate' => 'посчитать'
					,
					'mode' => 'time'
					,
					'direction' => 'forward'
					,
					'arrivalPoint' => $this->getLocationDestination()
					,
					'derivalPoint' => $this->getLocationOrigin()
					,
					'year' => intval (Zend_Date::now()->get (Zend_Date::YEAR))
					,
					'month' => intval (Zend_Date::now()->get (Zend_Date::MONTH))
					,
					'day' => intval (Zend_Date::now()->get (Zend_Date::DAY))
				)
			)
		;

		df_result_array ($result);


		return $result;

	}	
	
	




	/**
	 * @override
	 * @return array
	 */
	protected function getQueryParams () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getQueryParams()
				,
				array (
					'mode' => 'time'
				)
			)
		;

		df_result_array ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	protected function getQueryPath () {
		return '/calculator/';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getRequestMethod () {
		return Zend_Http_Client::POST;
	}





	/**
	 * @param array $matches
	 * @return Zend_Date
	 */
	private function createDateFromMatches (array $matches) {

		/** @var Zend_Date $result  */
		$result =
			new Zend_Date (
				array (
					'year' => df_a ($matches, 3)
					,
					'month' => df_a ($matches, 2)
					,
					'day' => df_a ($matches, 1)
				)
			)
		;

		df_assert ($result instanceof Zend_Date);

		return $result;

	}



	/**
	 * @return string
	 */
	private function getLocationDestination () {
		return $this->cfg (self::PARAM__LOCATION__DESTINATION);
	}


	/**
	 * @return string
	 */
	private function getLocationOrigin () {
		return $this->cfg (self::PARAM__LOCATION__ORIGIN);
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__LOCATION__DESTINATION, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__LOCATION__ORIGIN, new Df_Zf_Validate_String())
		;
	}



	const PARAM__LOCATION__DESTINATION = 'location__destination';
	const PARAM__LOCATION__ORIGIN = 'location__origin';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Request_DeliveryTime';
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


