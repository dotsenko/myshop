<?php

class Df_Dellin_Model_Config_Area_Service extends Df_Shipping_Model_Config_Area_Service {



	/**
	 * @return bool
	 */
	public function needAdditionalPacking () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_ADDITIONAL_PACKING
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
	public function needBagPacking () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_BAG_PACKING
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
	public function needCollapsiblePalletBoxAtOrigin () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_COLLAPSIBLE_PALLET_BOX_AT_ORIGIN
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
	public function needManipulatorAtDestination () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_MANIPULATOR_AT_DESTINATION
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
	public function needOpenCarAtDestination () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_OPEN_CAR_AT_DESTINATION
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
	public function needOpenCarAtOrigin () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_OPEN_CAR_AT_ORIGIN
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




	/**
	 * @return bool
	 */
	public function needSideCastingAtDestination () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_SIDE_CASTING_AT_DESTINATION
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
	public function needSideCastingAtOrigin () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_SIDE_CASTING_AT_ORIGIN
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
	public function needSoftPacking () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_SOFT_PACKING
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
	public function needTopCastingAtOrigin () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_TOP_CASTING_AT_ORIGIN
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
	public function needTopCastingAtDestination () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_TOP_CASTING_AT_DESTINATION
					,
					false
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}







	const KEY__VAR__NEED_ADDITIONAL_PACKING = 'need_additional_packing';
	const KEY__VAR__NEED_BAG_PACKING = 'need_bag_packing';
	const KEY__VAR__NEED_CARGO_TAIL_LOADER_AT_ORIGIN = 'need_cargo_tail_loader_at_origin';
	const KEY__VAR__NEED_COLLAPSIBLE_PALLET_BOX_AT_ORIGIN = 'need_collapsible_pallet_box_at_origin';
	const KEY__VAR__NEED_MANIPULATOR_AT_DESTINATION = 'need_manipulator_at_destination';
	const KEY__VAR__NEED_OPEN_CAR_AT_DESTINATION = 'need_open_car_at_destination';
	const KEY__VAR__NEED_OPEN_CAR_AT_ORIGIN = 'need_open_car_at_origin';
	const KEY__VAR__NEED_REMOVE_AWNING_AT_ORIGIN = 'need_remove_awning_at_origin';
	const KEY__VAR__NEED_RIGID_CONTAINER = 'need_rigid_container';
	const KEY__VAR__NEED_SIDE_CASTING_AT_DESTINATION = 'need_side_casting_at_destination';
	const KEY__VAR__NEED_SIDE_CASTING_AT_ORIGIN = 'need_side_casting_at_origin';
	const KEY__VAR__NEED_SOFT_PACKING = 'need_soft_packing';
	const KEY__VAR__NEED_TOP_CASTING_AT_ORIGIN = 'need_top_casting_at_origin';
	const KEY__VAR__NEED_TOP_CASTING_AT_DESTINATION = 'need_top_casting_at_destination';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Config_Area_Service';
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

