<?php

class Df_Autotrading_Model_Api_Calculator extends Df_Core_Model_Abstract {


	/**
	 * @param string $paramName
	 * @param int $productIndex
	 * @return string
	 */
	public function addProductIndexToParameter ($paramName, $productIndex) {

		/** @var string $result  */
		$result =
			implode (
				'_'
				,
				array (
					$paramName
					,
					$productIndex
				)
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @return float
	 */
	public function getRate () {
		return $this->getApiRequest()->getRate();
	}

	
	
	
	
	/**
	 * @return Df_Autotrading_Model_Request_Rate
	 */
	private function getApiRequest () {
	
		if (!isset ($this->_apiRequest)) {


			/** @var array $params  */
			$params = array ();



			if ($this->getServiceConfig()->checkCargoOnReceipt()) {

				/**
				 * Должна ли служба доставки вскрывать и сверять груз при покупателе?
				 * service__check_cargo_on_receipt
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__CHECK_CARGO_ON_RECEIPT] =
					Df_Autotrading_Model_Request_Rate::POST__CHECK_CARGO_ON_RECEIPT
				;

			}




			/**
			 * Объявленная ценность в том случае, если
			 * служба доставки должна вскрывать и сверять груз при покупателе?
			 *
			 * admin__declared_value_percent
			 */
			$params [Df_Autotrading_Model_Request_Rate::POST__DECLARED_VALUE__FOR_CHECKING_CARGO_ON_RECEIPT] =
					$this->getServiceConfig()->checkCargoOnReceipt()
				?
					df_helper()->directory()->currency()->convertFromBaseToRoubles (
							$this->getRequest()->getPackageValue()
						*
							$this->getRmConfig()->admin()->getDeclaredValuePercent()
						/
							100
					)

				:
					0
			;




			if ($this->getServiceConfig()->needInsurance()) {

				/**
				 * Должна ли служба доставки вскрывать и сверять груз при покупателе?
				 * service__check_cargo_on_receipt
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_INSURANCE] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_INSURANCE
				;

			}




			/**
			 * Объявленная ценность в том случае, если
			 * служба доставки должна страховать груз
			 *
			 * admin__declared_value_percent
			 */
			$params [Df_Autotrading_Model_Request_Rate::POST__DECLARED_VALUE__FOR_INSURANCE] =
					$this->getServiceConfig()->needInsurance()
				?
					df_helper()->directory()->currency()->convertFromBaseToRoubles (
							$this->getRequest()->getPackageValue()
						*
							$this->getRmConfig()->admin()->getDeclaredValuePercent()
						/
							100
					)
				:
					0
			;




			if ($this->getServiceConfig()->canCargoBePutOnASide()) {

				/**
				 * Можно ли переворачивать груз?
				 *
				 * service__can_cargo_be_put_on_a_side
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__CAN_CARGO_BE_PUT_ON_A_SIDE] =
					'on'
				;

			}




			if ($this->getServiceConfig()->makeAccompanyingForms()) {

				/**
				 * Должна ли служба доставки
				 * составлять сопроводительную документацию на груз?
				 *
				 * Опция доступна только если выбрана доставка до дома покупателя.
				 *
				 * service__make_accompanying_forms
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__MAKE_ACCOMPANYING_FORMS] =
					Df_Autotrading_Model_Request_Rate::POST__MAKE_ACCOMPANYING_FORMS
				;

			}




			if ($this->getServiceConfig()->notifySenderAboutDelivery()) {

				/**
				 * Должна ли служба доставки
				 * уведомлять отправителя в письменном виде
				 * о доставке груза получателю?
				 *
				 * service__notify_sender_about_delivery
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NOTIFY_SENDER_ABOUT_DELIVERY] =
					Df_Autotrading_Model_Request_Rate::POST__NOTIFY_SENDER_ABOUT_DELIVERY
				;

			}




			if ($this->getServiceConfig()->needCollapsiblePalletBox()) {

				/**
				 * Нужен ли для груза поддон с деревянными съёмными ограждениями
				 * (евроборт, паллетный борт)?
				 *
				 * service__need_collapsible_pallet_box
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_COLLAPSIBLE_PALLET_BOX] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_COLLAPSIBLE_PALLET_BOX
				;

			}




			if ($this->getServiceConfig()->needTaping()) {

				/**
				 * Нужна ли услуга перетяжки груза обычной клейкой лентой?
				 *
				 * service__need_taping
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_TAPING] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_TAPING
				;

			}




			if ($this->getServiceConfig()->needTapingAdvanced()) {

				/**
				 * Нужна ли услуга перетяжки груза фирменной клейкой лентой?
				 *
				 * service__need_taping_advanced
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_TAPING_ADVANCED] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_TAPING_ADVANCED
				;

			}




			if ($this->getServiceConfig()->needBox()) {

				/**
				 * Нужна ли коробка?
				 *
				 * service__need_box
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_BOX] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_BOX
				;


				/**
				 * Сколько коробок нужно?
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__BOX_PLACES] = 1;

			}




			if ($this->getServiceConfig()->needPalletPacking()) {

				/**
				 * Нужна ли услуга упаковки груза на поддоне (паллете)?
				 *
				 * service__need_pallet_packing
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_PALLET_PACKING] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_PALLET_PACKING
				;


				/**
				 * Сколько нужно поддонов?
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__PALLET_PLACES] = 1;


				/**
				 * Объём паллетированного груза
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__PALLET_VOLUME] = 1;

			}




			if ($this->getServiceConfig()->needBagPacking()) {

				/**
				 * Нужна ли услуга упаковки груза в мешок?
				 *
				 * service__need_bag_packing
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_BAG_PACKING] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_BAG_PACKING
				;


				/**
				 * Сколько нужно мешков?
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__BAG_PLACES] = 1;

			}




			if ($this->getServiceConfig()->needOpenSlatCrate()) {

				/**
				 * Нужна ли услуга обрешётки?
				 *
				 * service__need_open_slat_crate
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_OPEN_SLAT_CRATE] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_OPEN_SLAT_CRATE
				;

			}




			if ($this->getServiceConfig()->needPlywoodBox()) {

				/**
				 * Нужна ли услуга упаковки груза в фанерный ящик?
				 *
				 * service__need_plywood_box
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_PLYWOOD_BOX] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_PLYWOOD_BOX
				;

			}




			if ($this->getServiceConfig()->needCargoTailLoader()) {

				/**
				 * Нужен ли для погрузки и выгрузки гидравлический подъёмник?
				 *
				 * service__need_cargo_tail_loader
				 */
				$params [Df_Autotrading_Model_Request_Rate::POST__NEED_CARGO_TAIL_LOADER] =
					Df_Autotrading_Model_Request_Rate::POST__NEED_CARGO_TAIL_LOADER
				;

			}


			if (df_empty ($this->getRequest()->getOriginCity())) {
				df_error ('Администратор должен указать город склада магазина');
			}

			if (df_empty ($this->getRequest()->getDestinationCity())) {
				df_error ('Укажите город');
			}



			$params =
				array_merge (
					$params
					,
					array (
						Df_Autotrading_Model_Request_Rate::POST__LOCATION__SOURCE =>
							df_text()->ucfirst (
								mb_strtolower (
									$this->getRequest()->getOriginRegionalCenter()
								)
							)


						/**
						 * Допустимые значения:
						 * «В пределах города X»
						 * «Я привезу в филиал»
						 * Какой-нибудь населённый пункт
						 */
						,
						Df_Autotrading_Model_Request_Rate::POST__LOCATION__SOURCE__DETAILS =>
							$this->getLocationOriginInApiFormat ()


						,
						Df_Autotrading_Model_Request_Rate::POST__LOCATION__DESTINATION =>
							df_text()->ucfirst (
								mb_strtolower (
									$this->getRequest()->getDestinationRegionalCenter()
								)
							)


						/**
						 * Допустимые значения:
						 * «В пределы города X»
						 * «Заберут в филиале»
						 * Какой-нибудь населённый пункт
						 */
						,
						Df_Autotrading_Model_Request_Rate::POST__LOCATION__DESTINATION__DETAILS =>
							$this->getLocationDestinationInApiFormat ()
					)
				)
			;



			/** @var int $productIndex  */
			$productIndex = 0;


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
				$productDimensionsWithSystemLabels =
					$this->getRequest()->getProductDimensions ($product)
				;

				df_assert_array ($productDimensionsWithSystemLabels);



				/** @var array $productDimensionsWithServiceLabels  */
				$productDimensionsWithServiceLabels =

					df_array_combine (
						df_map (
							array ($this, 'addProductIndexToParameter')
							,
							array (
								'width'
								,
								'height'
								,
								'length'
							)
							,
							$productIndex
						)
						,
						array_map (
							array (df()->units()->length(), 'convertToMetres')
							,
							array (
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

								,
								df_a (
									$productDimensionsWithSystemLabels
									,
									df_cfg()->shipping()->product()->getAttributeNameLength()
								)

							)
						)
					)
				;

				df_assert_array ($productDimensionsWithServiceLabels);



				$params =
					array_merge (
						$params
						,
						$productDimensionsWithServiceLabels
						,
						array (
							$this->addProductIndexToParameter('weight', $productIndex) =>
								df()->units()->weight()->convertToKilogrammes (
									/**
									 * Здесь нужен вес именно товара, а не строки заказа
									 */
									floatval ($product->getWeight())
								)
							,
							$this->addProductIndexToParameter('quantity', $productIndex) =>
								$quoteItemExtended->getQty()
						)
					)
				;


				$productIndex++;

			}

	
			/** @var Df_Autotrading_Model_Request_Rate $result  */
			$result = 
				df_model (
					Df_Autotrading_Model_Request_Rate::getNameInMagentoFormat()
					,
					array (
						Df_Autotrading_Model_Request_Rate::PARAM__POST_PARAMS => $params
					)
				)
			;
	
	
			df_assert ($result instanceof Df_Autotrading_Model_Request_Rate);
	
			$this->_apiRequest = $result;
	
		}
	
	
		df_assert ($this->_apiRequest instanceof Df_Autotrading_Model_Request_Rate);
	
		return $this->_apiRequest;
	
	}
	
	
	/**
	* @var Df_Autotrading_Model_Request_Rate
	*/
	private $_apiRequest;	


	

	
	/**
	 * @return string
	 */
	private function getLocationDestinationInApiFormat () {
	
		if (!isset ($this->_locationDestinationInApiFormat)) {
	
			/** @var string $result  */
			$result = 
					!$this->getServiceConfig()->needDeliverCargoToTheBuyerHome()
				?
					'Заберут в филиале'
				:
					(
							$this->getRequest()->isDestinationCityRegionalCenter()
						?
							sprintf (
								'В пределы города %s'
								,
								df_text()->ucfirst (
									mb_strtolower (
										$this->getRequest()->getDestinationCity()
									)
								)
							)
						:
							$this->getPeripheralLocationNameInApiFormat (
								$this->getRequest()->getDestinationCity()
								,
								$this->getRequest()->getDestinationRegionalCenter()
							)
					)
			;
	
	
			df_assert_string ($result);
	
			$this->_locationDestinationInApiFormat = $result;
	
		}
	
	
		df_result_string ($this->_locationDestinationInApiFormat);
	
		return $this->_locationDestinationInApiFormat;
	
	}
	
	
	/**
	* @var string
	*/
	private $_locationDestinationInApiFormat;	






	
	/**
	 * @return string
	 */
	private function getLocationOriginInApiFormat () {
	
		if (!isset ($this->_locationOriginInApiFormat)) {
	
			/** @var string $result  */
			$result = 
					!$this->getServiceConfig()->needGetCargoFromTheShopStore()
				?
					'Я привезу в филиал'
				:
					(
							$this->getRequest()->isOriginCityRegionalCenter()
						?
							sprintf (
								'В пределах города %s'
								,
								df_text()->ucfirst (
									mb_strtolower (
										$this->getRequest()->getOriginCity()
									)
								)
							)
						:
							$this->getPeripheralLocationNameInApiFormat (
								$this->getRequest()->getOriginCity()
								,
								$this->getRequest()->getOriginRegionalCenter()
							)
					)
			;
	
	
			df_assert_string ($result);
	
			$this->_locationOriginInApiFormat = $result;
	
		}
	
	
		df_result_string ($this->_locationOriginInApiFormat);
	
		return $this->_locationOriginInApiFormat;
	
	}
	
	
	/**
	* @var string
	*/
	private $_locationOriginInApiFormat;	




	/**
	 * @param string $locationName
	 * @param string $regionalCenterName
	 * @return string
	 */
	private function getPeripheralLocationNameInApiFormat ($locationName, $regionalCenterName) {

		df_param_string ($locationName, 0);
		df_param_string ($regionalCenterName, 1);



		/** @var string $result  */
		$result =
			df_a (
				df_a (
					Df_Autotrading_Model_Request_Locations::i()->getLocations()
					,
					df_helper()->directory()->normalizeLocationName ($regionalCenterName)
					,
					array ()
				)
				,
				df_helper()->directory()->normalizeLocationName ($locationName)
			)
		;


		if (df_empty ($result)) {
			df_error (
				sprintf (
					'2 Служба Автотрейдинг не доставляет грузы в населённый пункт %s'
					,
					$locationName
				)
			);
		}


		df_result_string ($result);

		return $result;

	}
	



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
	 * @return Df_Autotrading_Model_Config_Area_Service
	 */
	private function getServiceConfig () {

		/** @var Df_Autotrading_Model_Config_Area_Service $result  */
		$result = $this->getRmConfig()->service();

		df_assert ($result instanceof Df_Autotrading_Model_Config_Area_Service);

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





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Autotrading_Model_Api_Calculator';
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
