<?php

class Df_Spsr_Model_Request_Locations extends Df_Spsr_Model_Request {


	/**
	 * @return array
	 */
	public function getResponseAsArray () {

		if (!isset ($this->_responseAsArray)) {

			/** @var array $result  */
			$result =
				json_decode (
					df_text()->bomRemove (
						df_trim (
							$this->getResponseAsText()
						)
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
					'Accept-Language' => 'en-us,en;q=0.5'
					,
					'Connection' => 'keep-alive'
					,
					'Host' => 'www.spsr.ru'
					,
					'Referer' => 'http://www.spsr.ru/ru/service/calculator'
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
	 * @return array
	 */
	protected function getQueryParams () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getQueryParams ()
				,
				array (
					'q' => $this->getQueryParamQ ()
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
		return '/ru/service/calculator';
	}




	/**
	 * @return string
	 */
	private function getLocationNamePart () {
		return $this->cfg (self::PARAM__LOCATION_NAME_PART);
	}




	/**
	 * @return string
	 */
	private function getQueryParamQ () {

		/** @var string $result  */
		$result =
			implode (
				Df_Core_Const::T_EMPTY
				,
				array (
					'/spsr/cc_autocomplete/'
					,
					$this->getLocationNamePart ()
				)
			)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();

		$this
			->addValidator (self::PARAM__LOCATION_NAME_PART, new Df_Zf_Validate_String ())
		;
	}





	const PARAM__LOCATION_NAME_PART = 'location_name_part';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Request_Locations';
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


