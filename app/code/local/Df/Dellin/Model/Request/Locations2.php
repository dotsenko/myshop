<?php

class Df_Dellin_Model_Request_Locations2 extends Df_Core_Model_Abstract {


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



				/** @var array $letters  */
				$letters =
					array (
						'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З'
						, 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р'
						, 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ'
						, 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
					)
				;


				foreach ($letters as $letter) {

					/** @var string $letter */


					/**
					 * Обратите внимание,
					 * что мы используем класс Zend_Http_Client, а не Varien_Http_Client,
					 * потому что применение Varien_Http_Client зачастую приводит к сбою:
					 * Error parsing body - doesn't seem to be a chunked message
					 *
					 * @var Zend_Http_Client $httpClient
					 */
					$httpClient = new Zend_Http_Client ();


					/** @var Zend_Uri_Http $uri  */
					$uri = Zend_Uri::factory ('http');

					$uri->setHost ('dellin.ru');
					$uri->setPath ('/javascripts/index.html');
					$uri
						->setQuery (
							array (
								'mode' => 'getPlaces'
								,
								'answerType' => 'plain'
								,
								'q' => $letter
								,
								'limit' => 1000
							)
						)
					;



					$httpClient
						->setHeaders (
							array (
								'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
								,
								'Accept-Encoding' => 'gzip, deflate'
								,
								'Accept-Language' => 'en-us,en;q=0.5'
								,
								'Connection' => 'keep-alive'
								,
								'Host' => 'dellin.ru'
								,
								'Referer' => 'http://dellin.ru/javascripts/index.html'
								,
								'User-Agent' => Df_Core_Const::FAKE_USER_AGENT
							)
						)
						->setUri ($uri)
						->setConfig (
							array (

								/**
								 * в секундах
								 */
								'timeout' => 10
							)
						)
					;

					/** @var Zend_Http_Response $response  */
					$response =
						$httpClient->request (
							Zend_Http_Client::GET
						)
					;


					/** @var string $responseAsText  */
					$responseAsText = $response->getBody();

					df_assert_string ($responseAsText);




					/** @var array $locationsAsText  */
					$locationsAsText =
						explode (
							"\n"
							,
							$responseAsText
						)
					;

					df_assert_array ($locationsAsText);



					foreach ($locationsAsText as $locationAsText) {

						/** @var string $locationAsText */


						/** @var array $locationAsArray  */
						$locationAsArray = explode ('|', $locationAsText);

						df_assert_array ($locationAsArray);



						/** @var string $locationName  */
						$locationName = mb_strtoupper(df_a ($locationAsArray, 0));

						$result [$locationName] =
							array (
								Df_Dellin_Model_Request_Locations::RESULT__ID => df_a ($locationAsArray, 1)
								,
								Df_Dellin_Model_Request_Locations::RESULT__IS_TERMINAL => df_a ($locationAsArray, 2)
							)
						;
					}

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
	 * @return Mage_Core_Model_Cache
	 */
	private function getCache () {

		/** @var Mage_Core_Model_Cache $result  */
		$result = Mage::app()->getCacheInstance();

		df_assert ($result instanceof Mage_Core_Model_Cache);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Request_Locations2';
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


