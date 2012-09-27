<?php

class Df_RussianPost_Model_Request extends Df_Shipping_Model_Request {
	

	/**
	 * @return string[]
	 */
	public function getRatesAsText () {
	
		if (!isset ($this->_ratesAsText)) {

			/** @var phpQueryObject $pqErrors  */
			$pqErrors = df_pq ('#content .errors ul li', $this->getResponseAsPq());

			df_assert ($pqErrors instanceof phpQueryObject);


			/** @var array $errorMessages  */
			$errorMessages = array ();

			foreach ($pqErrors as $errorListItem) {

				/** @var DOMNode $errorListItem */
				df_assert ($errorListItem instanceof DOMNode);

				$errorMessages []= df_trim ($errorListItem->textContent);

			}


			/** @var string[] $result  */
			$result = array ();


			if (0 < count ($errorMessages)) {

				df_error (
					implode (
						', '
						,
						array_map (
							array (df_text(), 'quote')
							,
							$errorMessages
						)

					)
				);

			}

			else {

				/** @var phpQueryObject $paragraphs */
				$paragraphs = df_pq ('#content > p', $this->getResponseAsPq());

				df_assert ($paragraphs instanceof phpQueryObject);


				foreach ($paragraphs as $paragraph) {

					/** @var DOMNode $paragraph */
					df_assert ($paragraph instanceof DOMNode);

					/** @var string $nodeValue  */
					$nodeValue = df_trim ($paragraph->nodeValue);

					if (0 === mb_strpos ($nodeValue, 'Доставка Почтой России')) {

						/**
						 * строка вида:
						 * Доставка Почтой России: 347.6 руб. Контрольный срок: 14* дн.
						 *
						 * или:
						 * Доставка Почтой России 1 класс: 382.44 руб. Контрольный срок: 4* дн
						 *
						 */

						$result []= $nodeValue;
					}

				}

			}

	

	
	
			df_assert_array ($result);
	
			$this->_ratesAsText = $result;
	
		}
	
	
		df_result_array ($this->_ratesAsText);
	
		return $this->_ratesAsText;
	
	}
	
	
	/**
	* @var string[]
	*/
	private $_ratesAsText;	
	






	/**
	 * @override
	 * @return array
	 */
	protected function getHeaders () {

		/** @var array $result  */
		$result =
			array (
				'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
				,
				'Host' => 'russianpostcalc.ru'
				,
				'Referer' => 'http://russianpostcalc.ru/'
				,
				'User-Agent' => Df_Core_Const::FAKE_USER_AGENT
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
		return 'russianpostcalc.ru';
	}





	/**
	 * @override
	 * @return string
	 */
	protected function getRequestMethod () {
		return Zend_Http_Client::POST;
	}




	const POST_PARAM__DECLARED_VALUE = 'ob_cennost_rub';
	const POST_PARAM__DESTINATION__POSTAL_CODE = 'to_index';
	const POST_PARAM__SOURCE__POSTAL_CODE = 'from_index';
	const POST_PARAM__WEIGHT = 'weight';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_RussianPost_Model_Request';
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
