<?php

class Df_Pec_Model_Api_Calculator extends Df_Core_Model_Abstract {




	/**
	 * @return array
	 */
	public function getRates () {
	
		if (!isset ($this->_rates)) {

			$result = array ();


			/** @var array $add  */
			$add = df_a ($this->getApiRequest()->getResponseAsArray(), 'ADD', array ());

			df_assert_array ($add);


			/** @var float $addRate  */
			$addRate = floatval (df_a ($add, 2));

			df_assert_float ($addRate);




			/** @var array $take  */
			$take = df_a ($this->getApiRequest()->getResponseAsArray(), 'take', array ());

			df_assert_array ($take);


			/** @var float $takeRate  */
			$takeRate = floatval (df_a ($take, 2));

			df_assert_float ($takeRate);




			/** @var array $deliver  */
			$deliver = df_a ($this->getApiRequest()->getResponseAsArray(), 'deliver', array ());

			df_assert_array ($deliver);


			/** @var float $deliverRate  */
			$deliverRate = floatval (df_a ($deliver, 2));

			df_assert_float ($deliverRate);




			/** @var array|null $avia  */
			$avia = df_a ($this->getApiRequest()->getResponseAsArray(), 'avia');

			if (!is_null ($avia)) {

				df_assert_array ($avia);

				/** @var float $airBaseRate  */
				$airBaseRate = floatval (df_a ($avia, 2));

				df_assert_float ($airBaseRate);


				/** @var array $methodAir  */
				$methodAir =
					array (
						self::RESULT__RATE => $airBaseRate + $takeRate + $deliverRate  + $addRate
						,
						self::RESULT__DELIVERY_TIME_MIN => 2
						,
						self::RESULT__DELIVERY_TIME_MAX => 3
					)
				;

				$result [Df_Pec_Model_Method_Air::METHOD]= $methodAir;

			}



			/** @var array|null $auto  */
			$auto = df_a ($this->getApiRequest()->getResponseAsArray(), 'auto');

			if (!is_null ($auto)) {

				df_assert_array ($auto);

				/** @var float $airBaseRate  */
				$autoBaseRate = floatval (df_a ($auto, 2));

				df_assert_float ($autoBaseRate);



				/** @var string $deliveryTimeAsText  */
				$deliveryTimeAsText = df_a ($this->getApiRequest()->getResponseAsArray(), 'periods');

				df_assert_string ($deliveryTimeAsText);



				/** @var string $deliveryTimeAsTextWithoutTags  */
				$deliveryTimeAsTextWithoutTags = strip_tags ($deliveryTimeAsText);

				df_assert_string ($deliveryTimeAsTextWithoutTags);



				/** @var string $pattern  */
				$pattern = '#Количество суток в пути: (\d+)#u';

				/** @var array $matches  */
				$matches = array ();

				/** @var bool|int $r  */
				$r = preg_match ($pattern, $deliveryTimeAsTextWithoutTags, $matches);

				df_assert (1 === $r);


				/** @var int $deliveryTime  */
				$deliveryTime = intval (df_a ($matches, 1));

				df_assert_integer ($deliveryTime);




				/** @var array $methodAir  */
				$methodAuto =
					array (
						self::RESULT__RATE => $autoBaseRate + $takeRate + $deliverRate + $addRate
						,
						self::RESULT__DELIVERY_TIME_MIN => $deliveryTime
						,
						self::RESULT__DELIVERY_TIME_MAX => $deliveryTime + 3
					)
				;

				$result [Df_Pec_Model_Method_Ground::METHOD]= $methodAuto;

			}


	
			df_assert_array ($result);
	
			$this->_rates = $result;
	
		}
	
	
		df_result_array ($this->_rates);
	
		return $this->_rates;
	
	}
	
	
	/**
	* @var array
	*/
	private $_rates;


	
	
	
	/**
	 * @return Df_Pec_Model_Request_Rate
	 */
	private function getApiRequest () {
	
		if (!isset ($this->_apiRequest)) {


			/** @var array $params  */
			$params =
				array (
					'deliver' =>
						array (
							'gidro' =>
								intval (
									$this->getServiceConfig()->needCargoTailLoaderAtDestination()
								)
							,
							'moscow' => 0
							,
							'speed' => 0
							,
							'tent' =>
								intval (
									$this->getServiceConfig()->needRemoveAwningAtDestination()
								)
							,
							'town' => $this->getLocationDestinationId()
						)
					,
					'take' =>
						array (
							'gidro' =>
								intval (
									$this->getServiceConfig()->needCargoTailLoaderAtOrigin()
								)
							,
							'moscow' =>
								df_a (
									array (
										Df_Pec_Model_Config_Source_MoscowCargoReceptionPoint
											::OPTION_VALUE__OUTSIDE => 0
										,
										Df_Pec_Model_Config_Source_MoscowCargoReceptionPoint
											::OPTION_VALUE__INSIDE_LITTLE_RING_RAILWAY => 1
										,
										Df_Pec_Model_Config_Source_MoscowCargoReceptionPoint
											::OPTION_VALUE__INSIDE_THIRD_RING_ROAD => 2
										,
										Df_Pec_Model_Config_Source_MoscowCargoReceptionPoint
											::OPTION_VALUE__INSIDE_GARDEN_RING => 3
									)
									,
									$this->getServiceConfig()->getMoscowCargoReceptionPoint()
									,
									0
								)
							,
							'speed' => 0
							,
							'tent' =>
								intval (
									$this->getServiceConfig()->needRemoveAwningAtOrigin()
								)
							,
							'town' => $this->getLocationSourceId()
						)
					,
					'fast' => 0
					,
					'fixedbox' =>
						intval (
							$this->getServiceConfig()->needRigidContainer()
						)
					,
					'night' =>
						intval (
							$this->getServiceConfig()->needOvernightDelivery()
						)
					,
					'places' => $this->getPlaces ()
					,
					'plombir' => $this->getServiceConfig()->getSealCount()
					,
					'strah' =>
						df_helper()->directory()->currency()->convertFromBaseToRoubles (
								$this->getRequest()->getPackageValue()
							*
								$this->getRmConfig()->admin()->getDeclaredValuePercent()
							/
								100
						)
				)
			;

	
			/** @var Df_Pec_Model_Request_Rate $result  */
			$result = 
				df_model (
					Df_Pec_Model_Request_Rate::getNameInMagentoFormat()
					,
					array (
						Df_Pec_Model_Request_Rate::PARAM__POST_PARAMS => $params
					)
				)
			;
	
	
			df_assert ($result instanceof Df_Pec_Model_Request_Rate);
	
			$this->_apiRequest = $result;
	
		}
	
	
		df_assert ($this->_apiRequest instanceof Df_Pec_Model_Request_Rate);
	
		return $this->_apiRequest;
	
	}
	
	
	/**
	* @var Df_Pec_Model_Request_Rate
	*/
	private $_apiRequest;




