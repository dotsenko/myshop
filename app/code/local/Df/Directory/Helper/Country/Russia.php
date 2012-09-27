<?php


class Df_Directory_Helper_Country_Russia extends Df_Directory_Helper_Country {


	/**
	 * @return array
	 */
	public function getMapFromCenterToRegion () {

		if (!isset ($this->_mapFromCenterToRegion)) {


			/** @var array $result  */
			$result = array ();


			foreach (df_helper()->directory()->getRussianRegions() as $region) {

				/** @var Mage_Directory_Model_Region $region */
				df_assert ($region instanceof Mage_Directory_Model_Region);


				/** @var string $centerName  */
				$centerName = $region->getData ('df_capital');

				if (
					/**
					 * У Московской и Ленинградской областей как бы и нет столиц
					 */
					!is_null ($centerName)
				) {


					df_assert_string ($centerName);


					/** @var string $centerNameNormalized  */
					$centerNameNormalized = mb_strtoupper ($centerName);

					df_assert_string ($centerNameNormalized);


					$result [$centerNameNormalized] = $region;
				}


			}



			df_assert_array ($result);

			$this->_mapFromCenterToRegion = $result;

		}


		df_result_array ($this->_mapFromCenterToRegion);

		return $this->_mapFromCenterToRegion;

	}


	/**
	* @var array
	*/
	private $_mapFromCenterToRegion;











	/**
	 * @param string $locationName
	 * @return bool
	 */
	public function isRegionalCenter ($locationName) {

		df_param_string ($locationName, 0);

		/** @var bool $result  */
		$result =
			!is_null (
				df_a (
					$this->getMapFromCenterToRegion()
					,
					mb_strtoupper ($locationName)
				)
			)
		;


		df_result_boolean ($result);

		return $result;

	}

	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Helper_Country_Russia';
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