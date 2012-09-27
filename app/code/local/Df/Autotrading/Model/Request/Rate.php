<?php

class Df_Autotrading_Model_Request_Rate extends Df_Shipping_Model_Request {


	/**
	 * @return float
	 */
	public function getRate () {

		if (!isset ($this->_rate)) {

			$this->responseFailureDetect();


			/** @var phpQueryObject $pqResultCell  */
			$pqResultCell = df_pq ('.calculator .calc_result ul li.inner .col2', $this->getResponseAsPq());

			df_assert ($pqResultCell instanceof phpQueryObject);


			/** @var string $resultAsText  */
			$resultAsText = df_trim ($pqResultCell->text());

			df_assert_string ($resultAsText);



			/** @var string $pattern  */
			$pattern = '#([\d\s,]+)#u';


			/** @var array $matches  */
			$matches = array ();


			/** @var bool|int $r  */
			$r = preg_match ($pattern, $resultAsText, $matches);

			df_assert (
				1 === $r
				,
				'Невозможно рассчитать стоимость доставки при данных условиях'
			);


			/** @var string $costFormatted  */
			$costFormatted = df_a ($matches, 1);

			df_assert_string ($costFormatted);



			/**
			 * Обратите внимание,
			 * что $costFormatted теперь содержит строку вида «6 657,4»,
			 * причём пробел между цифрами — необычный, там символ Unicode.
			 * Чтобы его устранить, используем preg_replace
			 */

			/** @var string $costFormattedRegularly  */
			$costFormattedRegularly =
				strtr (
					preg_replace ('#\s#u', '', $costFormatted)
					,
					array (
						',' => '.'
						,
						' ' => ''
					)
				)
			;

			df_assert_string ($costFormattedRegularly);


			/** @var float $result  */
			$result =
				floatval (
					$costFormattedRegularly
				)
			;


			df_assert_float ($result);

			$this->_rate = $result;

		}


		df_result_float ($this->_rate);

		return $this->_rate;

	}


	/**
	* @var float
	*/
	private $_rate;





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
					'Host' => 'www.ae5000.ru'
					,
					'Referer' => 'http://www.ae5000.ru/rates/calculate/'
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
			array_merge (
				parent::getPostParameters()
				,
				array (

					/**
					 * Всегда 1
					 */
					self::POST__TARGET => '1'


					/**
					 * Тип доставки.
					 *
					 * ch1 — стандартная
					 * ch2 — экспресс (пока не поддерживается калькулятором)
					 */
					,
					self::POST__DELIVERY_TYPE => 'ch1'


					/**
					 * Нужен ли детальный расчёт?
					 * В нашем случае значением будет всегда "on"
					 */
					,
					self::POST__ADVANCED_CALCULATION => 'on'


					/**
					 * Дата отправки груза
					 */
					,
					self::POST__SEND_DATE =>
						Zend_Date::now()->toString (
							Df_Core_Model_Format_Date::FORMAT__RUSSIAN
						)
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
		return 'www.ae5000.ru';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getQueryPath () {
		return '/rates/calculate/';
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
	 * @return Df_Shipping_Model_Request
	 */
	protected function responseFailureDetect () {

		parent::responseFailureDetect();

		/** @var phpQueryObject $pqErrors  */
		$pqErrors = df_pq ('.calculator .error_message ul li', $this->getResponseAsPq());

		if (0 < count ($pqErrors)) {

			/** @var string[] $errors  */
			$errors = array ();

			foreach ($pqErrors as $nodeError) {

				/** @var DOMNode $nodeError */
				df_assert ($nodeError instanceof DOMNode);

				$errors []= df_pq ($nodeError)->text();

			}

			$this
				->responseFailureHandle (
					implode (
						"\n"
						,
						$errors
					)
				)
			;

		}


		return $this;

	}




	const POST__CHECK_CARGO_ON_RECEIPT = 'assort';
	const POST__DECLARED_VALUE__FOR_CHECKING_CARGO_ON_RECEIPT = 'cargo_cost';
	const POST__NEED_INSURANCE = 'insurance';
	const POST__DECLARED_VALUE__FOR_INSURANCE = 'insurance_cargo_cost';
	const POST__CAN_CARGO_BE_PUT_ON_A_SIDE = 'manipulate';
	const POST__MAKE_ACCOMPANYING_FORMS = 'docs';
	const POST__NOTIFY_SENDER_ABOUT_DELIVERY = 'mailuved';
	const POST__NEED_COLLAPSIBLE_PALLET_BOX = 'evrobort';
	const POST__NEED_TAPING = 'scotc';
	const POST__NEED_TAPING_ADVANCED = 'firmscotc';
	const POST__NEED_BOX = 'box';
	const POST__BOX_PLACES = 'box_places';
	const POST__NEED_PALLET_PACKING = 'pallet';
	const POST__PALLET_PLACES = 'pallet_places';
	const POST__PALLET_VOLUME = 'pallet_volume';
	const POST__NEED_BAG_PACKING = 'meshok';
	const POST__BAG_PLACES = 'meshok_places';
	const POST__NEED_OPEN_SLAT_CRATE = 'obreshotka';
	const POST__NEED_PLYWOOD_BOX = 'faneryashik';
	const POST__NEED_CARGO_TAIL_LOADER = 'gidrobort';
	const POST__SEND_DATE = 'send_date';
	const POST__TARGET = 'target';
	const POST__DELIVERY_TYPE = 'delivery';
	const POST__ADVANCED_CALCULATION = 'extended';
	const POST__LOCATION__SOURCE = 'from';
	const POST__LOCATION__SOURCE__DETAILS = 'from_delivery';
	const POST__LOCATION__DESTINATION = 'to';
	const POST__LOCATION__DESTINATION__DETAILS = 'to_delivery';

	const POST__HEIGHT = 'height';
	const POST__LENGTH = 'length';
	const POST__WIDTH = 'width';
	const POST__WEIGHT = 'weight';
	const POST__QUANTITY = 'quantity';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Autotrading_Model_Request_Rate';
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


