<?php

class Df_Spsr_Model_Request_RateForm extends Df_Spsr_Model_Request {


	/**
	 * @return array
	 */
	public function getFormSystemParameters () {

		if (!isset ($this->_formSystemParameters)) {

			/** @var array $result  */
			$result = array ();


			/** @var phpQueryObject $pqHiddenInputs  */
			$pqHiddenInputs = df_pq ('input:hidden', $this->getPqForm ());


			foreach ($pqHiddenInputs as $hiddenInput) {

				/** DOMNode $hiddenInput */
				df_assert ($hiddenInput instanceof DOMNode);

				/** @var phpQueryObject $pqHiddenInput  */
				$pqHiddenInput = df_pq ($hiddenInput);


				$result [$pqHiddenInput->attr ('name')] = $pqHiddenInput->attr ('value');

			}



			df_assert_array ($result);

			$this->_formSystemParameters = $result;

		}


		df_result_array ($this->_formSystemParameters);

		return $this->_formSystemParameters;

	}


	/**
	* @var array
	*/
	private $_formSystemParameters;





	/**
	 * @return string
	 */
	public function getThemeToken () {

		if (!isset ($this->_themeToken)) {


			/** @var string $pattern */
			$pattern = '#"theme_token":"([^"]+)"#';


			/** @var array $matches  */
			$matches = array ();

			/** @var int|bool $numMatches */
			$numMatches = preg_match ($pattern, $this->getResponseAsText(), $matches);

			df_assert_integer ($numMatches);

			df_assert (1 === $numMatches);

			/** @var string $result  */
			$result = df_a ($matches, 1);


			df_assert_string ($result);

			$this->_themeToken = $result;

		}


		df_result_string ($this->_themeToken);

		return $this->_themeToken;

	}


	/**
	* @var string
	*/
	private $_themeToken;





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
					'Host' => 'www.spsr.ru'
					,
					'Referer' => 'http://www.spsr.ru/'
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
	protected function getQueryPath () {
		return '/ru/service/calculator';
	}





	/**
	 * @override
	 * @return bool
	 */
	protected function needCacheResponse () {
		return false;
	}




	/**
	 * @return phpQueryObject
	 */
	private function getPqForm () {

		if (!isset ($this->_pqForm)) {


			/** @var phpQueryObject $result  */
			$result =
				df_pq ('#spsr-calculator-form', $this->getResponseAsPq())
			;


			df_assert ($result instanceof phpQueryObject);

			$this->_pqForm = $result;

		}


		df_assert ($this->_pqForm instanceof phpQueryObject);

		return $this->_pqForm;

	}


	/**
	* @var phpQueryObject
	*/
	private $_pqForm;






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Request_RateForm';
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


