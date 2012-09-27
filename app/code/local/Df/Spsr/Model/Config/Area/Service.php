<?php

class Df_Spsr_Model_Config_Area_Service extends Df_Shipping_Model_Config_Area_Service {



	/**
	 * @return string
	 */
	public function getInsurer () {

		/** @var string $result  */
		$result =
			$this->getVar (
				self::KEY__VAR__INSURER
			)
		;

		df_result_string ($result);
		df_assert (!df_empty ($result));

		return $result;

	}



	/**
	 * @return bool
	 */
	public function enableSmsNotification () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__ENABLE_SMS_NOTIFICATION
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
	public function endorseDeliveryTime () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__ENDORSE_DELIVERY_TIME
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
	public function needPersonalHanding () {

		/** @var bool $result  */
		$result =
			df()->settings()->parseYesNo (
				$this->getVar (
					self::KEY__VAR__PERSONAL_HANDING
					,
					false
				)
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	const KEY__VAR__ENABLE_SMS_NOTIFICATION = 'enable_sms_notification';
	const KEY__VAR__ENDORSE_DELIVERY_TIME = 'endorse_delivery_time';
	const KEY__VAR__INSURER = 'insurer';
	const KEY__VAR__PERSONAL_HANDING = 'personal_handing';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Config_Area_Service';
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

