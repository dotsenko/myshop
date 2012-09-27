<?php

class Df_Megapolis_Model_Request_Location extends Df_Shipping_Model_Request {


	/**
	 * @return int
	 */
	public function getResult () {
	
		if (!isset ($this->_result)) {


			try {
				/** @var int $result  */
				$result = null;


				/** @var string $responseAsJson  */
				$responseAsJson =
					sprintf (
						'[%s]'
						,
						implode (
							"\n,"
							,
							explode (
								"\n"
								,
								strtr (
									$this->getResponseAsText()
									,
									array (
										'=>' => ':'
									)
								)
							)
						)
					)
				;

				df_assert_string ($responseAsJson);



				/** @var array $locations  */
				$locations = json_decode ($responseAsJson, $isAssoc = true);

				df_assert_array ($locations);


				/** @var string $destinationCityNormalized  */
				$destinationCityNormalized =
					df_helper()->directory()->normalizeLocationName (
						$this->getLocationDestination()
					)
				;

				df_assert_string ($destinationCityNormalized);


				foreach ($locations as $location) {
					/** @var array $location */
					df_assert_array ($location);

					/** @var string $locationName  */
					$locationName = df_a ($location, 'name');


					if ($destinationCityNormalized === mb_strtoupper ($locationName)) {
						$result = intval (df_a ($location, 'id'));
						break;
					}

				}


				df_assert_integer ($result);

				$this->_result = $result;
			}
			catch (Exception $e) {

				df_error (
					sprintf (
						'Служба МЕГАПОЛИС не доставляет грузы в населённый пункт %s'
						,
						$this->getLocationDestination()
					)
				);
			}
	
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
					'Accept-Encoding' => 'gzip, deflate'
					,
					'Accept-Language' => 'en-us,en;q=0.5'
					,
					'Connection' => 'keep-alive'
					,
					'Host' => 'www.megapolis-exp.ru'
					,
					'Referer' => 'http://www.megapolis-exp.ru/'
					,
					'User-Agent' => Df_Core_Const::FAKE_USER_AGENT
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
	protected function getQueryHost () {
		return 'www.megapolis-exp.ru';
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
					'get_city' => $this->getLocationDestination ()
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
		return '/api/';
	}





	/**
	 * @return string
	 */
	private function getLocationDestination () {
		return $this->cfg (self::PARAM__LOCATION__DESTINATION);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__LOCATION__DESTINATION, new Df_Zf_Validate_String())
		;
	}



	const PARAM__LOCATION__DESTINATION = 'location__destination';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Megapolis_Model_Request_Location';
	}




	/**
	 * @param string $locationName
	 * @return int
	 */
	public static function getIdByName ($locationName) {

		if (df_empty ($locationName)) {
			df_error ('Укажите город');
		}


		df_param_string ($locationName, 0);


		/** @var Df_Megapolis_Model_Request_Location $request  */
		$request =
			df_model (
				self::getNameInMagentoFormat()
				,
				array (
					self::PARAM__LOCATION__DESTINATION => $locationName
				)
			)
		;

		df_assert ($request instanceof Df_Megapolis_Model_Request_Location);


		/** @var int $result  */
		$result = $request->getResult();

		df_result_integer ($result);

		return $result;

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


