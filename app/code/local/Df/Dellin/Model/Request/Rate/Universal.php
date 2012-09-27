<?php

class Df_Dellin_Model_Request_Rate_Universal extends Df_Dellin_Model_Request_Rate {



	/**
	 * @return float
	 */
	public function getResult () {
	
		if (!isset ($this->_result)) {
	
			/** @var float $result  */
			$result =

					/**
					 * Надо прибавлять только если служба доставки должна приезжать за грузом на склад магазина.
					 * Иначе — надо прибавить только 100 рублей.
					 */
					(
							$this->needGetCargoFromTheShopStore()
						?
							df_a ($this->getResponseAsArray(), 'derivalToDoor', 0)
						:
							100
					)

				+
					/**
					 * Надо прибавлять только если служба доставки должна доставлять товар до дома покупателя.
					 * Иначе — забор товара в терминале бесплатен.
					 */

					(
							$this->needDeliverCargoToTheBuyerHome()
						?
							df_a ($this->getResponseAsArray(), 'arrivalToDoor', 0)
						:
							0
					)

				+
					df_a ($this->getResponseAsArray(), 'intercity', 0)
			;


			/** @var array $packages  */
			$packages = df_a ($this->getResponseAsArray(), 'packages', array ());

			df_assert_array ($packages);


			foreach ($packages as $packageRate) {

				/** @var string|float $packageRate */

				$result += floatval ($packageRate);

			}



			if ($this->needBagPacking()) {
				$result += 50;
			}


			if ($this->needInsurance()) {
				$result +=
					(0.0014 * $this->getCargoDeclaredValue())
				;
			}

	
			df_assert_float ($result);
	
			$this->_result = $result;
	
		}
	
	
		df_result_float ($this->_result);
	
		return $this->_result;
	
	}
	
	
	/**
	* @var float
	*/
	private $_result;

	
	
	
	/**
	 * @return array
	 */
	public function getResponseAsArray () {
	
		if (!isset ($this->_responseAsArray)) {
	
			/** @var array $result  */
			$result = json_decode ($this->getResponseAsText(), $assoc = true);

			df_assert_array ($result);
	
			$this->_responseAsArray = $result;
	
		}
	
	
		df_result_array ($this->_responseAsArray);
	
		return $this->_responseAsArray;
	
	}
	
	
	/**
	* @var array
	*/
	private $_responseAsArray;





