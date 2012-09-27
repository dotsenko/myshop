<?php

class Df_Ems_Model_Request extends Df_Shipping_Model_Request {


	/**
	 * @param string $paramName
	 * @param mixed $defaultValue [optional]
	 * @return mixed
	 */
	public function getResponseParam ($paramName, $defaultValue = null) {

		df_param_string ($paramName, 0);

		/** @var string $result  */
		$result =
			df_array_query (
				$this->getResponseAsArray()
				,
				implode (
					'/'
					,
					array (
						'rsp'
						,
						$paramName
					)
				)
				,
				$defaultValue
			)
		;

		return $result;

	}




	/**
	 * @override
	 * @return Df_Shipping_Model_Request
	 */
	protected function responseFailureDetect () {

		parent::responseFailureDetect();

		if (
				self::STATUS__OK
			!==
				$this->getResponseParam (self::RESPONSE_PARAM__STATUS)
		) {

			$this
				->responseFailureHandle (
					$this->getResponseParam (
						'err/msg'
					)
				)
			;

		}

		return $this;

	}





	/**
	 * @override
	 * @return string
	 */
	protected function getQueryHost () {
		return 'emspost.ru';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getQueryPath() {
		return '/api/rest';
	}




	/**
	 * @return array
	 */
	protected function getResponseAsArray () {

		if (!isset ($this->_responseAsArray)) {

			/** @var array $result  */
			$result =
				json_decode (
					$this->getResponseAsText ()
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
	 * @return string
	 */
	private function getResponseParam_Status () {

		/** @var string $result  */
		$result = $this->getResponseParam (self::RESPONSE_PARAM__STATUS);

		df_result_string ($result);

		return $result;

	}






	const RESPONSE_PARAM__STATUS = 'stat';

	const STATUS__OK = 'ok';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Ems_Model_Request';
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


