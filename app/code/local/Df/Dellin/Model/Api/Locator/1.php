<?php

class Df_Dellin_Model_Api_Locator_1 extends Df_Dellin_Model_Api_Locator {




	/**
	 * @override
	 * @param string|null $location
	 * @param bool $isOrigin
	 * @return array
	 */
	protected function getLocationData ($location, $isOrigin) {

		if (!is_null ($location)) {
			df_param_string ($location, 0);
		}

		df_param_boolean ($isOrigin, 1);


		/** @var array $map  */
		$map = Df_Dellin_Model_Request_Locations::i()->getResponseAsArray();

		df_assert_array ($map);


		if (is_null ($location)) {
			df_error (
					$isOrigin
				?
					'Администратор должен указать город склада магазина'
				:
					'Укажите город'
			);
		}



		/** @var array $result  */
		$result =
			df_a (
				$map
				,
				df_helper()->directory()->normalizeLocationName ($location)
			)
		;



		if (is_null ($result)) {

			/** @var string $from  */
			$from = 'из населённого пункта';

			/** @var string $to  */
			$to = 'в населённый пункт';


			df_error (
				sprintf (
					'Служба «Деловые Линии» не отправляет грузы %s %s.'
					,
					$isOrigin ? $from : $to
					,
					$location
				)
			);
		}


		df_result_array ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Api_Locator_1';
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

