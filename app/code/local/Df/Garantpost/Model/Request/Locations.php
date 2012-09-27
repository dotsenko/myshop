<?php

abstract class Df_Garantpost_Model_Request_Locations extends Df_Garantpost_Model_Request {


	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getOptionsSelector ();



	/**
	 * @abstract
	 * @param string $locationName
	 * @return string
	 */
	abstract protected function normalizeLocationName ($locationName);




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


				/** @var phpQueryObject $pqOptions  */
				$pqOptions = df_pq ($this->getOptionsSelector(), $this->getResponseAsPq());

				df_assert ($pqOptions instanceof phpQueryObject);



				/** @var array $locations  */
				$locations = array ();


				foreach ($pqOptions as $domOption) {

					/** @var DOMNode $domOption */
					df_assert ($domOption instanceof DOMNode);


					/** @var phpQueryObject $pqOption  */
					$pqOption = df_pq ($domOption);

					df_assert ($pqOption instanceof phpQueryObject);


					/** @var string $locationName  */
					$locationName = df_trim ($domOption->textContent);

					df_assert_string ($locationName);


					$locationName = $this->normalizeLocationName ($locationName);

					df_assert_string ($locationName);


					$locations [$locationName]= intval ($pqOption->val());

				}


				$result =
					/**
					 * У Чеченской республики отсутствует код
					 */
					df_clean (
						$locations
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
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_Locations';
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


