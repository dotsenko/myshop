<?php

class Df_Ems_Model_Carrier extends Df_Shipping_Model_Carrier {



	/**
	 * Обратите внимание, что при браковке запроса в методе proccessAdditionalValidation
	 * модуль может показать на экране оформления заказа диагностическое сообщение,
	 * вернув из этого метода объект класса Mage_Shipping_Model_Rate_Result_Error.
	 *
	 * При браковке запроса в методе collectRates модуль такой возможности лишён.
	 *
	 * @override
	 * @param Mage_Shipping_Model_Rate_Request $request
  	 * @return Df_Shipping_Model_Carrier|Mage_Shipping_Model_Rate_Result_Error|boolean
	 */
	public function proccessAdditionalValidation (Mage_Shipping_Model_Rate_Request $request) {

		/** @var Df_Shipping_Model_Carrier|Mage_Shipping_Model_Rate_Result_Error|boolean $result  */
		$result = parent::proccessAdditionalValidation ($request);

		if (
				(false !== $result)
			&&
				!($result instanceof Mage_Shipping_Model_Rate_Result_Error)
		) {

			try {

				/** @var Df_Shipping_Model_Rate_Request $rmRequest  */
				$rmRequest = Df_Shipping_Model_Rate_Request::createFromMagentoShippingRateRequest ($request);

				df_assert ($rmRequest instanceof Df_Shipping_Model_Rate_Request);


				if (self::MAX_WEIGHT < $rmRequest->getWeightInKilogrammes()) {
					df_error (
						sprintf (
							'Доставка службой EMS для данного заказа недоступна,
							потому что максимальный вес груза для службы EMS составляет %.1f кг.,
							а вес Вашего заказа — %.1f кг.'
							,
							self::MAX_WEIGHT
							,
							$rmRequest->getWeightInKilogrammes()
						)
					);
				}

			}

			catch (Exception $e) {
				/** @var Df_Shipping_Model_Rate_Result_Error $result */
				$result =
					Df_Shipping_Model_Rate_Result_Error::create (
						$this
						,
						$e->getMessage()
					)
				;

			}

		}

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	public function getRmId () {

		/** @var string $result  */
		$result = self::RM__ID;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return bool
	 */
	public function isTrackingAvailable () {
		return true;
	}





	/**
	 * @override
	 * @return string
	 */
	protected function getRmFeatureCode () {
 		return Df_Core_Feature::EMS;
	}





	/** @const float */
	const MAX_WEIGHT = 31.5;

	const RM__ID = 'ems';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Ems_Model_Carrier';
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


