<?php

class Df_Spsr_Model_Converter_Location_ToServiceFormat extends Df_Shipping_Model_Converter_Location_ToServiceFormat {


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
				 * Пусть будет Владивосток
				 */
				$result = '1324';
			}
			else {

				/**
				 * Сначала пробуем найти город
				 */

				if (!is_null ($this->getCity())) {

					try {
						$result =
							Df_Spsr_Model_Api_Locator::getLocationIdStatic (
								$this->getCity()
							)
						;
					}
					catch (Exception $e) {

					}

				}


				if (is_null ($result)) {

					try {
						/**
						 * Город не найден. Теперь ищем субъект РФ.
						 */
						if (!is_null ($this->getRegionName())) {
							$result =
								Df_Spsr_Model_Api_Locator::getLocationIdStatic (
									$this->getRegionName()
								)
							;
						}

					}

					catch (Exception $e) {

					}

				}



				if (is_null ($result)) {

					try {

						/**
						 * Субъект РФ не найден. Ищем страну.
						 * Обратите внимание, что ищем страну в английском варианте написания
						 */

						if (!is_null ($this->getCountryNameEnglish())) {

							if (!is_null ($this->getCountryNameEnglish())) {
								$result =
									Df_Spsr_Model_Api_Locator::getLocationIdStatic (
										$this->getCountryNameEnglish()
									)
								;
							}

						}

					}

					catch (Exception $e) {

					}

				}

				if (is_null ($result)) {
					$this->throwError_invalidLocation ();
				}
				else {
					$result = df_string ($result);
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
		return 'Df_Spsr_Model_Converter_Location_ToServiceFormat';
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

