<?php

class Df_RussianPost_TestController extends Mage_Core_Controller_Front_Action {


	/**
	 * @return void
	 */
    public function indexAction() {

		try {

			/**
			 * Обратите внимание,
			 * что мы используем класс Zend_Http_Client, а не Varien_Http_Client,
			 * потому что применение Varien_Http_Client зачастую приводит к сбою:
			 * Error parsing body - doesn't seem to be a chunked message
			 *
			 * @var Zend_Http_Client $httpClient
			 */
			$httpClient = new Zend_Http_Client ();

			$httpClient
				->setHeaders (
					array (
						'Accept' => '	text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
						,
						'Host' => '	russianpostcalc.ru'
						,
						'Referer' => 'http://russianpostcalc.ru/'
						,
						'User-Agent' => Df_Core_Const::FAKE_USER_AGENT
					)
				)
				->setUri (
					'http://russianpostcalc.ru'
				)
				->setConfig (
					array (

						/**
						 * в секундах
						 */
						'timeout' => 10
					)
				)
				->setParameterPost (
					array_map (
						array (df_text(), 'convertUtf8ToWindows1251')
						,
						array (
//							'from_city' => 'Москва'
//							,
//							'from_country' => 'RU'
//							,
							'from_index' => '101700'
//							,
//							'from_state' => 'Москва'
							,
							'ob_cennost_rub' => 3250
							,
							'russianpostcalc' => 1
//							,
//							'to_city' => 'Владивосток'
//							,
//							'to_country' => 'RU'
							,
							'to_index' => '649100'
//							,
//							'to_state' => 'Приморский край'
							,
							'weight' =>	1
						)
					)
				)
			;

			/** @var Zend_Http_Response $result  */
			$result =
				$httpClient->request (
					Zend_Http_Client::POST
				)
			;


			/** @var string $html  */
			$html = $result->getBody();


			df_helper()->phpquery()->lib()->init();


			/** @var phpQueryObject $qHtml */
			$qHtml = phpQuery::newDocument ($html);

			/** @var phpQueryObject $ps */
			$ps = df_pq ('#content > p', $qHtml);

			/** @var array $rates  */
			$rates = array ();

			foreach ($ps as $p) {

				/** @var DOMNode $p */
				df_assert ($p instanceof DOMNode);

				/** @var string $nodeValue  */
				$nodeValue = df_trim ($p->nodeValue);

				if (0 === mb_strpos ($nodeValue, 'Доставка Почтой России')) {
					$rates []= $nodeValue;
				}

			}


			$this
				->getResponse()
				->setBody (
					implode (
						"<br/>"
						,
						$rates
					)
				)
			;

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e, false);
			echo $e->getMessage();
		}

    }


}


