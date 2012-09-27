<?php

class Df_IPay_Model_Config_Service extends Df_Payment_Model_Config_Area_Service {



	/**
	 * @override
	 * @return string
	 */
	public function getUrlPaymentPage () {

		/** @var string $result  */
		$result =
				$this->isTestMode()
			?
				parent::getUrlPaymentPage()
			:
				df_a (
					$this->getMobileNetworkOperatorParams ()
					,
					'payment-page'
				)
		;


		df_assert_string ($result);

		return $result;

	}






	/**
	 * @return string|null
	 */
	private function getMobileNetworkOperator () {

		/** @var string|null $result  */
		$result = $this->getPaymentMethod()->getMobileNetworkOperator();

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}





	/**
	 * @return array
	 */
	private function getMobileNetworkOperatorParams () {

		/** @var array $result  */
		$result =
			df_a (
				$this->getConstManager()->getAvailablePaymentMethodsAsCanonicalConfigArray ()
				,
				$this->getMobileNetworkOperator ()
			)
		;

		df_result_array ($result);

		return $result;

	}







	/**
	 * @return Df_IPay_Model_Payment
	 */
	private function getPaymentMethod () {

		/** @var Df_IPay_Model_Payment $result  */
		$result = $this->getVarManager()->getPaymentMethod ();

		df_assert ($result instanceof Df_IPay_Model_Payment);

		return $result;

	}



	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_IPay_Model_Config_Service';
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


