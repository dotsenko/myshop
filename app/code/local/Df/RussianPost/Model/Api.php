<?php

class Df_RussianPost_Model_Api extends Df_Core_Model_Abstract {




	/**
	 * @return string[]
	 */
	public function getRatesAsText () {

		/** @var string[] $result  */
		$result = $this->getRequest()->getRatesAsText();

		df_result_array ($result);

		return $result;

	}




	/**
	 * @return float
	 */
	private function getDeclaredValue () {

		/** @var float $result  */
		$result = $this->cfg (self::PARAM__DECLARED_VALUE);

		df_result_float ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getDestinationPostalCode () {

		/** @var string $result */
		$result = $this->getData (self::PARAM__DESTINATION__POSTAL_CODE);

		df_helper()->shipping()->assertPostalCodeDestination ($result);

		return $result;
	}

	
	
	
	/**
	 * @return Df_RussianPost_Model_Request
	 */
	private function getRequest () {
	
		if (!isset ($this->_request)) {
	
			/** @var Df_RussianPost_Model_Request $result  */
			$result = 
				df_model (
					Df_RussianPost_Model_Request::getNameInMagentoFormat()
					,
					array (
						Df_RussianPost_Model_Request::PARAM__POST_PARAMS =>
							array_map (
								array (df_text(), 'convertUtf8ToWindows1251')
								,
								array (
									Df_RussianPost_Model_Request
										::POST_PARAM__SOURCE__POSTAL_CODE => $this->getSourcePostalCode()
									,
									Df_RussianPost_Model_Request
										::POST_PARAM__DECLARED_VALUE => $this->getDeclaredValue()
									,
									'russianpostcalc' => 1
									,
									Df_RussianPost_Model_Request
										::POST_PARAM__DESTINATION__POSTAL_CODE => $this->getDestinationPostalCode()
									,
									Df_RussianPost_Model_Request
										::POST_PARAM__WEIGHT => $this->getWeight()
								)
							)
					)
				)
			;
	
	
			df_assert ($result instanceof Df_RussianPost_Model_Request);
	
			$this->_request = $result;
	
		}
	
	
		df_assert ($this->_request instanceof Df_RussianPost_Model_Request);
	
		return $this->_request;
	
	}
	
	
	/**
	* @var Df_RussianPost_Model_Request
	*/
	private $_request;





	/**
	 * @return string
	 */
	private function getSourcePostalCode () {

		/** @var string $result */
		$result = $this->getData (self::PARAM__SOURCE__POSTAL_CODE);

		df_helper()->shipping()->assertPostalCodeSource ($result);

		return $result;
	}




	/**
	 * @return float
	 */
	private function getWeight () {

		/** @var float $result  */
		$result = $this->cfg (self::PARAM__WEIGHT);

		df_result_float ($result);

		return $result;

	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__DECLARED_VALUE
				,
				new Zend_Validate_Float ()
			)
			/**
			 * Не используем валидаторы для почтовых индексов,
			 * потому что приход сюда почтового индекса в неверном формате
			 * является ошибкой покупателя, а не программиста,
			 * и покупателю нужно показать понятное ему сообщение
			 * вместо сообщения валидатора.
			 */
			->addValidator (
				self::PARAM__WEIGHT
				,
				new Zend_Validate_Float ()
			)
		;
	}





	const PARAM__DECLARED_VALUE = 'declared_value';
	const PARAM__DESTINATION__POSTAL_CODE = 'destination__postal_code';
	const PARAM__SOURCE__POSTAL_CODE = 'source__postal_code';
	const PARAM__WEIGHT = 'weight';







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_RussianPost_Model_Api';
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
