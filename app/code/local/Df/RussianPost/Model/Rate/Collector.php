<?php

class Df_RussianPost_Model_Rate_Collector extends Df_Shipping_Model_Rate_Collector {


	
	/**
	 * @override
	 * @return array
	 */
	protected function getMethods () {
	
		if (!isset ($this->_methods)) {

			/** @var array $result  */
			$result = array ();

			foreach ($this->getApi()->getRatesAsText() as $textualRate) {

				/** @var string $textualRate */
				df_assert_string ($textualRate);

				$result []= $this->createMethodByTextualRate ($textualRate);

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
	 * @return Df_RussianPost_Model_Api
	 */
	private function getApi () {

		if (!isset ($this->_api)) {

			/** @var Df_RussianPost_Model_Api $result  */
			$result =
				df_model (
					Df_RussianPost_Model_Api::getNameInMagentoFormat()
					,
					array (
						Df_RussianPost_Model_Api::PARAM__WEIGHT =>
							$this->getRateRequest()->getWeightInKilogrammes()

						,
						Df_RussianPost_Model_Api::PARAM__DECLARED_VALUE =>
							df_helper()->directory()->currency()->convertFromBaseToRoubles (
								$this->getDeclaredValue ()
							)

						,
						Df_RussianPost_Model_Api::PARAM__SOURCE__POSTAL_CODE =>
							$this->getRateRequest()->getOriginPostalCode()

						,
						Df_RussianPost_Model_Api::PARAM__DESTINATION__POSTAL_CODE =>
							$this->getRateRequest()->getDestinationPostalCode()
					)
				)
			;


			df_assert ($result instanceof Df_RussianPost_Model_Api);

			$this->_api = $result;

		}


		df_assert ($this->_api instanceof Df_RussianPost_Model_Api);

		return $this->_api;

	}


	/**
	* @var Df_RussianPost_Model_Api
	*/
	private $_api;





	/**
	 * @param string $textualRate
	 * @return Df_Shipping_Model_Method
	 */
	private function createMethodByTextualRate ($textualRate) {

		/** @var Df_Shipping_Model_Method $result  */
		$result = null;


		/** @var string $methodClass  */
		$methodClass = null;

		/** @var string $methodTitle  */
		$methodTitle = null;


		/** @var int $titleLength  */
		$titleLength = 0;


		foreach ($this->getCarrier()->getAllowedMethodsAsArray() as $methodData) {

			/** @var array $methodData */
			df_assert_array ($methodData);

			/** @var string $title */
			$title = df_a ($methodData, 'title');

			df_assert_string ($title);


			if (0 === mb_strpos ($textualRate, $title)) {

				/** @var int $currentTitleLength  */
				$currentTitleLength = mb_strlen ($title);

				if ($currentTitleLength > $titleLength) {

					$methodClass = df_a ($methodData, 'class');

					$methodTitle = $title;

				}

			}

		}


		df_assert_string ($methodClass);

		df_assert_string ($methodTitle);


		/** @var Df_RussianPost_Model_Method $result */
		$result = df_model ($methodClass);

		df_assert ($result instanceof Df_RussianPost_Model_Method);


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

		$result->setRateAsText ($textualRate);


		df_assert ($result instanceof Df_Shipping_Model_Method);

		return $result;

	}



	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_RussianPost_Model_Rate_Collector';
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


