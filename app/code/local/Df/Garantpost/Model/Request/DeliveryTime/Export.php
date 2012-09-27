<?php

class Df_Garantpost_Model_Request_DeliveryTime_Export extends Df_Garantpost_Model_Request_DeliveryTime {



	/**
	 * @return int
	 */
	public function getCapitalMax () {

		/** @var int $result  */
		$result =
			df_a (
				df_a (
					$this->getResultAsInterval ()
					,
					self::RESULT__CAPITAL
					,
					array ()
				)
				,
				self::RESULT__DELIVERY_TIME__MAX
				,
				0
			)
		;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getCapitalMin () {

		/** @var int $result  */
		$result =
			df_a (
				df_a (
					$this->getResultAsInterval ()
					,
					self::RESULT__CAPITAL
					,
					array ()
				)
				,
				self::RESULT__DELIVERY_TIME__MIN
				,
				0
			)
		;

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return int
	 */
	public function getNonCapitalMax () {

		/** @var int $result  */
		$result =
			df_a (
				df_a (
					$this->getResultAsInterval ()
					,
					self::RESULT__NON_CAPITAL
					,
					array ()
				)
				,
				self::RESULT__DELIVERY_TIME__MAX
				,
				0
			)
		;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getNonCapitalMin () {

		/** @var int $result  */
		$result =
			df_a (
				df_a (
					$this->getResultAsInterval ()
					,
					self::RESULT__NON_CAPITAL
					,
					array ()
				)
				,
				self::RESULT__DELIVERY_TIME__MIN
				,
				0
			)
		;

		df_result_integer ($result);

		return $result;

	}






	
	
	/**
	 * @return array
	 */
	public function getResultAsInterval () {
	
		if (!isset ($this->_resultAsInterval)) {

			/** @var phpQueryObject $pqDeliveryTime  */
			$pqDeliveryTime = df_pq ('#body_min_height table:first tr', $this->getResponseAsPq());

			df_assert ($pqDeliveryTime instanceof phpQueryObject);



			/** @var string $deliveryTimeToCapitalAsText  */
			$deliveryTimeToCapitalAsText =
				df_trim (
					df_pq (
						'td'
						,
						$pqDeliveryTime->eq(1)
					)->text()
				)
			;

			df_assert_string ($deliveryTimeToCapitalAsText);


			/** @var string $deliveryTimeToOtherLocationsAsText  */
			$deliveryTimeToOtherLocationsAsText =
				df_trim (
					df_pq (
						'td'
						,
						$pqDeliveryTime->eq(2)
					)->text()
				)
			;

			df_assert_string ($deliveryTimeToOtherLocationsAsText);




			/** @var $deliveryTimeToCapitalAsArray  */
			$deliveryTimeToCapitalAsArray =
				explode (
					'-'
					,
					$deliveryTimeToCapitalAsText
				)
			;

			df_assert_array ($deliveryTimeToCapitalAsArray);




			/** @var $deliveryTimeToOtherLocationsAsArray  */
			$deliveryTimeToOtherLocationsAsArray =
				explode (
					'-'
					,
					$deliveryTimeToOtherLocationsAsText
				)
			;

			df_assert_array ($deliveryTimeToOtherLocationsAsArray);



			/** @var $result  */
			$result =
				array (
					self::RESULT__CAPITAL =>
						array (
							self::RESULT__DELIVERY_TIME__MIN =>
								df_a ($deliveryTimeToCapitalAsArray, 0)
							,
							self::RESULT__DELIVERY_TIME__MAX =>
								df_a ($deliveryTimeToCapitalAsArray, 1)
						)

					,
					self::RESULT__NON_CAPITAL =>
						array (
							self::RESULT__DELIVERY_TIME__MIN =>
								df_a ($deliveryTimeToOtherLocationsAsArray, 0)
							,
							self::RESULT__DELIVERY_TIME__MAX =>
								df_a ($deliveryTimeToOtherLocationsAsArray, 1)
						)
				)
			;


	
			df_assert_array ($result);
	
			$this->_resultAsInterval = $result;
	
		}
	
	
		df_result_array ($this->_resultAsInterval);
	
		return $this->_resultAsInterval;
	
	}
	
	
	/**
	* @var array
	*/
	private $_resultAsInterval;	
	
	


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
					'Referer' => 'http://www.garantpost.ru/tools/transint/'
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
					'if_submit' => 1

		            ,
					self::POST_PARAM__DESTINATION_COUNTRY_ID => $this->getDestinationCountryId ()
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
		return '/tools/transint/';
	}





	/**
	 * @return int
	 */
	private function getDestinationCountryId () {

		/** @var int $result  */
		$result =
			df_a (
				Df_Garantpost_Model_Request_Countries_ForDeliveryTime::i()
					->getResponseAsArray()
				,
				$this->getDestinationCountryIso2()
				,
				0
			)
		;

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	private function getDestinationCountryIso2 () {
		return $this->cfg (self::PARAM__DESTINATION_COUNTRY_ISO2);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__DESTINATION_COUNTRY_ISO2, new Df_Zf_Validate_String())
		;
	}





	const PARAM__DESTINATION_COUNTRY_ISO2  = 'destination_country_iso2';
	const POST_PARAM__DESTINATION_COUNTRY_ID = 'cid';


	const RESULT__CAPITAL = 'capital';
	const RESULT__NON_CAPITAL = 'non_capital';


	const RESULT__DELIVERY_TIME__MAX= 'delivery_time__max';
	const RESULT__DELIVERY_TIME__MIN = 'delivery_time__min';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_DeliveryTime_Export';
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


