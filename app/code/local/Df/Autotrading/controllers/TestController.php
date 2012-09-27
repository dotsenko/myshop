<?php

class Df_Autotrading_TestController extends Mage_Core_Controller_Front_Action {


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


			/** @var Zend_Uri_Http $uri  */
			$uri = Zend_Uri::factory ('http');

			$uri->setHost ('www.autotrading.ru');
			$uri->setPath ('/enum.php');
			$uri
				->setQuery (
					array (
						'what' => 'branchesA'
						,
						'show' => 'full'
					)
				)
			;



			$httpClient
				->setHeaders (
					array (
						'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
						,
						'Accept-Encoding' => 'gzip, deflate'
						,
						'Accept-Language' => 'en-us,en;q=0.5'
						,
						'Connection' => 'keep-alive'
						,
						'Host' => 'www.autotrading.ru'
						,
						'Referer' => 'http://www.autotrading.ru/rates/calculate/'
						,
						'User-Agent' => Df_Core_Const::FAKE_USER_AGENT
					)
				)
				->setUri ($uri)
				->setConfig (
					array (

						/**
						 * в секундах
						 */
						'timeout' => 10
					)
				)
			;

			/** @var Zend_Http_Response $response  */
			$response =
				$httpClient->request (
					Zend_Http_Client::GET
				)
			;


			/** @var string $responseAsText  */
			$responseAsText = $response->getBody();

			//df_assert_string ($responseAsText);


			/** @var array $locations  */
			$locations =
				array_map (
					array ($this, 'parseLocation')
					,
					explode ("\n", $responseAsText)
				)
			;


			Mage::log (
				df_column (
					$locations, 'place'
				)
			);

//			Mage::log (
//				array_unique (
//					df_column (
//						$locations, 'regional_centre'
//					)
//				)
//			);




			/** @var Df_Autotrading_Model_Request_Locations $request */
//			$request =
//				df_model (
//					Df_Autotrading_Model_Request_Locations::getNameInMagentoFormat()
//				)
//			;
//
//
//			Mage::log ($request->getResponseAsArray());

			$this
				->getResponse()
				->setBody (
					'OK'
				)
			;

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e, false);
			echo $e->getMessage();
		}

    }




	/**
	 * @param string $textualLocation
	 * @return array
	 */
	public function parseLocation ($textualLocation) {

		df_param_string ($textualLocation, 0);


		/** @var string $repeatedPart */
		$repeatedPart =
			strtr (
				df_a (
					explode ('|', $textualLocation)
					,
					0
				)
				,
				array (
					' (порт восточный)' => Df_Core_Const::T_EMPTY
					,
					' (спец.тариф)' => Df_Core_Const::T_EMPTY
					,
					' (газо-конден. промысел)' => Df_Core_Const::T_EMPTY
					,
					'Княжпогост (Емва)' => 'Княжпогост'
					,
					'Курумоч (Береза)' => 'Курумоч'
					,
					'Матырский (ОЭЗ)' => 'Матырский'
					,
					'Новоникольское (МН Дружба, Траннефтепродукт)' => 'Новоникольское'
					,
					'Озерск (до поста ГАИ)' => 'Озерск'
					,
					'Озерск (только до поста ГАИ)' => 'Озерск'
					,
					'Снежинск (только до поста ГАИ)' => 'Снежинск'
					,
					'Трехгорный (до поста ГАИ)' => 'Трехгорный'
					,
					'Алексеевка (ближняя)' => 'Алексеевка'
					,
					'Петергоф (Петродворец)' => 'Петергоф'
					,
					'Пыть-Ях (г. Лянтор)' => 'Пыть-Ях'
					,
					'Ростилово (КС-17)' => 'Ростилово'
					,
					'Сосьва (ч/з Серов)' => 'Сосьва'
					,
					'Спасск (Беднодемьяновск)' => 'Спасск'
					,
					'Строитель (ДСУ-2)' => 'Строитель'
					,
					'Химки (Вашутинское шоссе)' => 'Химки'
					,
					'Хоста (с. «Калиновое озеро»)' => 'Хоста'
					,
					'Хоста (село «Каштаны»)' => 'Хоста'
					,
					'Ниж.Новгород' => 'Нижний Новгород'
					,
					'Ниж.Тагил' => 'Нижний Тагил'
					,
					'Наб.Челны' => 'Набережные Челны'
					,
					'Ал-Гай' => 'Александров Гай'
					,
					'Нов. Уренгой' => 'Новый Уренгой'
				)
			)
		;

		df_assert_string ($repeatedPart);


		/** @var string $regionalCentre */
		$regionalCentre = null;

		/** @var string $place */
		$place = null;


		if (false === mb_strpos ($repeatedPart, '(')) {
			$regionalCentre = $repeatedPart;
			$place = $repeatedPart;
		}
		else {

			/** @var array $locationParts  */
			$locationParts =
				df_map (
					'df_trim'
					,
					explode (
						'('
						,
						$repeatedPart
					)
					,
					') '
				)
			;

			df_assert_array ($locationParts);



			/** @var string $regionalCentre */
			$regionalCentre = df_a ($locationParts, 1);

			df_assert_string ($regionalCentre);


			/** @var string $placeWithSuffix  */
			$placeWithSuffix = df_a ($locationParts, 0);

			df_assert_string ($placeWithSuffix);


			/** @var string $place */
			$place =
				preg_replace (
					'#(.+)\s+(р\.ц\.|г\.|рп|г\.|п\.|с\.|c\.|мкр|д\.|пгт|снп\.|снп|стц|нп|р\-н|пос\.|ст\-ца|ж\/д ст\.|ст\.|а\/п|кп\.|х\.)#u'
					,
					'$1'
					,
					$placeWithSuffix
				)
			;

			df_assert_string ($place);

		}


		/** @var array $result  */
		$result =
			array (
				'place' => $place
				,
				'regional_centre' => $regionalCentre
			)
		;


		df_result_array ($result);

		return $result;

	}


}


