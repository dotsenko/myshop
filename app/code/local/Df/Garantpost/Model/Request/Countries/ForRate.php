<?php

class Df_Garantpost_Model_Request_Countries_ForRate
	extends Df_Garantpost_Model_Request_Countries {




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
	protected function getOptionsSelector () {
		return '.tarif [name="i_to_1"] option';
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
					'calc_type' => 'world'
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
		return '/tools/calc';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getRequestMethod () {
		return Zend_Http_Client::POST;
	}





	/**
	 * @return Df_Garantpost_Model_Request_Countries_ForRate
	 */
	public static function i () {

		/** @var Df_Garantpost_Model_Request_Countries_ForRate $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Garantpost_Model_Request_Countries_ForRate $result  */
			$result = df_model (self::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Garantpost_Model_Request_Countries_ForRate);

		}

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_Countries_ForRate';
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


