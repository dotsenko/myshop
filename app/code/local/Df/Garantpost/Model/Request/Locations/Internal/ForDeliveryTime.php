<?php

class Df_Garantpost_Model_Request_Locations_Internal_ForDeliveryTime extends Df_Garantpost_Model_Request_Locations {


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
					'Referer' => 'http://www.garantpost.ru/tools/transit/'
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
	protected function getOptionsSelector () {
		return '.tarif .frm option';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getQueryPath () {
		return '/tools/transit/';
	}




	/**
	 * @override
	 * @param string $locationName
	 * @return string
	 */
	protected function normalizeLocationName ($locationName) {

		df_param_string ($locationName, 0);

		$locationName =
			strtr (
				$locationName
				,
				array (
					' А.О.' => ''
					,
					' край' => ''
					,
					' обл.' => ''
					,
					' Респ.' => ''
					,
					' респ.' => ''
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
					'Северная Осетия-Алания' => 'Северная Осетия — Алания'
					,
					'Тыва' => 'Тыва (Тува)'
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
	 * @return Df_Garantpost_Model_Request_Locations_Internal_ForDeliveryTime
	 */
	public static function i () {

		/** @var Df_Garantpost_Model_Request_Locations_Internal_ForDeliveryTime $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Garantpost_Model_Request_Locations_Internal_ForDeliveryTime $result  */
			$result = df_model (self::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Garantpost_Model_Request_Locations_Internal_ForDeliveryTime);

		}

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_Locations_Internal_ForDeliveryTime';
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


