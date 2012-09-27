<?php

class Df_Autotrading_Model_Request_Locations extends Df_Shipping_Model_Request {


	/**
	 * @return array
	 */
	public function getLocations () {
	
		if (!isset ($this->_locations)) {
	
			/** @var array $result  */
			$result = null;


			/**
			 * Надо бы кэшировать результат
			 */

			/**
			 * Обратите внимание, что не используем в качестве ключа __METHOD__,
			 * потому что данный метод может находиться
			 * в родительском по отношени к другим классе.
			 *
			 * @var string $cacheKey
			 */
			$cacheKey =
				implode (
					'::'
					,
					array (
						get_class ($this)
						,
						__FUNCTION__
					)
				)
			;


			/** @var string|bool $resultSerialized  */
			$resultSerialized =
				$this->getCache()->load (
					$cacheKey
				)
			;

			if (false !== $resultSerialized) {

				$result = @unserialize ($resultSerialized);

			}


			if (!is_array ($result)) {


				/** @var array $locations  */
				$locations =
					array_map (
						array ($this, 'parseLocation')
						,
						explode ("\n", $this->getResponseAsText())
					)
				;

				df_assert_array ($locations);


				$result = array ();

				/**
				 * Группируем и индексируем данные по региональным центрам
				 */
				foreach ($locations as $location) {

					/** @var array $location */

					/** @var string $regionalCenter  */
					$regionalCenter = df_a ($location, self::KEY__REGIONAL_CENTER);


					/** @var array $locationsForRegionalCenter  */
					$locationsForRegionalCenter =
						df_a ($result, $regionalCenter, array ())
					;

					$locationsForRegionalCenter [df_a ($location, self::KEY__LOCATION)] =
						df_a ($location, self::KEY__ORIGINAL_NAME)
					;

					$result [$regionalCenter] = $locationsForRegionalCenter;

				}


				$resultSerialized = serialize ($result);

				$this->getCache()
					->save (
						$resultSerialized
						,
						$cacheKey
					)
				;

			}



	
	
			df_assert_array ($result);
	
			$this->_locations = $result;
	
		}
	
	
		df_result_array ($this->_locations);
	
		return $this->_locations;
	
	}
	
	
	/**
	* @var array
	*/
	private $_locations;






	/**
	 * @param string $textualLocation
	 * @return array
	 */
	public function parseLocation ($textualLocation) {

		df_param_string ($textualLocation, 0);


		/** @var string $rawLocationName  */
		$rawLocationName =
			df_a (
				explode ('|', $textualLocation)
				,
				0
			)
		;

		df_assert_string ($rawLocationName);


		/** @var string $originalName  */
		$originalName =
			$this->removeRegionalCenterFromLocationName (
				$rawLocationName
			)
		;

		df_assert_string ($originalName);


		/** @var string $repeatedPart */
		$repeatedPart =
			strtr (
				$rawLocationName
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
				self::KEY__LOCATION =>
					df_helper()->directory()->normalizeLocationName ($place)
				,
				self::KEY__REGIONAL_CENTER =>
					df_helper()->directory()->normalizeLocationName ($regionalCentre)
				,
				self::KEY__ORIGINAL_NAME => $originalName
			)
		;


		df_result_array ($result);

		return $result;

	}




	/**
	 * @param string
	 * @return string
	 */
	private function removeRegionalCenterFromLocationName ($locationName) {

		df_param_string ($locationName, 0);

		/** @var string $result  */
		$result = null;


		/** @var int|bool $regionalCenterPosition  */
		$regionalCenterPosition = mb_strrpos  ($locationName, '(');

		if (false === $regionalCenterPosition) {
			$result = $locationName;
		}
		else {
			df_assert_integer ($regionalCenterPosition);
			df_assert_between ($regionalCenterPosition, 0);

			$result =
				df_trim (
					mb_substr (
						$locationName
						,
						0
						,
						$regionalCenterPosition
					)
				)
			;
		}

		df_result_string ($result);

		return $result;

	}


	


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
					'Accept-Language' => 'en-us,en;q=0.5'
					,
					'Connection' => 'keep-alive'
					,
					'Host' => $this->getQueryHost()
					,
					'Referer' => 'http://www.autotrading.ru/rates/calculate/'
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
		return 'www.ae5000.ru';
	}




	/**
	 * @override
	 * @return array
	 */
	protected function getQueryParams () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getQueryParams()
				,
				array (
					'what' => 'branchesA'
					,
					'show' => 'full'
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
		return '/enum.php';
	}





	/**
	 * @return string
	 */
	private function getRegionalCenter () {
		return $this->cfg (self::PARAM__REGIONAL_CENTER);
	}







	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__REGIONAL_CENTER, new Df_Zf_Validate_String())
		;
	}



	const KEY__LOCATION = 'location';
	const KEY__ORIGINAL_NAME = 'original_name';
	const KEY__REGIONAL_CENTER = 'regional_center';


	const PARAM__REGIONAL_CENTER = 'regional_center';




	/**
	 * @return Df_Autotrading_Model_Request_Locations
	 */
	public static function i () {

		/** @var Df_Autotrading_Model_Request_Locations $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Autotrading_Model_Request_Locations $result  */
			$result = df_model (self::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Autotrading_Model_Request_Locations);

		}

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Autotrading_Model_Request_Locations';
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


