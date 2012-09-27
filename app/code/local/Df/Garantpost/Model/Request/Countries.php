<?php

abstract class Df_Garantpost_Model_Request_Countries extends Df_Garantpost_Model_Request {


	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getOptionsSelector ();



	/**
	 * @return array
	 */
	public function getResponseAsArray () {

		if (!isset ($this->_responseAsArray)) {

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

				$result = array ();


				/** @var phpQueryObject $pqOptions  */
				$pqOptions = df_pq ($this->getOptionsSelector(), $this->getResponseAsPq());

				df_assert ($pqOptions instanceof phpQueryObject);



				/** @var array $locations  */
				$locations = array ();


				foreach ($pqOptions as $domOption) {

					/** @var DOMNode $domOption */
					df_assert ($domOption instanceof DOMNode);


					/** @var phpQueryObject $pqOption  */
					$pqOption = df_pq ($domOption);

					df_assert ($pqOption instanceof phpQueryObject);


					/** @var string $locationName  */
					$locationName = df_trim ($domOption->textContent);

					df_assert_string ($locationName);


					$locationName = $this->normalizeLocationName ($locationName);

					df_assert_string ($locationName);


					if (!df_empty ($locationName)) {
						$locations [$locationName]= intval ($pqOption->val());
					}

				}



				/** @var Mage_Directory_Model_Resource_Country_Collection $countriesInMagentoFormatCollection */
				$countriesInMagentoFormatCollection =
					Mage::getResourceModel('directory/country_collection')
				;

				df_helper()->directory()->assert()->countryCollection ($countriesInMagentoFormatCollection);


				/** @var array $countriesInMagentoFormat  */
				$countriesInMagentoFormat =
					$countriesInMagentoFormatCollection
						->loadData()
						->toOptionArray(false)
				;

				df_assert_array ($countriesInMagentoFormat);



				/** @var array $countriesInMagentoFormatAsMap  */
				$countriesInMagentoFormatAsMap =
					df_array_combine (
						df_column ($countriesInMagentoFormat, 'value')
						,
						array_map (
							'mb_strtoupper'
							,
							df_column ($countriesInMagentoFormat, 'label')
						)
					)
				;

				df_assert_array ($countriesInMagentoFormatAsMap);



				/** @var array $result  */
				$result = array ();


				foreach ($countriesInMagentoFormatAsMap as $codeInMagento => $labelInMagento) {

					/** @var string $codeInMagento */
					/** @var string $labelInMagento */

					df_assert_string ($codeInMagento);
					df_assert_string ($labelInMagento);


					/** @var int|null $codeInService  */
					$codeInService = df_a ($locations, $labelInMagento);

					if (!is_null ($codeInService)) {

						df_assert_integer ($codeInService);

						$result [$codeInMagento] = $codeInService;

					}

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
	 * @param string $locationName
	 * @return string
	 */
	protected function normalizeLocationName ($locationName) {

		$locationName = mb_strtoupper ($locationName);


		$locationName =
			strtr (
				$locationName
				,
				array (
					' О-ВА' => ' ОСТРОВА'
				)
			)
		;


		$locationName =
			df_trim (
				$locationName
				,
				', '
			)
		;



		$locationName =
			df_a (
				array (
					'АНГИЛЬЯ' => 'АНГУИЛЛА'
					,
					'АНТИЛЬСКИЕ О.' => ''
					,
					'БЕЛОРУССИЯ' => 'БЕЛАРУСЬ'
					,
					'БОСНИЯ-ГЕРЦЕГОВИНА' => 'БЕРМУДСКИЕ ОСТРОВА'
					,
					'БРУНЕЙ' => 'БРУНЕЙ ДАРУССАЛАМ'
					,
					'ВИРГИНСКИЕ БРИТАНСКИЕ ОСТРОВА' => ''
					,
					'ГОНКОНГ' => 'ГОНКОНГ, ОСОБЫЙ АДМИНИСТРАТИВНЫЙ РАЙОН КИТАЯ'
					,
					'ДОМИНИКА' => 'ДОМИНИКАНСКАЯ РЕСПУБЛИКА'
					,
					'КАБО-ВЕРДЕ' => 'ОСТРОВА ЗЕЛЕНОГО МЫСА'
					,
					'КАЙМАН ОСТРОВА' => 'КАЙМАНОВЫ ОСТРОВА'
					,
					'КАНАРСКИЕ ОСТРОВА' => ''
					,
					'КИРГИЗИЯ' => 'КЫРГЫЗСТАН'
					,
					'КИТАЙ (КНР)' => 'КИТАЙ'
					,
					'КОНГО' => 'ДЕМОКРАТИЧЕСКАЯ РЕСПУБЛИКА КОНГО'
					,
					'КОРЕЯ (ЮЖНАЯ)' => 'РЕСПУБЛИКА КОРЕЯ'
					,
					'КОТ Д\'ИВУАР' => 'КОТ Д’ИВУАР'
					,
					'КЮРАСАО' => ''
					,
					'МАКАО' => 'МАКАО (ОСОБЫЙ АДМИНИСТРАТИВНЫЙ РАЙОН КНР)'
					,
					'МИКРОНЕЗИЯ' => ''
					,
					'МОЛДАВИЯ' => 'МОЛДОВА'
					,
					'МОНТСЕРРАТ' => 'МОНСЕРРАТ'
					,
					'ОАЭ' => 'ОБЪЕДИНЕННЫЕ АРАБСКИЕ ЭМИРАТЫ'
					,
					'ПАЛЕСТИНА' => 'ПАЛЕСТИНСКАЯ АВТОНОМИЯ'
					,
					'ПАПУА-Н.ГВИНЕЯ' => 'ПАПУА-НОВАЯ ГВИНЕЯ'
					,
					'САБА' => ''
					,
					'САЙПАН' => ''
					,
					'СЕНТ-БАРТОЛОМИ' => ''
					,
					'СЕНТ-ВИНСЕНТ' => 'СЕНТ-ВИНСЕНТ И ГРЕНАДИНЫ'
					,
					'СЕНТ-КИТС И НЕВИС' => 'СЕНТ-КИТТС И НЕВИС'
					,
					'СИРИЯ' => 'СИРИЙСКАЯ АРАБСКАЯ РЕСПУБЛИКА'
					,
					'ТЕРКС И КАЙКОС' => ''
					,
					'ЦАР' => 'ЦЕНТРАЛЬНО-АФРИКАНСКАЯ РЕСПУБЛИКА'
					,
					'ЧЕХИЯ' => 'ЧЕШСКАЯ РЕСПУБЛИКА'
					,
					'ЮАР' => 'ЮЖНАЯ АФРИКА'
				)
				,
				$locationName
				,
				$locationName
			)
		;



		/** @var string $result  */
		$result = mb_strtoupper ($locationName);


		df_result_string ($result);

		return $result;

	}

	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request';
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


