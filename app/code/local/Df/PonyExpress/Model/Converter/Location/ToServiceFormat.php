<?php

class Df_PonyExpress_Model_Converter_Location_ToServiceFormat extends Df_Shipping_Model_Converter_Location_ToServiceFormat {


	/**
	 * @override
	 * @return string
	 * @throws Exception
	 */
	public function getResult () {

		if (!isset ($this->_result)) {

			/** @var string|null $result  */
			$result = null;


			if (Df_Shipping_Model_Request::NO_INTERNET) {

				/**
				 * что-нибудь, исключительно для тестирования
				 */
				$result = 'Майма, Алтай респ., Майминский';
			}
			else {

				/** @var array $locationsWithSameName  */
				$locationsWithSameName =
					df_a (
						Df_PonyExpress_Model_Request_Locations::i()->getResponseAsArray()
						,
						mb_strtoupper ($this->getCity())
						,
						array ()
					)
				;

				df_assert_array ($locationsWithSameName);


				foreach ($locationsWithSameName as $location) {

					/** @var array $location */
					df_assert_array ($location);


					/** @var string $region  */
					$region = df_a ($location, Df_PonyExpress_Model_Request_Locations::LOCATION__REGION);

					df_assert_string ($region);

					if (
						/**
						 * Москва, Санкт-Петербург
						 */
						df_empty ($region)
					) {

						$result = $this->getCity();
						break;

					}

					else if ($region === $this->getRegionName()) {

						$result =
							str_replace (
								'|'
								,
								Df_Core_Const::T_EMPTY
								,
								df_a (
									$location
									,
									Df_PonyExpress_Model_Request_Locations::LOCATION__LOCATION_AS_TEXT
								)
							)
						;
						break;

					}

				}


				if (is_null ($result)) {
					$this->throwError_invalidLocation ();
				}

			}

			$this->_result = $result;

		}


		df_result_string ($this->_result);

		return $this->_result;

	}


	/**
	* @var string
	*/
	private $_result;







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PonyExpress_Model_Converter_Location_ToServiceFormat';
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

