<?php

class Df_PonyExpress_Model_Request_Rate extends Df_PonyExpress_Model_Request {
	
	
	
	/**
	 * @return float
	 */
	public function getResult () {
	
		if (!isset ($this->_result)) {

			/** @var phpQueryObject $pqRates  */
			$pqRates = df_pq ('.tariff_result:last', $this->getResponseAsPq());

			df_assert ($pqRates instanceof phpQueryObject);


			/** @var float $result  */
			$result = floatval (df_trim ($pqRates->html()));

	
			df_assert_float ($result);

			df_assert ($result > 0.0);
	
			$this->_result = $result;
	
		}
	
	
		df_result_float ($this->_result);
	
		return $this->_result;
	
	}
	
	
	/**
	* @var float
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
					'Cache-Control'	=> 'no-cache'
					,
					'Connection' => 'keep-alive'
					,
					'Host' => 'www.ponyexpress.ru'
					,
					'Referer' => 'http://www.ponyexpress.ru/tariff.php'
					,
					'User-Agent' => Df_Core_Const::FAKE_USER_AGENT
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
			array_map (
				array (df_text(), 'convertUtf8ToWindows1251')
				,
				array_merge (
					$this->getAllPostParameters()
					,
					array (
						'GetTariff'=> 'Рассчитать'
					)
					,
					parent::getPostParameters()
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
		return '/tariff.php';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getRequestMethod () {
		return Zend_Http_Client::POST;
	}




	/**
	 * @return array
	 */
	private function getAllPostParameters () {

		/** @var array $prefixes  */
		$prefixes =
			array (
				'StringSend' => 'Москва'
				,
				'StringRec' => Df_Core_Const::T_EMPTY
				,
				'ClientService' => 'Экспресс'
				,
				'ClientWeight' => Df_Core_Const::T_EMPTY
				,
				'ClientOG' => Df_Core_Const::T_EMPTY
				,
				'ClientKGO' => Df_Core_Const::T_EMPTY
			)
		;

		/** @var int $numKeys  */
		$numKeys = 20;


		/** @var array $result  */
		$result = array ();


		for ($index = 1; $index <= $numKeys; $index++) {

			foreach ($prefixes as $prefixKey => $prefixValue) {

				/** @var string $prefixKey */
				df_assert_string ($prefixKey);

				/** @var string $prefixValue */
				df_assert_string ($prefixValue);


				/** @var string $key */
				$key =
					implode (
						Df_Core_Const::T_EMPTY
						,
						array (
							$prefixKey
							,
							df_string ($index)
						)
					)
				;

				df_assert_string ($key);


				$result [$key] = $prefixValue;

			}

		}


		df_result_array ($result);

		return $result;

	}



	const POST__LOCATION__DESTINATION = 'StringRec1';
	const POST__LOCATION__SOURCE = 'StringSend1';
	const POST__METHOD = 'ClientService1';
	const POST__WEIGHT = 'ClientWeight1';
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PonyExpress_Model_Request_Rate';
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


