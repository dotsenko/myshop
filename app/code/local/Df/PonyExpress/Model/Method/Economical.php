<?php

class Df_PonyExpress_Model_Method_Economical extends Df_PonyExpress_Model_Method {



	/**
	 * @override
	 * @return string
	 */
	public function getMethod () {
		return 'economical';
	}



	/**
	 * @return bool
	 */
	public function isApplicable () {

		/** @var array $applicableCities  */
		$applicableCities =
			array_map (
				'mb_strtoupper'
				,
				array ('Москва', 'Санкт-Петербург')
			)
		;

		df_assert_array ($applicableCities);


		/** @var bool $result  */
		$result =
				in_array (
					mb_strtoupper ($this->getRequest()->getOriginCity())
					,
					$applicableCities
				)
			&&
				in_array (
					mb_strtoupper ($this->getRequest()->getDestinationCity())
					,
					$applicableCities
				)
			&&
				(20 >= $this->getRequest()->getWeightInKilogrammes())
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @override
	 * @return int
	 */
	protected function getTimeOfDeliveryMax () {
		return 3;
	}


	/**
	 * @override
	 * @return int
	 */
	protected function getTimeOfDeliveryMin () {
		return 2;
	}





	/**
	 * @override
	 * @return string
	 */
	protected function getTitleBase () {
		return 'эконом';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTitleForRequest () {
		return 'Эконом';
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PonyExpress_Model_Method_Economical';
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


