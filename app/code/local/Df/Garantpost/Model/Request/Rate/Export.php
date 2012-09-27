<?php

class Df_Garantpost_Model_Request_Rate_Export extends Df_Garantpost_Model_Request_Rate {



	/**
	 * @return int
	 */
	public function getResult () {

		if (!isset ($this->_result)) {

			/** @var phpQueryObject $pqRate  */
			$pqRate = df_pq ('.tarif [name="i_tariff_1"]', $this->getResponseAsPq());

			df_assert ($pqRate instanceof phpQueryObject);



			/** @var int $cost  */
			$result = intval ($pqRate->val());

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
					'calc_type' => 'world'


					/**
					 * term-term — от терминала до терминала
					 * door-term — от двери до терминала
					 * term-door — от терминала до двери
					 * door-door — от двери до двери
					 */
					,
					self::POST_PARAM__SERVICE => 2

					,
					self::POST_PARAM__DESTINATION_COUNTRY_ID => $this->getDestinationCountryId ()

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
	private function getDestinationCountryId () {

		/** @var int $result  */
		$result =
			df_a (
				Df_Garantpost_Model_Request_Countries_ForRate::i()
					->getResponseAsArray()
				,
				$this->getDestinationCountryIso2()
				,
				0
			)
		;


		if (0 === $result) {
			df_error (
				sprintf (
					'Служба Гарантпост не доставляет грузы в страну %s'
					,
					df_helper()->directory()->country()->getByIso2Code (
						$this->getDestinationCountryIso2 ()
					)->getName()
				)
			);
		}

		df_result_integer ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	private function getDestinationCountryIso2 () {
		return $this->cfg (self::PARAM__DESTINATION_COUNTRY_ISO2);
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
			->addValidator (self::PARAM__DESTINATION_COUNTRY_ISO2, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__WEIGHT, new Zend_Validate_Float())
		;
	}




	const PARAM__DESTINATION_COUNTRY_ISO2  = 'destination_country_iso2';
	const PARAM__WEIGHT = 'weight';



	const POST_PARAM__DESTINATION_COUNTRY_ID = 'i_to_1';
	const POST_PARAM__SERVICE = 'i_service_1';
	const POST_PARAM__WEIGHT = 'i_weight_1';






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_Rate_Export';
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


