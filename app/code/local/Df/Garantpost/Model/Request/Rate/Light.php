<?php

class Df_Garantpost_Model_Request_Rate_Light extends Df_Garantpost_Model_Request_Rate {


	/**
	 * @return float
	 */
	public function getResult () {

		if (!isset ($this->_result)) {


			/** @var phpQueryObject $pqRate  */
			$pqRate = df_pq ('.tarif [name="i_tariff_1"]', $this->getResponseAsPq());

			df_assert ($pqRate instanceof phpQueryObject);


			/** @var float $cost  */
			$result = floatval ($pqRate->val());


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
	protected function getPostParameters () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getPostParameters()
				,
				array (
					self::POST_PARAM__LOCATION_ORIGIN_ID => $this->getLocationOriginId()
					,
					/**
					 * express — обычная доставка
					 * op — оплата получателем
					 */
					self::POST_PARAM__SERVICE => $this->getService()
					,
					self::POST_PARAM__LOCATION_DESTINATION_ID => $this->getLocationDestinationId()
					,
					self::POST_PARAM__WEIGHT => $this->getWeight()
				)
			)
		;

		df_result_array ($result);


		return $result;

	}




	/**
	 * @return int
	 */
	private function getLocationDestinationId () {
		return $this->cfg (self::PARAM__LOCATION_DESTINATION_ID);
	}



	/**
	 * @return int
	 */
	private function getLocationOriginId () {
		return $this->cfg (self::PARAM__LOCATION_ORIGIN_ID);
	}





	/**
	 * @return string
	 */
	private function getService () {
		return $this->cfg (self::PARAM__SERVICE);
	}



	/**
	 * @return string
	 */
	private function getWeight () {
		return $this->cfg (self::PARAM__WEIGHT);
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__LOCATION_DESTINATION_ID, new Df_Zf_Validate_Int())
			->addValidator (self::PARAM__LOCATION_ORIGIN_ID, new Df_Zf_Validate_Int())
			->addValidator (self::PARAM__SERVICE, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__WEIGHT, new Zend_Validate_Float())
		;
	}





	const PARAM__LOCATION_DESTINATION_ID = 'location_destination_id';
	const PARAM__LOCATION_ORIGIN_ID = 'location_origin_id';
	const PARAM__SERVICE = 'service';
	const PARAM__WEIGHT = 'weight';



	const POST_PARAM__LOCATION_DESTINATION_ID = 'i_to_1';
	const POST_PARAM__LOCATION_ORIGIN_ID = 'i_from_1';
	const POST_PARAM__SERVICE = 'i_service_1';
	const POST_PARAM__WEIGHT = 'i_weight_1';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_Rate_Light';
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


