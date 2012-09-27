<?php

class Df_Autotrading_Model_Config_Area_Service extends Df_Shipping_Model_Config_Area_Service {



	/**
	 * @return bool
	 */
	public function canCargoBePutOnASide () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__CAN_CARGO_BE_PUT_ON_A_SIDE
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
	public function checkCargoOnReceipt () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__CHECK_CARGO_ON_RECEIPT
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
	public function makeAccompanyingForms () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__MAKE_ACCOMPANYING_FORMS
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
	public function needBox () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_BOX
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
	public function needCargoTailLoader () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_CARGO_TAIL_LOADER
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
	public function needCollapsiblePalletBox () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_COLLAPSIBLE_PALLET_BOX
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
	public function needInsurance () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_INSURANCE
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
	public function needOpenSlatCrate () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_OPEN_SLAT_CRATE
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
	public function needTaping () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_TAPING
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
	public function needTapingAdvanced () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_TAPING_ADVANCED
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
	public function needPalletPacking () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_PALLET_PACKING
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
	public function needPlywoodBox () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NEED_PLYWOOD_BOX
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
	public function notifySenderAboutDelivery () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__NOTIFY_SENDER_ABOUT_DELIVERY
					,
					false
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}



	const KEY__VAR__CAN_CARGO_BE_PUT_ON_A_SIDE = 'can_cargo_be_put_on_a_side';
	const KEY__VAR__CHECK_CARGO_ON_RECEIPT = 'check_cargo_on_receipt';
	const KEY__VAR__MAKE_ACCOMPANYING_FORMS = 'make_accompanying_forms';
	const KEY__VAR__NEED_BAG_PACKING = 'need_bag_packing';
	const KEY__VAR__NEED_BOX = 'need_box';
	const KEY__VAR__NEED_CARGO_TAIL_LOADER = 'need_cargo_tail_loader';
	const KEY__VAR__NEED_COLLAPSIBLE_PALLET_BOX = 'need_collapsible_pallet_box';
	const KEY__VAR__NEED_INSURANCE = 'need_insurance';
	const KEY__VAR__NEED_OPEN_SLAT_CRATE = 'need_open_slat_crate';
	const KEY__VAR__NEED_TAPING = 'need_taping';
	const KEY__VAR__NEED_TAPING_ADVANCED = 'need_taping_advanced';
	const KEY__VAR__NEED_PALLET_PACKING = 'need_pallet_packing';
	const KEY__VAR__NEED_PLYWOOD_BOX = 'need_plywood_box';
	const KEY__VAR__NOTIFY_SENDER_ABOUT_DELIVERY = 'notify_sender_about_delivery';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Autotrading_Model_Config_Area_Service';
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

