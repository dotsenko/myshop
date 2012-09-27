<?php

class Df_Pec_Model_Request_Rate extends Df_Shipping_Model_Request {




	/**
	 * @return array
	 */
	public function getResponseAsArray () {

		if (!isset ($this->_responseAsArray)) {

			/** @var array $result  */
			$result = json_decode ($this->getResponseAsText(), true);

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
					'Cache-Control'	=> 'no-cache'
					,
					'Connection' => 'keep-alive'
					,
					'Host' => 'www.pecom.ru'
					,
					'Pragma' => 'www.pecom.ru'
					,
					'Referer' => 'http://www.pecom.ru/ru/calc/'
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
	protected function getQueryHost () {
		return 'www.pecom.ru';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getQueryPath () {
		return '/bitrix/components/pecom/calc/ajax.php';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getRequestMethod () {
		return Zend_Http_Client::POST;
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
		return 'Df_Pec_Model_Request_Rate';
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


