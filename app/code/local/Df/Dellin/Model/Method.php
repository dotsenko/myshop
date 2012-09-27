<?php

abstract class Df_Dellin_Model_Method extends Df_Shipping_Model_Method {



	/**
	 * @abstract
	 * @return int
	 */
	abstract protected function getCostInRoubles ();





	/**
	 * @override
	 * @return float
	 */
	public function getCost () {

		if (!isset ($this->_cost)) {

			if (0 === $this->getCostInRoubles ()) {
				df_error (
					'Служба «Деловые Линии» не может доставить данный груз в указанное место.'
				);
			}

			/** @var float $result  */
			$result =
				df_helper()->directory()->currency()->convertFromRoublesToBase (
					$this->getCostInRoubles ()
				)
			;

			df_assert_float ($result);

			$this->_cost = $result;

		}


		df_result_float ($this->_cost);

		return $this->_cost;

	}


	/**
	* @var float
	*/
	private $_cost;
	
	
	




	/**
	 * @override
	 * @return string
	 */
	public function getMethodTitle () {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;

		if (!is_null ($this->getRequest()) && (0 !== $this->getTimeOfDelivery())) {
			$result =
				sprintf (
					'%s'
					,
					$this->formatTimeOfDelivery (
						$this->getTimeOfDelivery()
					)
				)
			;
		}

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return bool
	 */
	public function isApplicable () {


		/** @var bool $result  */
		$result =
				(
						Df_Directory_Helper_Country::ISO_2_CODE__RUSSIA
					===
						$this->getRequest()->getOriginCountryId()
				)
			&&
				df_strings_are_equal_ci (
					$this->getRequest()->getOriginCity()
					,
					$this->getRequest()->getDestinationCity()
				)
		;

		df_result_boolean ($result);

		return $result;

	}






	/**
	 * Максимальные габариты
	 *
	 * @return array
	 */
	protected function getCargoDimensions () {

		if (!isset ($this->_cargoDimensions)) {

			/** @var array $result  */
			$result = array ();


			foreach ($this->getRequest()->getQuoteItemsSimple() as $quoteItem) {

				/** @var Mage_Sales_Model_Quote_Item $quoteItem */
				df_assert ($quoteItem instanceof Mage_Sales_Model_Quote_Item);


				/** @var Mage_Catalog_Model_Product $product */
				$product = $quoteItem->getProduct();

				df_assert ($product instanceof Mage_Catalog_Model_Product);


				/** @var array $productDimensions  */
				$productDimensions =
					$this->getRequest()->getProductDimensions (
						$product
						,
						$useSystemNames = true
					)
				;

				df_assert_array ($productDimensions);


				foreach ($productDimensions as $dimensionName => $productDimension) {

					/** @var string $dimensionName */
					df_assert_string ($dimensionName);

					/** @var float $productDimension */
					df_assert_float ($productDimension);


					$result [$dimensionName] =
						max (
							df_a ($result, $dimensionName, 0)
							,
							df()->units()->length()->convertToMetres (
								$productDimension
							)
						)
					;
				}

			}


			df_assert_array ($result);

			$this->_cargoDimensions = $result;

		}


		df_result_array ($this->_cargoDimensions);

		return $this->_cargoDimensions;

	}


	/**
	* @var array
	*/
	private $_cargoDimensions;





	/**
	 * @return float
	 */
	protected function getCargoDimensionHeight () {

		/** @var float $result  */
		$result =
			df_a (
				$this->getCargoDimensions()
				,
				Df_Shipping_Model_Rate_Request::DIMENSION__HEIGHT
			)
		;

		df_result_float ($result);

		return $result;

	}




	/**
	 * @return float
	 */
	protected function getCargoDimensionLength () {

		/** @var float $result  */
		$result =
			df_a (
				$this->getCargoDimensions()
				,
				Df_Shipping_Model_Rate_Request::DIMENSION__LENGTH
			)
		;

		df_result_float ($result);

		return $result;

	}




	/**
	 * @return float
	 */
	protected function getCargoDimensionWidth () {

		/** @var float $result  */
		$result =
			df_a (
				$this->getCargoDimensions()
				,
				Df_Shipping_Model_Rate_Request::DIMENSION__WIDTH
			)
		;

		df_result_float ($result);

		return $result;

	}




	/**
	 * @return Df_Dellin_Model_Config_Area_Service
	 */
	protected function getConfigService () {

		/** @var Df_Dellin_Model_Config_Area_Service $result  */
		$result = $this->getRmConfig()->service();

		df_assert ($result instanceof Df_Dellin_Model_Config_Area_Service);

		return $result;

	}
	
	
	
	
	/**
	 * @return string
	 */
	protected function getLocationDestinationIdForDeliveryTime () {
	
		if (!isset ($this->_locationDestinationIdForDeliveryTime)) {
	
			/** @var string $result  */
			$result = 
				$this->getLocatorForDeliveryTime()->getLocationId (
					$this->getRequest()->getDestinationCity()
					,
					$isOrigin = false
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_locationDestinationIdForDeliveryTime = $result;
	
		}
	
	
		df_result_string ($this->_locationDestinationIdForDeliveryTime);
	
		return $this->_locationDestinationIdForDeliveryTime;
	
	}
	
	
	/**
	* @var string
	*/
	private $_locationDestinationIdForDeliveryTime;	
	
	
	
	
	/**
	 * @return string
	 */
	protected function getLocationDestinationIdForRate () {
	
		if (!isset ($this->_locationDestinationIdForRate)) {
	
			/** @var string $result  */
			$result = 
				$this->getLocatorForRate()->getLocationId (
					$this->getRequest()->getDestinationCity()
					,
					$isOrigin = false
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_locationDestinationIdForRate = $result;
	
		}
	
	
		df_result_string ($this->_locationDestinationIdForRate);
	
		return $this->_locationDestinationIdForRate;
	
	}
	
	
	/**
	* @var string
	*/
	private $_locationDestinationIdForRate;






	/**
	 * @return string
	 */
	private function getLocationOriginIdForDeliveryTime () {

		if (!isset ($this->_locationOriginIdForDeliveryTime)) {

			/** @var string $result  */
			$result =
				$this->getLocatorForDeliveryTime()->getLocationId (
					$this->getRequest()->getOriginCity()
					,
					$isOrigin = true
				)
			;


			df_assert_string ($result);

			$this->_locationOriginIdForDeliveryTime = $result;

		}


		df_result_string ($this->_locationOriginIdForDeliveryTime);

		return $this->_locationOriginIdForDeliveryTime;

	}


	/**
	* @var string
	*/
	private $_locationOriginIdForDeliveryTime;


	
	
	
	
	/**
	 * @return string
	 */
	protected function getLocationOriginIdForRate () {
	
		if (!isset ($this->_locationOriginIdForRate)) {
	
			/** @var string $result  */
			$result = 
				$this->getLocatorForRate()->getLocationId (
					$this->getRequest()->getOriginCity()
					,
					$isOrigin = true
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_locationOriginIdForRate = $result;
	
		}
	
	
		df_result_string ($this->_locationOriginIdForRate);
	
		return $this->_locationOriginIdForRate;
	
	}
	
	
	/**
	* @var string
	*/
	private $_locationOriginIdForRate;
	
	
	

	
	
	/**
	 * @return Df_Dellin_Model_Api_Locator
	 */
	protected function getLocatorForDeliveryTime () {
	
		if (!isset ($this->_locatorForDeliveryTime)) {
	
			/** @var Df_Dellin_Model_Api_Locator_1 $result  */
			$result = 
				df_model (
					Df_Dellin_Model_Api_Locator_1::getNameInMagentoFormat()
				)
			;
	
	
			df_assert ($result instanceof Df_Dellin_Model_Api_Locator_1);
	
			$this->_locatorForDeliveryTime = $result;
	
		}
	
	
		df_assert ($this->_locatorForDeliveryTime instanceof Df_Dellin_Model_Api_Locator_1);
	
		return $this->_locatorForDeliveryTime;
	
	}
	
	
	/**
	* @var Df_Dellin_Model_Api_Locator
	*/
	private $_locatorForDeliveryTime;	
	



	/**
	 * @return Df_Dellin_Model_Api_Locator
	 */
	protected function getLocatorForRate () {

		if (!isset ($this->_locatorForRate)) {

			/** @var Df_Dellin_Model_Api_Locator_1 $result  */
			$result =
				df_model (
					Df_Dellin_Model_Api_Locator_1::getNameInMagentoFormat()
				)
			;


			df_assert ($result instanceof Df_Dellin_Model_Api_Locator_1);

			$this->_locatorForRate = $result;

		}


		df_assert ($this->_locatorForRate instanceof Df_Dellin_Model_Api_Locator_1);

		return $this->_locatorForRate;

	}


	/**
	* @var Df_Dellin_Model_Api_Locator
	*/
	private $_locatorForRate;


	


	
	/**
	 * @return bool
	 */
	protected function hasLocationDestinationTerminal () {
	
		if (!isset ($this->_locationDestinationTerminal)) {
	
			/** @var bool $result  */
			$result = 
				$this->getLocatorForRate()->hasLocationTerminal (
					$this->getRequest()->getDestinationCity()
					,
					$isOrigin = false					
				)
			;
	
	
			df_assert_boolean ($result);
	
			$this->_locationDestinationTerminal = $result;
	
		}
	
	
		df_result_boolean ($this->_locationDestinationTerminal);
	
		return $this->_locationDestinationTerminal;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_locationDestinationTerminal;	
	
	
	
	
	
	
	/**
	 * @return bool
	 */
	protected function hasLocationOriginTerminal () {
	
		if (!isset ($this->_locationOriginTerminal)) {
	
			/** @var bool $result  */
			$result = 
				$this->getLocatorForRate()->hasLocationTerminal (
					$this->getRequest()->getOriginCity()
					,
					$isOrigin = false					
				)
			;
	
	
			df_assert_boolean ($result);
	
			$this->_locationOriginTerminal = $result;
	
		}
	
	
		df_result_boolean ($this->_locationOriginTerminal);
	
		return $this->_locationOriginTerminal;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_locationOriginTerminal;	
	






	/**
	 * @return Df_Dellin_Model_Request_DeliveryTime
	 */
	private function getApiDeliveryTime () {

		if (!isset ($this->_apiDeliveryTime)) {

			/** @var Df_Dellin_Model_Request_DeliveryTime $result  */
			$result =
				df_model (
					Df_Dellin_Model_Request_DeliveryTime::getNameInMagentoFormat()
					,
					array (
						Df_Dellin_Model_Request_DeliveryTime
							::PARAM__LOCATION__DESTINATION =>
								$this->getLocationDestinationIdForDeliveryTime()

						,
						Df_Dellin_Model_Request_DeliveryTime
							::PARAM__LOCATION__ORIGIN =>
								$this->getLocationOriginIdForDeliveryTime()
					)
				)
			;


			df_assert ($result instanceof Df_Dellin_Model_Request_DeliveryTime);

			$this->_apiDeliveryTime = $result;

		}


		df_assert ($this->_apiDeliveryTime instanceof Df_Dellin_Model_Request_DeliveryTime);

		return $this->_apiDeliveryTime;

	}


	/**
	* @var Df_Dellin_Model_Request_DeliveryTime
	*/
	private $_apiDeliveryTime;


	



	/**
	 * @return int
	 */
	private function getTimeOfDelivery () {

		/** @var int $result  */
		$result = 0;

		try {
			$result = $this->getApiDeliveryTime()->getResult();
		}
		catch (Exception $e) {
			/**
			 * Вот здесь вываливать исключительную ситуацию наружу нам не нужно,
			 * потому что метод getTimeOfDelivery вызывается из метода getMethodTitle,
			 * а тот, в свою очередь, из ядра Magento,
			 * и там исключительную ситуаацию никто не обработает.
			 *
			 * Исключительная ситуация может возникнуть, например,
			 * если калькулятор сроков на сайте Деловых Линий не понимает пункт доставки.
			 *
			 * Например, калькулятор тарифов понимает в качестве пункта доставки Якутск,
			 * а калькулятор сроков - не понимает.
			 */

			Mage::logException ($e);
		}


		df_result_integer ($result);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Method';
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


