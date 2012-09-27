<?php

class Df_Ems_Model_Converter_Location_ToServiceFormat extends Df_Shipping_Model_Converter_Location_ToServiceFormat {


	/**
	 * @override
	 * @return string
	 * @throws Exception
	 */
	public function getResult () {

		if (!isset ($this->_result)) {

			/** @var string|null $result  */
			$result = null;


			if (Df_Ems_Model_Request::NO_INTERNET) {
				$result = 'city--moskva';
			}
			else {

				/**
				 * Сначала пробуем найти город
				 */

				if (!is_null ($this->getCity())) {
					$result =
						df_a (
							df_helper()->ems()->api()->cities()
								->getMapFromLocationNameToEmsLocationCode ()
							,
							mb_strtoupper (
								$this->getCity()
							)
						)
					;
				}


				if (is_null ($result)) {

					/**
					 * Город не найден. Теперь ищем субъект РФ.
					 */

					if (0 !== intval ($this->getRegionId())) {

						$result =
							df_a (
								df_helper()->ems()->api()->regions()
									->getMapFromMagentoRegionIdToEmsRegionId ()
								,
								$this->getRegionId()
							)
						;
					}

				}


				if (is_null ($result)) {

					/**
					 * Субъект РФ не найден. Ищем страну.
					 */

					if (!is_null ($this->getCountryName())) {

						$result =
							df_a (
								df_helper()->ems()->api()->countries()
									->getMapFromLocationNameToEmsLocationCode ()
								,
								mb_strtoupper (
									$this->getCountryName()
								)
							)
						;

					}

				}


				if (!is_string ($result)) {
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
		return 'Df_Ems_Model_Converter_Location_ToServiceFormat';
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


