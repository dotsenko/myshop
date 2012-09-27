<?php

class Df_Pec_Model_Config_Area_Service extends Df_Shipping_Model_Config_Area_Service {



	/**
	 * @return string
	 */
	public function getMoscowCargoReceptionPoint () {

		/** @var string $result  */
		$result =
			$this->getVar (
				self::KEY__VAR__MOSCOW_CARGO_RECEPTION_POINT
				,
				Df_Pec_Model_Config_Source_MoscowCargoReceptionPoint::OPTION_VALUE__OUTSIDE
			)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getSealCount () {

		/** @var int $result  */
		$result =
			intval (
				$this->getVar (
					self::KEY__VAR__SEAL_COUNT
					,
					0
				)
			)
		;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return bool
	 */
	public function needCargoTailLoaderAtDestination () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_CARGO_TAIL_LOADER_AT_DESTINATION
					,
					false
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return bool
	 */
	public function needCargoTailLoaderAtOrigin () {
		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_CARGO_TAIL_LOADER_AT_ORIGIN
					,
					false
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return bool
	 */
	public function needOvernightDelivery () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_OVERNIGHT_DELIVERY
					,
					false
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return bool
	 */
	public function needRemoveAwningAtDestination () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_REMOVE_AWNING_AT_DESTINATION
					,
					false
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return bool
	 */
	public function needRemoveAwningAtOrigin () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_REMOVE_AWNING_AT_ORIGIN
					,
					false
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return bool
	 */
	public function needRigidContainer () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_RIGID_CONTAINER
					,
					false
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}







	const KEY__VAR__MOSCOW_CARGO_RECEPTION_POINT = 'moscow_cargo_reception_point';
	const KEY__VAR__NEED_CARGO_TAIL_LOADER_AT_DESTINATION = 'need_cargo_tail_loader_at_destination';
	const KEY__VAR__NEED_CARGO_TAIL_LOADER_AT_ORIGIN = 'need_cargo_tail_loader_at_origin';
	const KEY__VAR__NEED_OVERNIGHT_DELIVERY = 'need_overnight_delivery';
	const KEY__VAR__NEED_REMOVE_AWNING_AT_DESTINATION = 'need_remove_awning_at_destination';
	const KEY__VAR__NEED_REMOVE_AWNING_AT_ORIGIN = 'need_remove_awning_at_origin';
	const KEY__VAR__NEED_RIGID_CONTAINER = 'need_rigid_container';
	const KEY__VAR__SEAL_COUNT = 'seal_count';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pec_Model_Config_Area_Service';
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

