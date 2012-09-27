<?php

class Df_Garantpost_Model_Request_Locations_Internal_ForRate extends Df_Garantpost_Model_Request_Locations {
	


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
		return 'form.tarif select[name="i_from_1"] option';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getQueryPath () {
		return '/tools/calc';
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
					' ао' => ''
					,
					' г.' => ''
					,
					' край' => ''
					,
					' обл.' => ''
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
	 * @return Df_Garantpost_Model_Request_Locations_Internal_ForRate
	 */
	public static function i () {

		/** @var Df_Garantpost_Model_Request_Locations_Internal_ForRate $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Garantpost_Model_Request_Locations_Internal_ForRate $result  */
			$result = df_model (self::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Garantpost_Model_Request_Locations_Internal_ForRate);

		}

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_Locations_Internal_ForRate';
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


