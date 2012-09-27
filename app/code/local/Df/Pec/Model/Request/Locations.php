<?php

class Df_Pec_Model_Request_Locations extends Df_Shipping_Model_Request {


	/**
	 * @return array
	 */
	public function getResponseAsArray () {

		if (!isset ($this->_responseAsArray)) {

			/** @var array $result  */
			$result = null;


			/**
			 * Надо бы кэшировать результат
			 */

			/**
			 * Обратите внимание, что не используем в качестве ключа __METHOD__,
			 * потому что данный метод может находиться
			 * в родительском по отношени к другим классе.
			 *
			 * @var string $cacheKey
			 */
			$cacheKey =
				implode (
					'::'
					,
					array (
						get_class ($this)
						,
						__FUNCTION__
					)
				)
			;


			/** @var string|bool $resultSerialized  */
			$resultSerialized =
				$this->getCache()->load (
					$cacheKey
				)
			;

			if (false !== $resultSerialized) {

				$result = @unserialize ($resultSerialized);

			}


			if (!is_array ($result)) {


				/** @var string $responseAsText  */
				$responseAsText =
					df_text()->convertWindows1251ToUtf8 (
						$this->getResponseAsText()
					)
				;



				/** @var string $pattern  */
				$pattern = '#\$\.regions \= (\{[^;]+\});#mu';

				/** @var array $matches  */
				$matches = array ();

				/** @var bool|int $r  */
				$r = preg_match ($pattern, $responseAsText, $matches);

				df_assert (1 === $r);


				/** @var string $locationsAsJson  */
				$locationsAsJson = df_a ($matches, 1);

				df_assert_string ($locationsAsJson);



				/** @var array $locationsNested */
				$locationsNested = json_decode ($locationsAsJson, true);

				df_assert_array ($locationsNested);



				/** @var array $locationsFlatten  */
				$locationsFlatten =
					call_user_func_array (
						'array_merge'
						,
						array_map (
							'array_flip'
							,
							array_values (
								$locationsNested
							)
						)
					)
				;

				df_assert_array ($locationsFlatten);



				/** @var array $locationNames */
				$locationNames = array_keys ($locationsFlatten);

				df_assert_array ($locationNames);


				/** @var array $locationNamesProcessed */
				$locationNamesProcessed =
					array_map (
						array ($this, 'processLocationName')
						,
						$locationNames
					)
				;

				df_assert_array ($locationNamesProcessed);



				/** @var array $result  */
				$result =
					df_array_combine (
						$locationNamesProcessed
						,
						array_values (
							$locationsFlatten
						)
					)
				;



				$resultSerialized = serialize ($result);

				$this->getCache()
					->save (
						$resultSerialized
						,
						$cacheKey
					)
				;

			}


			df_assert_array ($result);

			$this->_responseAsArray = $result;

		}


		df_result_array ($this->_responseAsArray);

		return $this->_responseAsArray;

	}


	/**
	* @var array
	*/
	private $_responseAsArray;






	/**
	 * @param string $locationName
	 * @return string
	 */
	public function processLocationName ($locationName) {

		df_param_string ($locationName, 0);


		/** @var string $pattern  */
		$pattern = '#([^\(]+)#u';

		/** @var array $matches  */
		$matches = array ();


		/** @var bool|int $r  */
		$r = preg_match ($pattern, $locationName, $matches);

		df_assert (1 === $r);



		/** @var string $locationWithoutRegion  */
		$locationWithoutRegion = df_a ($matches, 1);

		df_assert_string ($locationWithoutRegion);



		$locationWithoutRegion = df_trim ($locationWithoutRegion);



		$result = mb_strtoupper ($locationWithoutRegion);

		df_result_string ($result);

		return $result;

	}




	


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
					'Host' => 'www.pecom.ru'
					,
					'Referer' => 'http://www.pecom.ru/ru/calc/'
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
		return 'www.pecom.ru';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getQueryPath () {
		return '/ru/calc/';
	}






	/**
	 * @return Df_Pec_Model_Request_Locations
	 */
	public static function i () {

		/** @var Df_Pec_Model_Request_Locations $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Pec_Model_Request_Locations $result  */
			$result = df_model (self::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Pec_Model_Request_Locations);

		}

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pec_Model_Request_Locations';
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


