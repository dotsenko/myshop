<?php

class Df_Spsr_Model_Request_Rate extends Df_Spsr_Model_Request {




	/**
	 * @return array
	 */
	public function getRates () {

		if (!isset ($this->_rates)) {

			/** @var array $result  */
			$result = array ();

			/** @var phpQueryObject $rows  */
			$rows = df_pq ('tr:gt(0)', $this->getPqRateTable ());

			df_assert ($rows instanceof phpQueryObject);


			foreach ($rows as $row) {

				/** @var DOMNode $row */
				df_assert ($row instanceof DOMNode);


				/** @var phpQueryObject $pqRow  */
				$pqRow = df_pq ($row);

				df_assert ($pqRow instanceof phpQueryObject);


				$result []= $this->parseRow ($pqRow);

			}


			df_assert_array ($result);

			$this->_rates = $result;

		}


		df_result_array ($this->_rates);

		return $this->_rates;

	}


	/**
	* @var array
	*/
	private $_rates;





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
					'Accept' => 'application/json, text/javascript, */*; q=0.01'
					,
					'Accept-Encoding' => 'gzip, deflate'
					,
					'Cache-Control'	=> 'no-cache'
					,
					'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
					,
					'Host' => 'www.spsr.ru'
					,
					'Pragma'	=> 'no-cache'
					,
					'Referer' => '	http://www.spsr.ru/ru/service/calculator'
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
	 * @return array
	 */
	protected function getPostParameters () {

		/** @var array $result  */
		$result =
			array_merge (
				$this->getFormRequest()->getFormSystemParameters()
				,
				array (
					'ajax_page_state[theme]' => 'spsr'

					,
					'ajax_page_state[theme_token]' => $this->getFormRequest()->getThemeToken()

					,
					'payment_of_receiver' => 0

					,
					'fee_on_request' => 0

					,
					'from_ship_owner_id' => ''

					,
					'from_ship_region_id' => ''

					/**
					 * СПСР способна рассчитывать тариф и без габаритов.
					 * Пока не учитываем габариты, а потом посмотрим.
					 */
//					,
//					self::POST__LENGTH => 40
//					,
//					self::POST__HEIGHT => 10
//					,
//					self::POST__WIDTH => 20

				)

				,
				/**
				 * Обратите внимание, что мы вызваем родительский метод
				 * последним параметром array_merge,
				 * потому что он содержит ключеыые параметры запроса
				 * (которые владелец объекта передаст через конструктор)
				 */
				parent::getPostParameters()
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
		return '/ru/system/ajax';
	}





	/**
	 * @override
	 * @return string
	 */
	protected function getRequestMethod () {
		return Zend_Http_Client::POST;
	}





	/**
	 * @override
	 * @return bool
	 */
	protected function needCacheResponse () {
		return false;
	}
	
	
	
	
	
	/**
	 * @return Df_Spsr_Model_Request_RateForm
	 */
	private function getFormRequest () {
	
		if (!isset ($this->_formRequest)) {
	
			/** @var Df_Spsr_Model_Request_RateForm $result  */
			$result = 
				df_model (
					Df_Spsr_Model_Request_RateForm::getNameInMagentoFormat()
				)
			;
	
	
			df_assert ($result instanceof Df_Spsr_Model_Request_RateForm);
	
			$this->_formRequest = $result;
	
		}
	
	
		df_assert ($this->_formRequest instanceof Df_Spsr_Model_Request_RateForm);
	
		return $this->_formRequest;
	
	}
	
	
	/**
	* @var Df_Spsr_Model_Request_RateForm
	*/
	private $_formRequest;





	/**
	 * @return phpQueryObject
	 */
	private function getPqRateResponse () {

		if (!isset ($this->_pqRateResponse)) {

			/** @var phpQueryObject $result  */
			$result =
				df_pq (
					df_a (
						df_a ($this->getResponseAsArray(), 1, array ())
						,
						'data'
					)
				)
			;


			df_assert ($result instanceof phpQueryObject);

			$this->_pqRateResponse = $result;

		}


		df_assert ($this->_pqRateResponse instanceof phpQueryObject);

		return $this->_pqRateResponse;

	}


	/**
	* @var phpQueryObject
	*/
	private $_pqRateResponse;





	/**
	 * @return phpQueryObject
	 */
	private function getPqRateTable () {

		if (!isset ($this->_pqRateTable)) {

			/** @var phpQueryObject $result  */
			$result = null;


			/** @var phpQueryObject $pqTables  */
			$pqTables = df_pq ('table', $this->getPqRateResponse());

			df_assert ($pqTables instanceof phpQueryObject);


			foreach ($pqTables as $table) {

				/** @var DOMNode $table */
				df_assert ($table instanceof DOMNode);

				if (false !== mb_strpos ($table->textContent, 'Название услуги')) {

					$result = df_pq ($table);
					break;
				}

			}


			df_assert ($result instanceof phpQueryObject);

			$this->_pqRateTable = $result;

		}


		df_assert ($this->_pqRateTable instanceof phpQueryObject);

		return $this->_pqRateTable;

	}


	/**
	* @var phpQueryObject
	*/
	private $_pqRateTable;

	
	
	
	
	/**
	 * @return array
	 */
	private function getResponseAsArray () {
	
		if (!isset ($this->_responseAsArray)) {
	
			/** @var array $result  */
			$result =
				json_decode (
					df_text()->bomRemove (
						$this->getResponseAsText()
					)
					,
					true
				)
			;
	
	
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
	 * @param phpQueryObject $pqRow
	 * @return array
	 */
	private function parseRow (phpQueryObject $pqRow) {

		/** @var phpQueryObject $pqCells */
		$pqCells = df_pq ('td', $pqRow);

		df_assert ($pqCells instanceof phpQueryObject);


		/** @var array $cellsAsArray  */
		$cellsAsArray = array ();


		foreach ($pqCells as $cell) {

			/** @var DOMNode $cell */
			df_assert ($cell instanceof DOMNode);

			$cellsAsArray []= df_trim ($cell->textContent);

		}


		/** @var array $timeOfDeliveryAsArray  */
		$timeOfDeliveryAsArray =
			explode (
				'-'
				,
				df_a ($cellsAsArray, 2)
			)
		;


		/** @var int $timeOnDeliveryMin  */
		$timeOnDeliveryMin =
			intval (
				df_a (
					$timeOfDeliveryAsArray, 0
				)
			)
		;


		/** @var array $result  */
		$result =
			array (
				self::RATE__TITLE =>
					df_trim (
						df_a ($cellsAsArray, 0)
						,
						'"'
					)
				,
				self::RATE__COST => df_a ($cellsAsArray, 1)
				,
				self::RATE__TIME_OF_DELIVERY__MIN => $timeOnDeliveryMin
				,
				self::RATE__TIME_OF_DELIVERY__MAX =>
						$timeOnDeliveryMin
					+
						intval (
							df_a (
								$timeOfDeliveryAsArray, 1
							)
						)
			)
		;

		df_result_array ($result);

		return $result;

	}




	const POST__DECLARED_VALUE = 'cost';
	const POST__ENABLE_SMS_NOTIFICATION = 'sms';
	const POST__ENDORSE_DELIVERY_TIME = 'pre_notification';
	const POST__HEIGHT = 'height';
	const POST__INSURANCE_TYPE = 'type';
	const POST__LENGTH = '_length';
	const POST__LOCATION__DESTINATION = 'to_send_id';
	const POST__LOCATION__SOURCE = 'from_ship_id';
	const POST__PERSONAL_HANDING = 'by_hand';
	const POST__WEIGHT = 'weight';
	const POST__WIDTH = 'width';


	const RATE__TITLE = 'title';
	const RATE__COST = 'cost';
	const RATE__TIME_OF_DELIVERY__MIN = 'time_of_delivery__min';
	const RATE__TIME_OF_DELIVERY__MAX = 'time_of_delivery__max';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Request_Rate';
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


