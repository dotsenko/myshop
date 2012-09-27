<?php

class Df_Ems_Model_Api_Locations_Regions extends Df_Ems_Model_Api_Locations_Abstract {


	/**
	 * @override
	 * @return string
	 */
	protected function getLocationType () {
		return 'regions';
	}



	/**
	 * @return array
	 */
	public function getMapFromMagentoRegionIdToEmsRegionId () {

		if (!isset ($this->_mapFromMagentoRegionIdToEmsRegionId)) {

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


			/** @var array $result  */
			$result = null;


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

				/** @var array $result  */
				$result =
					df_array_combine (
						array_map (
							array ($this, 'getRegionIdInMagentoByRegionNameInEmsFormat')
							,
							df_column (
								$this->getLocationsAsRawArray()
								,
								'name'
							)
						)
						,
						df_column (
							$this->getLocationsAsRawArray()
							,
							'value'
						)
					)
				;


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

			$this->_mapFromMagentoRegionIdToEmsRegionId = $result;

		}


		df_result_array ($this->_mapFromMagentoRegionIdToEmsRegionId);

		return $this->_mapFromMagentoRegionIdToEmsRegionId;

	}


	/**
	* @var array
	*/
	private $_mapFromMagentoRegionIdToEmsRegionId;



	
	
	/**
	 * @return array
	 */
	private function getMapFromMagentoRegionNameToMagentoRegionId () {
	
		if (!isset ($this->_mapFromMagentoRegionNameToMagentoRegionId)) {
	
			/** @var array $result  */
			$result = array ();

			foreach (df_helper()->directory()->getRussianRegions() as $region) {

				/** @var Mage_Directory_Model_Region $region */
				df_assert ($region instanceof Mage_Directory_Model_Region);

				$result [mb_strtoupper ($region->getName())] = $region->getId();

			}
	
	
			df_assert_array ($result);
	
			$this->_mapFromMagentoRegionNameToMagentoRegionId = $result;
	
		}
	
	
		df_result_array ($this->_mapFromMagentoRegionNameToMagentoRegionId);
	
		return $this->_mapFromMagentoRegionNameToMagentoRegionId;
	
	}
	
	
	/**
	* @var array
	*/
	private $_mapFromMagentoRegionNameToMagentoRegionId;	
	




	/**
	 * @param string $regionNameInEmsFormat
	 * @return int
	 */
	public function getRegionIdInMagentoByRegionNameInEmsFormat ($regionNameInEmsFormat) {

		df_param_string ($regionNameInEmsFormat, 0);


		/** @var array $replacements  */
		$replacements =
			array (
				'СЕВЕРНАЯ ОСЕТИЯ-АЛАНИЯ РЕСПУБЛИКА' => 'СЕВЕРНАЯ ОСЕТИЯ — АЛАНИЯ РЕСПУБЛИКА'
				,
				'ТЫВА РЕСПУБЛИКА' => 'ТЫВА (ТУВА) РЕСПУБЛИКА'
				,
				'ХАНТЫ-МАНСИЙСКИЙ-ЮГРА АВТОНОМНЫЙ ОКРУГ' => 'ХАНТЫ-МАНСИЙСКИЙ АВТОНОМНЫЙ ОКРУГ'
			)
		;

		/** @var string $regionNameInMagentoFormat  */
		$regionNameInMagentoFormat =
			df_a (
				$replacements
				,
				$regionNameInEmsFormat
				,
				$regionNameInEmsFormat
			)
		;

		df_assert_string ($regionNameInMagentoFormat);


		/** @var string $result  */
		$result =
			df_a (
				$this->getMapFromMagentoRegionNameToMagentoRegionId()
				,
				$regionNameInMagentoFormat
				,
				0
			)
		;


		/** @var array $expectedlyNotTranslated  */
		$expectedlyNotTranslated =
			array (
				'КАЗАХСТАН'
				,
				'ТАЙМЫРСКИЙ АО'
			)
		;


		if ((0 === $result) && !in_array ($regionNameInMagentoFormat, $expectedlyNotTranslated)) {
			df_log ('Не могу перевести: ' . $regionNameInMagentoFormat);
		}

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Ems_Model_Api_Locations_Regions';
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


