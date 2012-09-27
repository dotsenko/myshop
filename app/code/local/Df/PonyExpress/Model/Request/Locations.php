<?php

class Df_PonyExpress_Model_Request_Locations extends Df_PonyExpress_Model_Request {


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

				$result = array ();

				/** @var array $locationsAsText  */
				$locationsAsText =
					explode (
						"\n"
						,
						df_trim (
							$this->getResponseAsText()
							,
							"\r\n"
						)
					)
				;

				df_assert_array ($locationsAsText);


				foreach ($locationsAsText as $locationAsText) {

					/** @var string $locationAsText */
					df_assert_string ($locationAsText);

					/** @var array $locationAsArray  */
					$locationAsArray =
						df_map (
							'df_trim'
							,
							explode (
								'|'
								,
								$locationAsText
							)
							,
							', '
						)
					;


					/** @var string $place  */
					$place = df_a ($locationAsArray, 0);

					df_assert_string ($place);


					$place = mb_strtoupper ($place);



					/** @var string $region  */
					$region = df_a ($locationAsArray, 1);

					df_assert_string ($region);



					$region =
						strtr (
							$region
							,
							array (
								' AO' => ''
								,
								' край' => ''
								,
								' респ.' => ''
							)
						)
					;


					$region =
						df_a (
							array (
								'Саха' => 'Саха (Якутия)'
								,
								'Северная Осетия' => 'Северная Осетия — Алания'
								,
								'Тыва' => 'Тыва (Тува)'
							)
							,
							$region
							,
							$region
						)
					;


					/**
					 * Обратите внимание,
					 * что несколько населённых пунктов могут иметь одно имя
					 */

					/** @var array $locationsWithSameName  */
					$locationsWithSameName = df_a ($result, $place, array ());

					df_assert_array ($locationsWithSameName);


					$locationsWithSameName[] =
						array (
							self::LOCATION__REGION => $region
							,
							self::LOCATION__SUBREGION => df_a ($locationAsArray, 2)
							,
							self::LOCATION__LOCATION_AS_TEXT => $locationAsText
						)
					;


					$result [$place] = $locationsWithSameName;

				}


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
					'Accept' => '*/*'
					,
					'Accept-Encoding' => 'gzip, deflate'
					,
					'Accept-Language' => 'en-us,en;q=0.5'
					,
					'Connection' => 'keep-alive'
					,
					'Host' => 'www.ponyexpress.ru'
					,
					'Referer' => 'http://www.ponyexpress.ru/tariff.php'
					,
					'User-Agent' => Df_Core_Const::FAKE_USER_AGENT
					,
					'X-Requested-With' => 'XMLHttpRequest'
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
		return '/inc/getCity.php';
	}





	/**
	 * @override
	 * @return array
	 */
	protected function getRequestConfuguration () {
		return
			array (
				'timeout' => 120
			)
		;
	}






	/**
	 * @return Df_PonyExpress_Model_Request_Locations
	 */
	public static function i () {

		/** @var Df_PonyExpress_Model_Request_Locations $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_PonyExpress_Model_Request_Locations $result  */
			$result = df_model (self::getNameInMagentoFormat());

			df_assert ($result instanceof Df_PonyExpress_Model_Request_Locations);

		}

		return $result;

	}





	const LOCATION__REGION = 'region';
	const LOCATION__SUBREGION = 'subregion';
	const LOCATION__LOCATION_AS_TEXT = 'location_as_text';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PonyExpress_Model_Request_Locations';
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


