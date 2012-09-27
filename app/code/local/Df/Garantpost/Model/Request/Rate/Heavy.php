<?php

class Df_Garantpost_Model_Request_Rate_Heavy extends Df_Garantpost_Model_Request_Rate {


	/**
	 * @return int
	 */
	public function getResult () {

		if (!isset ($this->_result)) {

			/** @var phpQueryObject $pqRate  */
			$pqRate = df_pq ('.itog tr:first td', $this->getResponseAsPq())->eq (1);

			df_assert ($pqRate instanceof phpQueryObject);



			/** @var string $rateAsText  */
			$rateAsText = df_trim ($pqRate->text());

			df_assert_string ($rateAsText);


			/** @var string $pattern  */
			$pattern = '#(\d+)#u';


			/** @var array $matches  */
			$matches = array ();


			/** @var bool|int $r  */
			$r = preg_match ($pattern, $rateAsText, $matches);

			df_assert (1 === $r);


			/** @var int $cost  */
			$result = intval (df_a ($matches, 1));

			df_assert_integer ($result);

			$this->_result = $result;

		}


		df_result_integer ($this->_result);

		return $this->_result;

	}


	/**
	* @var int
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
					'calc_type' => 'cargo'

					/**
					 * msk — Москва
					 * obl — Московская область
					 */
					,
					self::POST_PARAM__LOCATION_ORIGIN_ID => $this->getLocationOriginId()


					/**
					 * term-term — от терминала до терминала
					 * door-term — от двери до терминала
					 * term-door — от терминала до двери
					 * door-door — от двери до двери
					 */
					,
					self::POST_PARAM__SERVICE => $this->getService()

					,
					self::POST_PARAM__LOCATION_DESTINATION_NAME =>
						df_text()->convertUtf8ToWindows1251 (
							$this->getLocationDestinationName()
						)

					,
					self::POST_PARAM__WEIGHT => $this->getWeight()
				)
			)
		;

		df_result_array ($result);


		return $result;

	}



	/**
	 * @return string
	 */
	private function getLocationDestinationName () {
		return $this->cfg (self::PARAM__LOCATION_DESTINATION_NAME);
	}



	/**
	 * @return string
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
			->addValidator (self::PARAM__LOCATION_DESTINATION_NAME, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__LOCATION_ORIGIN_ID, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__SERVICE, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__WEIGHT, new Zend_Validate_Float())
		;
	}





	const PARAM__LOCATION_DESTINATION_NAME = 'location_destination_name';
	const PARAM__LOCATION_ORIGIN_ID = 'location_origin_id';
	const PARAM__SERVICE = 'service';
	const PARAM__WEIGHT = 'weight';



	const POST_PARAM__LOCATION_DESTINATION_NAME = 'i_to_1';
	const POST_PARAM__LOCATION_ORIGIN_ID = 'i_from_1';
	const POST_PARAM__SERVICE = 'i_service_1';
	const POST_PARAM__WEIGHT = 'i_weight_1';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_Rate_Heavy';
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


