<?php

class Df_Dellin_Model_Request_Rate_Mini extends Df_Dellin_Model_Request_Rate {



	/**
	 * @return int
	 */
	public function getResult () {
	
		if (!isset ($this->_result)) {


			/** @var phpQueryObject $resultParagraph  */
			$resultParagraph = df_pq ('p.success', $this->getResponseAsPq());

			df_assert ($resultParagraph instanceof phpQueryObject);


			/** @var string $resultParagraphAsText  */
			$resultParagraphAsText = $resultParagraph->text ();

			df_assert_string ($resultParagraphAsText);


			/** @var string $pattern  */
			$pattern = '#(\d+) рублей#u';


			/** @var array $matches  */
			$matches = array ();


			/** @var int|bool $r  */
			$r = preg_match ($pattern, $resultParagraphAsText, $matches);

			df_assert_integer ($r);

			df_assert (1 === $r);



			/** @var int $result  */
			$result = intval (df_a ($matches, 1));

			df_assert_integer ($result);

			df_assert_between ($result, 1);

	
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
					'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
					,
					'Referer' => 'http://dellin.ru/calculator/?mode=small'
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
					'arrivalPoint' => $this->getLocationDestination()


					/**
					 * Пункт доставки
					 */
					,
					'derivalPoint' => $this->getLocationOrigin()


					/**
					 * Тип посылки
					 *
					 * «mail»:
					 * 		Письмо — максимальные габариты 0.3 x 0.21 м.,
					 * 		вес до 0.5 кг. включительно
					 * 		(данное отправление должно помещаться в фирменный пакет)
					 *
					 * «freight»:
							Малогабаритный груз —
					 * 		максимальные габариты 0.36 м. в одном из трёх измерений,
					 * 		сумма трёх измерений не должна превышать 0.65 м.
					 */
					,
					'freightType' =>
						$this->isCargoLetter() ? 'mail' :  'freight'



					/**
					 * Требуется ли страхование груза?
					 */
					,
					'insuranceNeeded' => $this->needInsurance() ? 'on' : null


					/**
					 * Объявленная стоимость груза
					 */
					,
					'statedValue' => $this->getCargoDeclaredValue ()


					/**
					 * Требуется ли дополнительная упаковка?
					 */
					,
					'packageNeeded' => $this->needAdditionalPacking() ? 'on' : null


					/**
					 * Требуется ли дополнительная упаковка?
					 * (всегда это значение: калькулятор не предоставляет выбора типа упаковки)
					 */
					,
					'packages' =>
							$this->needAdditionalPacking()
						?
							'0x9A7F11408F4957D7494570820FCF4549'
						:
							null




					/**
					 * Вес груза в килограммах
					 */
					,
					'sizedWeight' => $this->getCargoWeight()



					/**
					 * Всегда 0
					 */
					,
					'sizedVolume' => 0



					/**
					 * Пункт доставки
					 */
					,
					'calculate' => 'посчитать'

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
					'mode' => 'small'
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
	 * @return float
	 */
	private function getCargoDeclaredValue () {
		return $this->cfg (self::PARAM__CARGO__DECLARED_VALUE);
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
	 * @return bool
	 */
	private function isCargoLetter () {
		return $this->cfg (self::PARAM__CARGO__IS_LETTER);
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
	private function needInsurance () {
		return $this->cfg (self::PARAM__NEED_INSURANCE);
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__CARGO__DECLARED_VALUE, new Zend_Validate_Float())
			->addValidator (self::PARAM__CARGO__IS_LETTER, new Df_Zf_Validate_Boolean())
			->addValidator (self::PARAM__CARGO__WEIGHT, new Zend_Validate_Float())
			->addValidator (self::PARAM__LOCATION__DESTINATION, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__LOCATION__ORIGIN, new Df_Zf_Validate_String())
			->addValidator (self::PARAM__NEED_INSURANCE, new Df_Zf_Validate_Boolean())

			->addValidator (self::PARAM__NEED_ADDITIONAL_PACKING, new Df_Zf_Validate_Boolean())
		;
	}


	const PARAM__CARGO__DECLARED_VALUE = 'cargo__declared_value';
	const PARAM__CARGO__IS_LETTER = 'cargo__is_letter';
	const PARAM__CARGO__WEIGHT = 'cargo__weight';



	const PARAM__LOCATION__DESTINATION = 'location__destination';
	const PARAM__LOCATION__ORIGIN = 'location__origin';

	const PARAM__NEED_INSURANCE = 'need_insurance';

	const PARAM__NEED_ADDITIONAL_PACKING = 'need_additional_packing';








	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Request_Rate_Mini';
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


