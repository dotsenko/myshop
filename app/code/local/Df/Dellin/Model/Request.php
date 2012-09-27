<?php

class Df_Dellin_Model_Request extends Df_Shipping_Model_Request {
	


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
					'Accept-Encoding' => 'gzip, deflate'
					,
					'Accept-Language' => 'en-us,en;q=0.5'
					,
					'Connection' => 'keep-alive'
					,
					'Host' => $this->getQueryHost ()
					,
					'User-Agent' => Df_Core_Const::FAKE_USER_AGENT
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
		return 'dellin.ru';
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Request';
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


