<?php

class Df_Dellin_Model_Request_Locations extends Df_Dellin_Model_Request {


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



				/** @var Varien_Simplexml_Element $simpleXmlElement */
				$simpleXmlElement = new Varien_Simplexml_Element ($this->getResponseAsText());

				df_assert ($simpleXmlElement instanceof Varien_Simplexml_Element);



				/** @var array $locationsAsSimplexmlElements */
				$locationsAsSimplexmlElements = $simpleXmlElement->xpath ('/data/cities/city');

				df_assert_array ($locationsAsSimplexmlElements);



				foreach ($locationsAsSimplexmlElements as $locationAsSimplexmlElement) {

					/** @var Varien_Simplexml_Element $locationAsSimplexmlElement */
					df_assert ($locationAsSimplexmlElement instanceof Varien_Simplexml_Element);


					/** @var array $locationAsArray  */
					$locationAsArray = $locationAsSimplexmlElement->asCanonicalArray();

					df_assert_array ($locationAsArray);


					/** @var string $locationName  */
					$locationName = df_a ($locationAsArray, 'name');

					df_assert_string ($locationName);


					/** @var string $locationNameNormalized */
					$locationNameNormalized = $this->normalizeLocationName ($locationName);

					df_assert_string ($locationNameNormalized);


					$result [$locationNameNormalized] =
						array (
							self::RESULT__IS_TERMINAL => df_a ($locationAsArray, 'isTerminal')
							,
							self::RESULT__ID => df_a ($locationAsArray, 'id')
						)
					;

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
					'Host' => 'public.services.dellin.ru'
					,
					'Referer' => 'http://dellin.ru/calculator/?'
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
		return 'public.services.dellin.ru';
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
					'request' => 'xmlForm'
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
		return '/calculatorService2/index.html';
	}




	/**
	 * Обратите внимание, что в названиях населённых пунктов вместо ё используется е.
	 *
	 * @param string $locationName
	 * @return string
	 */
	private function normalizeLocationName ($locationName) {

		df_param_string ($locationName, 0);


		$locationName = mb_strtoupper ($locationName);


		/** @var string $result  */
		$result = null;


		if (false === mb_strpos ($locationName, '(')) {
			$result = $locationName;
		}
		else {

			/** @var array $locationParts  */
			$locationParts =
				df_map (
					'df_trim'
					,
					explode (
						'('
						,
						$locationName
					)
					,
					') '
				)
			;

			df_assert_array ($locationParts);



			/** @var string $result  */
			$result = df_a ($locationParts, 0);

			df_assert_string ($result);

		}



		df_result_string($result);

		return $result;

	}





	/**
	 * @return Df_Dellin_Model_Request_Locations
	 */
	public static function i () {

		/** @var Df_Dellin_Model_Request_Locations $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Dellin_Model_Request_Locations $result  */
			$result = df_model (self::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Dellin_Model_Request_Locations);

		}

		return $result;

	}



	const RESULT__ID = 'id';
	const RESULT__IS_TERMINAL = 'is_terminal';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Request_Locations';
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