	/**
	 * @return int
	 */
	private function getLocationDestinationId () {

		if (df_empty ($this->getRequest()->getDestinationCity())) {
			df_error ('Укажите город');
		}

		/** @var int $result  */
		$result =
			df_a (
				Df_Pec_Model_Request_Locations::i()->getResponseAsArray()
				,
				df_helper()->directory()->normalizeLocationName (
					$this->getRequest()->getDestinationCity()
				)
			)
		;


		if (is_null ($result)) {

			df_error (
				sprintf (
					'Служба ПЭК не отправляет грузы в населённый пункт %s.'
					,
					$this->getRequest()->getDestinationCity()
				)
			);

		}


		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return int
	 */
	private function getLocationSourceId () {

		if (df_empty ($this->getRequest()->getDestinationCity())) {
			df_error ('Администратор должен указать город склада магазина');
		}


		/** @var int $result  */
		$result =
			df_a (
				Df_Pec_Model_Request_Locations::i()->getResponseAsArray()
				,
				df_helper()->directory()->normalizeLocationName (
					$this->getRequest()->getOriginCity()
				)
			)
		;


		if (is_null ($result)) {

			df_error (
				sprintf (
					'Доставка из населённого пункта %s невозможна'
					,
					$this->getRequest()->getOriginCity()
				)
			);

		}


		df_result_integer ($result);

		return $result;

	}



	
	
	
	/**
	 * @return array
	 */
	private function getPlaces () {
	
		if (!isset ($this->_places)) {
	
			/** @var array $result  */
			$result = array ();


			foreach ($this->getRequest()->getQuoteItemsSimple() as $quoteItem) {

				/** @var Mage_Sales_Model_Quote_Item $quoteItem */
				df_assert ($quoteItem instanceof Mage_Sales_Model_Quote_Item);


				/** @var Df_Sales_Model_Quote_Item_Extended $quoteItemExtended  */
				$quoteItemExtended =
					Df_Sales_Model_Quote_Item_Extended::create (
						$quoteItem
					)
				;

				df_assert ($quoteItemExtended instanceof Df_Sales_Model_Quote_Item_Extended);


				/** @var Mage_Catalog_Model_Product $product */
				$product = $quoteItem->getProduct();

				df_assert ($product instanceof Mage_Catalog_Model_Product);


				/** @var array $productDimensionsWithSystemLabels  */
				$productDimensionsWithSystemLabels = $this->getRequest()->getProductDimensions ($product);

				df_assert_array ($productDimensionsWithSystemLabels);



				/** @var array $productDimensionsInMeters  */
				$productDimensionsInMeters =
					array_map (
						array (df()->units()->length(), 'convertToMetres')
						,
						array (
							df_a (
								$productDimensionsWithSystemLabels
								,
								df_cfg()->shipping()->product()->getAttributeNameLength()
							)

							,
							df_a (
								$productDimensionsWithSystemLabels
								,
								df_cfg()->shipping()->product()->getAttributeNameWidth()
							)

							,
							df_a (
								$productDimensionsWithSystemLabels
								,
								df_cfg()->shipping()->product()->getAttributeNameHeight()
							)

						)
					)
				;

				df_assert_array ($productDimensionsInMeters);

				/** @var array $place */
				$place =
					array_merge (
						$productDimensionsInMeters
						,
						array (
									df_a ($productDimensionsInMeters, 0)
								*
									df_a ($productDimensionsInMeters, 1)
								*
									df_a ($productDimensionsInMeters, 2)
							,
							df()->units()->weight()->convertToKilogrammes (
								floatval ($product->getWeight())
							)

							,
							/**
							 * Груз габаритен?
							 */
							1
							,
							intval (
								$this->getServiceConfig()->needRigidContainer()
							)
						)
					)
				;

				df_assert_array ($place);

				for ($index = 0; $index < $quoteItemExtended->getQty(); $index++) {
					$result []= $place;
				}
			}

	
			df_assert_array ($result);
	
			$this->_places = $result;
	
		}
	
	
		df_result_array ($this->_places);
	
		return $this->_places;
	
	}
	
	
	/**
	* @var array
	*/
	private $_places;	
	





	/**
	 * @return Df_Shipping_Model_Rate_Request
	 */
	private function getRequest () {
		return $this->cfg (self::PARAM__REQUEST);
	}



	/**
	 * @return Df_Shipping_Model_Config_Facade
	 */
	private function getRmConfig () {
		return $this->cfg (self::PARAM__RM_CONFIG);
	}



	/**
	 * @return Df_Pec_Model_Config_Area_Service
	 */
	private function getServiceConfig () {

		/** @var Df_Pec_Model_Config_Area_Service $result  */
		$result = $this->getRmConfig()->service();

		df_assert ($result instanceof Df_Pec_Model_Config_Area_Service);

		return $result;

	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__REQUEST, Df_Shipping_Model_Rate_Request::getClass ()
			)
			->validateClass (
				self::PARAM__RM_CONFIG, Df_Shipping_Model_Config_Facade::getClass ()
			)
		;
	}


	const PARAM__REQUEST = 'request';
	const PARAM__RM_CONFIG = 'rm_config';



	const RESULT__DELIVERY_TIME_MAX = 'delivery_time_max';
	const RESULT__DELIVERY_TIME_MIN = 'delivery_time_min';
	const RESULT__RATE = 'rate';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pec_Model_Api_Calculator';
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
