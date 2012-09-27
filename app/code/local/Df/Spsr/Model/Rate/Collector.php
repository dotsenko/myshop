<?php

class Df_Spsr_Model_Rate_Collector extends Df_Shipping_Model_Rate_Collector {


	
	/**
	 * @override
	 * @return array
	 */
	protected function getMethods () {
	
		if (!isset ($this->_methods)) {
	
			/** @var array $result  */
			$result = array ();


			foreach ($this->getApi()->getRates() as $rate) {

				/** @var array $rate */
				df_assert_array ($rate);

				$result []= $this->createMethodByRate ($rate);

			}

	
			df_assert_array ($result);
	
			$this->_methods = $result;
	
		}
	
	
		df_result_array ($this->_methods);
	
		return $this->_methods;
	
	}


	/**
	* @var array
	*/
	private $_methods;






	/**
	 * @return Df_Spsr_Model_Api_Calculator
	 */
	private function getApi () {

		if (!isset ($this->_api)) {

			/** @var Df_Spsr_Model_Api_Calculator $result  */
			$result =
				df_model (
					Df_Spsr_Model_Api_Calculator::getNameInMagentoFormat()
					,
					array (
						Df_Spsr_Model_Api_Calculator::PARAM__REQUEST =>
							$this->getRateRequest()

						,
						Df_Spsr_Model_Api_Calculator::PARAM__DECLARED_VALUE =>
							df_helper()->directory()->currency()->convertFromBaseToRoubles (
								$this->getDeclaredValue ()
							)

						,
						Df_Spsr_Model_Api_Calculator::PARAM__RM_CONFIG => $this->getRmConfig()

					)
				)
			;


			df_assert ($result instanceof Df_Spsr_Model_Api_Calculator);

			$this->_api = $result;

		}


		df_assert ($this->_api instanceof Df_Spsr_Model_Api_Calculator);

		return $this->_api;

	}


	/**
	* @var Df_Spsr_Model_Api_Calculator
	*/
	private $_api;





	/**
	 * @param array $rate
	 * @return Df_Shipping_Model_Method
	 */
	private function createMethodByRate (array $rate) {

		/** @var Df_Shipping_Model_Method $result  */
		$result = null;



		/** @var string $rateTitle  */
		$rateTitle = df_a ($rate, Df_Spsr_Model_Request_Rate::RATE__TITLE);

		df_assert_string ($rateTitle);



		/** @var string $methodClass  */
		$methodClass = null;

		/** @var string $methodTitle  */
		$methodTitle = null;



		foreach ($this->getCarrier()->getAllowedMethodsAsArray() as $methodData) {

			/** @var array $methodData */
			df_assert_array ($methodData);

			/** @var string $title */
			$title = df_a ($methodData, 'title');

			df_assert_string ($title);


			if (false !== mb_stripos ($rateTitle, $title)) {

				$methodClass = df_a ($methodData, 'class');

				$methodTitle = $title;

				break;

			}

		}


		df_assert_string ($methodClass);

		df_assert_string ($methodTitle);


		/** @var Df_Spsr_Model_Method $result */
		$result = df_model ($methodClass);

		df_assert ($result instanceof Df_Spsr_Model_Method);


		$result
			->setRequest ($this->getRateRequest())
			->setCarrier (
				$this->getCarrier()->getCarrierCode()
			)

			/**
			 * При оформлении заказа Magento игнорирует данное значение
			 * и берёт заголовок способа доставки из реестра настроек:
			 *
				public function getCarrierName($carrierCode)
				{
					if ($name = Mage::getStoreConfig('carriers/'.$carrierCode.'/title')) {
						return $name;
					}
					return $carrierCode;
				}
			 */
			->setCarrierTitle ($this->getCarrier()->getTitle())
		;


		$result
			->setCostInRoubles (
				floatval (
					df_a (
						$rate, Df_Spsr_Model_Request_Rate::RATE__COST
					)
				)
			)
		;


		$result
			->setTimeOfDeliveryMax (
				intval (
					df_a (
						$rate, Df_Spsr_Model_Request_Rate::RATE__TIME_OF_DELIVERY__MAX
					)
				)
			)
		;



		$result
			->setTimeOfDeliveryMin (
				intval (
					df_a (
						$rate, Df_Spsr_Model_Request_Rate::RATE__TIME_OF_DELIVERY__MIN
					)
				)
			)
		;


		df_assert ($result instanceof Df_Shipping_Model_Method);

		return $result;

	}



	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Spsr_Model_Rate_Collector';
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


