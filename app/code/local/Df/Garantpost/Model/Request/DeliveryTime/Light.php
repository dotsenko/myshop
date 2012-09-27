<?php

class Df_Garantpost_Model_Request_DeliveryTime_Light extends Df_Garantpost_Model_Request_DeliveryTime {



	/**
	 * @return int
	 */
	public function getMax () {

		/** @var int $result  */
		$result = df_a ($this->getResultAsInterval (), 1, 0);

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getMin () {

		/** @var int $result  */
		$result = df_a ($this->getResultAsInterval (), 0, 0);

		df_result_integer ($result);

		return $result;

	}



	
	
	/**
	 * @return array
	 */
	public function getResultAsInterval () {
	
		if (!isset ($this->_resultAsInterval)) {

			/** @var phpQueryObject $pqDeliveryTime  */
			$pqDeliveryTime =
				df_pq (
					'#body_min_height table:first tr.text:last td:last'
					,
					$this->getResponseAsPq()
				)
			;

			df_assert ($pqDeliveryTime instanceof phpQueryObject);



			/** @var string $deliveryTimeAsText  */
			$deliveryTimeAsText = df_trim ($pqDeliveryTime->text());

			df_assert_string ($deliveryTimeAsText);



			/** @var array $result  */
			$result =
				explode (
					'-'
					,
					$deliveryTimeAsText
				)
			;

	
			df_assert_array ($result);
	
			$this->_resultAsInterval = $result;
	
		}
	
	
		df_result_array ($this->_resultAsInterval);
	
		return $this->_resultAsInterval;
	
	}
	
	
	/**
	* @var array
	*/
	private $_resultAsInterval;	
	
	


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
					'Referer' => 'http://www.garantpost.ru/tools/transit/'
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
					'if_submit' => 1

		            ,
					self::POST_PARAM__LOCATION_DESTINATION_ID => $this->getLocationDestinationId()
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
	protected function getQueryPath () {
		return '/tools/transit/';
	}





	/**
	 * @return int
	 */
	private function getLocationDestinationId () {
		return $this->cfg (self::PARAM__LOCATION_DESTINATION_ID);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (self::PARAM__LOCATION_DESTINATION_ID, new Df_Zf_Validate_Int())
		;
	}





	const PARAM__LOCATION_DESTINATION_ID = 'location_destination_id';
	const POST_PARAM__LOCATION_DESTINATION_ID = 'city';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Request_DeliveryTime_Light';
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


