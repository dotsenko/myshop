<?php

class Df_Garantpost_Model_Request_Rate extends Df_Garantpost_Model_Request {



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
					'Referer' => 'http://www.garantpost.ru/tools/calc'
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
	protected function getRequestMethod () {
		return Zend_Http_Client::POST;
	}



	/**
	 * @override
	 * @return string
	 */
	protected function getQueryPath () {
		return '/tools/calc';
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_Rate';
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