	/**
	 * @override
	 * @return array
	 */
	protected function getCacheKeyParams () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getCacheKeyParams()
				,
				array (
					$this->needGetCargoFromTheShopStore()
					,
					$this->needDeliverCargoToTheBuyerHome()
					,
					$this->needBagPacking()
					,
					$this->needInsurance()
				)
			)
		;


		df_result_array ($result);

		return $result;

	}

	



	/**
	 * @override
	 * @return array
	 */
	protected function getHeaders () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getHeaders()
				,
				array (
					'Accept' => 'application/json, text/javascript, */*; q=0.01'
					,
					'Pragma' => 'no-cache'
					,
					'Referer' => 'http://dellin.ru/calculator/?'
					,
					'X-Requested-With' => 'XMLHttpRequest'
				)
			)
		;


		df_result_array ($result);

		return $result;

	}





	/**
	 * @return array
	 */
	protected function getPostParameters () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getPostParameters()
				,
				array (

					/**
					 * Пункт отправки
					 */
					'derivalPoint' => $this->getLocationOrigin()



					/**
					 * Пункт доставки
					 */
					,
					'arrivalPoint' => $this->getLocationDestination()


					,
					'height' => $this->getCargoDimensionHeight()

					,
					'width' => $this->getCargoDimensionWidth()

					,
					'length' => $this->getCargoDimensionLength()

					,
					'sizedVolume' => $this->getCargoVolume()

					,
					'sizedWeight' => $this->getCargoWeight()


					,
					'oversizedVolume' => 'объём'
					,
					'oversizedWeight' => 'вес'





					/**
					 * Класс опасности груза.
					 * Всегда используем значение «0»
					 */
					,
					'hazardClass' => '0'


					/**
					 * Должна ли служба доставки приезжать за грузом на склад магазина?
					 *
					 * Обратите внимание, что терминалы службы Деловые Линии
					 * присутствуют не в каждом населённом пункте.
					 *
					 * Если терминала в населённом пункте, то значение данной опции игнорируется,
					 * и служба доставки всегда приезжает за грузом на склад магазина.
					 *
					 * «toDoor» — да
					 * «terminal» — нет
					 *
					 */
					,
					'derivalVariant' =>
						$this->getLocationTypeCode (
							$this->needGetCargoFromTheShopStore()
						)


					/**
					 * Должна ли служба доставки доставлять товар до дома покупателя?
					 *
					 * Обратите внимание, что терминалы службы Деловые Линии
					 * присутствуют не в каждом населённом пункте.
					 *
					 * Если терминала в населённом пункте, то значение данной опции игнорируется,
					 * и служба доставки всегда доставляет товар до дома покупателя.
					 *
					 * «toDoor» — да
					 * «terminal» — нет
					 */
					,
					'arrivalVariant' =>
						$this->getLocationTypeCode (
							$this->needDeliverCargoToTheBuyerHome()
						)



//					,
//					'arrivalPointTimeEnd' => '05:00'
//
//					,
//					'arrivalPointTimeStart' => '23:00'

//					,
//					'derivalPointTimeEnd' => '00:00'
//					,
//					'derivalPointTimeStart' => '23:30'



					,
					'derivalSerivces' =>

						df_clean (
							array (

								/**
								 * Требуется ли боковая загрузка?
								 * (несовместимо с верхней загрузкой)
								 */
									$this->needSideCastingAtOrigin()
								?
									'0xb83b7589658a3851440a853325d1bf69'
								:
									null




								/**
								 * Требуется ли верхняя загрузка?
								 * (несовместимо с боковой загрузкой)
								 */
								,
									$this->needTopCastingAtOrigin()
								?
									'0xabb9c63c596b08f94c3664c930e77778'
								:
									null


								/**
								 * Требуется ли гидроборт для погрузки?
								 */
								,
									$this->needCargoTailLoaderAtOrigin()
								?
									'0x92fce2284f000b0241dad7c2e88b1655'
								:
									null


								/**
								 * Требуется ли открытая машина?
								 * (несовместимо с услугой «растентовка»)
								 */
								,
									$this->needOpenCarAtOrigin()
								?
									'0x9951e0ff97188f6b4b1b153dfde3cfec'
								:
									null


								/**
								 * Требуется ли растентовка?
								 * (несовместимо с услугой «открытая машина»)
								 */
								,
									$this->needRemoveAwningAtOrigin()
								?
									'0x818e8ff1eda1abc349318a478659af08'
								:
									null


								/**
								 * Является ли временной интервал забора товара точным?
								 */
	//							,
	//							'0xbd9e13655f56aa2b4f1072f4d9b2e1b9'
							)
						)



					,
					'arrivalSerivces' =>
					    df_clean (
							array (


								/**
								 * Требуется ли боковая выгрузка?
								 * (несовместимо с верхней выгрузкой)
								 */
									$this->needSideCastingAtDestination()
								?
									'0xb83b7589658a3851440a853325d1bf69'
								:
									null


								/**
								 * Требуется ли верхняя выгрузка?
								 * (несовместимо с боковой выгрузкой)
								 */
								,
									$this->needTopCastingAtDestination()
								?
									'0xabb9c63c596b08f94c3664c930e77778'
								:
									null


								/**
								 * Требуется ли для выгрузки манипулятор?
								 */
								,
									$this->needManipulatorAtDestination()
								?
									'0x88f93a2c37f106d94ff9f7ada8efe886'
								:
									null


								/**
								 * Требуется ли для выгрузки открытая машина?
								 */
								,
									$this->needOpenCarAtDestination()
								?
									'0x9951e0ff97188f6b4b1b153dfde3cfec'
								:
									null
							)
						)



					,
					'packages' =>
						df_clean (
							array (

								/**
								 * Требуется ли мягкая упаковка?
								 */
									$this->needSoftPacking()
								?
									'0xADC8F9235B54D4D946E1F2A6972D5A68'
								:
									null


								/**
								 * Требуется ли жёсткая упаковка?
								 */
								,
									$this->needRigidContainer()
								?
									'0x838FC70BAEB49B564426B45B1D216C15'
								:
									null


								/**
								 * Требуется ли дополнительная упаковка?
								 */
								,
									$this->needAdditionalPacking()
								?
									'0x9A7F11408F4957D7494570820FCF4549'
								:
									null


								/**
								 * Требуется ли упаковка в мешки?
								 * Мешки — по 50 рублей, их стоимость надо добавлять вручную
								 */
								,
									$this->needBagPacking()
								?
									'0xAD22189D098FB9B84EEC0043196370D6'
								:
									null


								/**
								 * Требуется ли упаковка в паллетный борт?
								 */
								,
									$this->needCollapsiblePalletBoxAtOrigin()
								?
									'0xBAA65B894F477A964D70A4D97EC280BE'
								:
									null
							)
						)



					/**
					 * Сколько мешков требуется?
					 * (учитывается, если требуется упаковка в мешки)
					 */
					,
					'bagCount' => '1'


					/**
					 * Требуется ли страхование груза?
					 */
					,
					'insuranceCheck' => intval ($this->needInsurance())


					/**
					 * Объявленная стоимость груза
					 * (минимальная объявленная стоимость — 1000 рублей)
					 * Стоимость страховки: 0.14% от стоимости груза.
					 *
					 * Обратите внимание,
					 * что стоимость страховки надо прибавлять к стоимости заказа вручную.
					 *
					 * При этом узнать стоимость страховки можно так:
					 * $('insuranceCheck').attr ('data-percent');
					 *
					 */
					,
					'insuranceValue' => $this->getCargoDeclaredValue ()


					,
					'derivalStreet' => ''
					,
					'derivalStreetBuilding' => ''
					,
					'derivalStreetCode' => ''
					,
					'derivalStreetFraction' => ''
					,
					'derivalStreetHouse' => ''
					,
					'derivalStreetLetter' => ''
					,
					'derivalStreetStructure' => ''

					,
					'arrivalStreet' => ''
					,
					'arrivalStreetBuilding' => ''
					,
					'arrivalStreetCode' => ''
					,
					'arrivalStreetFraction' => ''
					,
					'arrivalStreetHouse' => ''
					,
					'arrivalStreetLetter' => ''
					,
					'arrivalStreetStructure' => ''


					,
					'primaryService' => 'intercity'


				)
			)
		;


		df_result_array ($result);


		return $result;

	}





	/**
	 * @override
	 * @return array
	 */
	protected function getQueryParams () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getQueryParams()
				,
				array (
					'answerType' => 'json'
					,
					'calculate' => 1
					,
					'mode' => 'auto'
				)
			)
		;

		df_result_array ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getRequestMethod () {
		return Zend_Http_Client::POST;
	}




	/**
	 * 	Некоторые калькуляторы допускают несколько одноимённых опций.
	 *  http_build_query кодирует их как a[0]=1&a[1]=2&a[2]=3
	 *  Если калькулятору нужно получить a=1&a=2&a=3,
	 *  то перекройте этот метод и верните true.
	 *
	 * @override
	 * @return bool
	 */
	protected function needPostKeysWithSameName () {
		return true;
	}



	/**
	 * @return float
	 */
	private function getCargoDeclaredValue () {
		return $this->cfg (self::PARAM__CARGO__DECLARED_VALUE);
	}



	/**
	 * @return float
	 */
	private function getCargoDimensionHeight () {
		return $this->cfg (self::PARAM__CARGO__DIMENSION__HEIGHT);
	}



	/**
	 * @return float
	 */
	private function getCargoDimensionLength () {
		return $this->cfg (self::PARAM__CARGO__DIMENSION__LENGTH);
	}



	/**
	 * @return float
	 */
	private function getCargoDimensionWidth () {
		return $this->cfg (self::PARAM__CARGO__DIMENSION__WIDTH);
	}



	/**
	 * @return float
	 */
	private function getCargoVolume () {
		return $this->cfg (self::PARAM__CARGO__VOLUME);
	}



	/**
	 * @return float
	 */
	private function getCargoWeight () {
		return $this->cfg (self::PARAM__CARGO__WEIGHT);
	}




	/**
	 * @return string
	 */
	private function getLocationDestination () {
		return $this->cfg (self::PARAM__LOCATION__DESTINATION);
	}


	/**
	 * @return string
	 */
	private function getLocationOrigin () {
		return $this->cfg (self::PARAM__LOCATION__ORIGIN);
	}




	/**
	 * @param bool $isHome
	 * @return string
	 */
	private function getLocationTypeCode ($isHome) {

		df_param_boolean ($isHome, 0);

		/** @var string $result  */
		$result = $isHome ? 'toDoor' : 'terminal';

		df_result_string ($result);

		return $result;

	}



	/**
	 * @return bool
	 */
	private function needGetCargoFromTheShopStore () {
		return $this->cfg (self::PARAM__NEED_GET_CARGO_FROM_THE_SHOP_STORE);
	}


	/**
	 * @return bool
	 */
	private function needDeliverCargoToTheBuyerHome () {
		return $this->cfg (self::PARAM__NEED_DELIVER_CARGO_TO_THE_BUYER_HOME);
	}

	/**
	 * @return bool
	 */
	private function needInsurance () {
		return $this->cfg (self::PARAM__NEED_INSURANCE);
	}



	/**
	 * @return bool
	 */
	private function needAdditionalPacking () {
		return $this->cfg (self::PARAM__NEED_ADDITIONAL_PACKING);
	}

	/**
	 * @return bool
	 */
	private function needBagPacking () {
		return $this->cfg (self::PARAM__NEED_BAG_PACKING);
	}

	/**
	 * @return bool
	 */
	private function needCargoTailLoaderAtOrigin () {
		return $this->cfg (self::PARAM__NEED_CARGO_TAIL_LOADER_AT_ORIGIN);
	}

	/**
	 * @return bool
	 */
	private function needCollapsiblePalletBoxAtOrigin () {
		return $this->cfg (self::PARAM__NEED_COLLAPSIBLE_PALLET_BOX_AT_ORIGIN);
	}

	/**
	 * @return bool
	 */
	private function needManipulatorAtDestination () {
		return $this->cfg (self::PARAM__NEED_MANIPULATOR_AT_DESTINATION);
	}

	/**
	 * @return bool
	 */
	private function needOpenCarAtDestination () {
		return $this->cfg (self::PARAM__NEED_OPEN_CAR_AT_DESTINATION);
	}

	/**
	 * @return bool
	 */
	private function needOpenCarAtOrigin () {
		return $this->cfg (self::PARAM__NEED_OPEN_CAR_AT_ORIGIN);
	}

	/**
	 * @return bool
	 */
	private function needRemoveAwningAtOrigin () {
		return $this->cfg (self::PARAM__NEED_REMOVE_AWNING_AT_ORIGIN);
	}

	/**
	 * @return bool
	 */
	private function needRigidContainer () {
		return $this->cfg (self::PARAM__NEED_RIGID_CONTAINER);
	}

	/**
	 * @return bool
	 */
	private function needSideCastingAtDestination () {
		return $this->cfg (self::PARAM__NEED_SIDE_CASTING_AT_DESTINATION);
	}

	/**
	 * @return bool
	 */
	private function needSideCastingAtOrigin () {
		return $this->cfg (self::PARAM__NEED_SIDE_CASTING_AT_ORIGIN);
	}

	/**
	 * @return bool
	 */
	private function needSoftPacking () {
		return $this->cfg (self::PARAM__NEED_SOFT_PACKING);
	}

	/**
	 * @return bool
	 */
	private function needTopCastingAtOrigin () {
		return $this->cfg (self::PARAM__NEED_TOP_CASTING_AT_ORIGIN);
	}

	/**
	 * @return bool
	 */
	private function needTopCastingAtDestination () {
		return $this->cfg (self::PARAM__NEED_TOP_CASTING_AT_DESTINATION);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__CARGO__DECLARED_VALUE, new Zend_Validate_Float())
			->addValidator (self::PARAM__CARGO__DIMENSION__HEIGHT, new Zend_Validate_Float())
			->addValidator (self::PARAM__CARGO__DIMENSION__LENGTH, new Zend_Validate_Float())
			->addValidator (self::PARAM__CARGO__DIMENSION__WIDTH, new Zend_Validate_Float())
			->addValidator (self::PARAM__CARGO__VOLUME, new Zend_Validate_Float())
			->addValidator (self::PARAM__CARGO__WEIGHT, new Zend_Validate_Float())
			->addValidator (self::PARAM__LOCATION__DESTINATION, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__LOCATION__ORIGIN, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__NEED_GET_CARGO_FROM_THE_SHOP_STORE, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_DELIVER_CARGO_TO_THE_BUYER_HOME, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_INSURANCE, new Df_Zf_Validate_Boolean())

			->addValidator (self::PARAM__NEED_ADDITIONAL_PACKING, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_BAG_PACKING, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_CARGO_TAIL_LOADER_AT_ORIGIN, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_COLLAPSIBLE_PALLET_BOX_AT_ORIGIN, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_MANIPULATOR_AT_DESTINATION, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_OPEN_CAR_AT_DESTINATION, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_OPEN_CAR_AT_ORIGIN, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_REMOVE_AWNING_AT_ORIGIN, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_RIGID_CONTAINER, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_SIDE_CASTING_AT_DESTINATION, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_SIDE_CASTING_AT_ORIGIN, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_SOFT_PACKING, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_TOP_CASTING_AT_ORIGIN, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__NEED_TOP_CASTING_AT_DESTINATION, new Df_Zf_Validate_Boolean())
		;
	}


	const PARAM__CARGO__DECLARED_VALUE = 'cargo__declared_value';

	const PARAM__CARGO__DIMENSION__HEIGHT = 'cargo__dimension__height';
	const PARAM__CARGO__DIMENSION__LENGTH = 'cargo__dimension__length';
	const PARAM__CARGO__DIMENSION__WIDTH = 'cargo__dimension__width';

	const PARAM__CARGO__VOLUME = 'cargo__volume';
	const PARAM__CARGO__WEIGHT = 'cargo__weight';


	const PARAM__LOCATION__DESTINATION = 'location__destination';
	const PARAM__LOCATION__ORIGIN = 'location__origin';


	const PARAM__NEED_GET_CARGO_FROM_THE_SHOP_STORE = 'need_get_cargo_from_the_shop_store';
	const PARAM__NEED_DELIVER_CARGO_TO_THE_BUYER_HOME = 'need_deliver_cargo_to_the_buyer_home';
	const PARAM__NEED_INSURANCE = 'need_insurance';
	
	
	const PARAM__NEED_ADDITIONAL_PACKING = 'need_additional_packing';
	const PARAM__NEED_BAG_PACKING = 'need_bag_packing';
	const PARAM__NEED_CARGO_TAIL_LOADER_AT_ORIGIN = 'need_cargo_tail_loader_at_origin';
	const PARAM__NEED_COLLAPSIBLE_PALLET_BOX_AT_ORIGIN = 'need_collapsible_pallet_box_at_origin';
	const PARAM__NEED_MANIPULATOR_AT_DESTINATION = 'need_manipulator_at_destination';
	const PARAM__NEED_OPEN_CAR_AT_DESTINATION = 'need_open_car_at_destination';
	const PARAM__NEED_OPEN_CAR_AT_ORIGIN = 'need_open_car_at_origin';
	const PARAM__NEED_REMOVE_AWNING_AT_ORIGIN = 'need_remove_awning_at_origin';
	const PARAM__NEED_RIGID_CONTAINER = 'need_rigid_container';
	const PARAM__NEED_SIDE_CASTING_AT_DESTINATION = 'need_side_casting_at_destination';
	const PARAM__NEED_SIDE_CASTING_AT_ORIGIN = 'need_side_casting_at_origin';
	const PARAM__NEED_SOFT_PACKING = 'need_soft_packing';
	const PARAM__NEED_TOP_CASTING_AT_ORIGIN = 'need_top_casting_at_origin';
	const PARAM__NEED_TOP_CASTING_AT_DESTINATION = 'need_top_casting_at_destination';	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Request_Rate_Universal';
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


