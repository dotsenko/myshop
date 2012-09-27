<?php

class Df_Ems_Model_Method extends Df_Shipping_Model_Method {



	/**
	 * @override
	 * @return float
	 */
	public function getCost () {

		/** @var float $result  */
		$result =
			$this->processMoney (
				$this->getApi()->getRate()
			)
		;

		df_result_float ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	public function getMethod () {
		return 'standard';
	}




	/**
	 * @override
	 * @return string
	 */
	public function getMethodTitle () {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;

		if (!is_null ($this->getRequest()) && (0 !== $this->getApi()->getTimeOfDeliveryMin())) {
			$result =
				$this->formatTimeOfDelivery (
					$this->getApi()->getTimeOfDeliveryMin()
					,
					$this->getApi()->getTimeOfDeliveryMax()
				)
			;
		}

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Df_Ems_Model_Api_GetConditions
	 */
	private function getApi () {

		if (!isset ($this->_api)) {

			/** @var Df_Ems_Model_Api_GetConditions $result  */
			$result =
				df_model (
					Df_Ems_Model_Api_GetConditions::getNameInMagentoFormat()
					,
					array (
						Df_Ems_Model_Api_GetConditions::PARAM__SOURCE => $this->getPostingSource()
						,
						Df_Ems_Model_Api_GetConditions::PARAM__DESTINATION => $this->getPostingDestination()
						,
						Df_Ems_Model_Api_GetConditions::PARAM__WEIGHT => $this->getPostingWeight()
						,
						Df_Ems_Model_Api_GetConditions::PARAM__POSTING_TYPE => 'att'
					)
				)
			;


			df_assert ($result instanceof Df_Ems_Model_Api_GetConditions);

			$this->_api = $result;

		}


		df_assert ($this->_api instanceof Df_Ems_Model_Api_GetConditions);

		return $this->_api;

	}


	/**
	* @var Df_Ems_Model_Api_GetConditions
	*/
	private $_api;




	/**
	 * @return string
	 */
	private function getPostingDestination () {

		/** @var Df_Ems_Model_Converter_Location_ToServiceFormat $converter */
		$converter =
			df_model (
				Df_Ems_Model_Converter_Location_ToServiceFormat::getNameInMagentoFormat()
				,
				array (
					Df_Ems_Model_Converter_Location_ToServiceFormat
						::PARAM__CITY => $this->getRequest()->getDestCity()
					,
					Df_Ems_Model_Converter_Location_ToServiceFormat
						::PARAM__REGION_ID => $this->getRequest()->getDestRegionId()
					,
					Df_Ems_Model_Converter_Location_ToServiceFormat
						::PARAM__COUNTRY_ID => $this->getRequest()->getDestCountryId()
				)
			)
		;

		df_assert ($converter instanceof Df_Ems_Model_Converter_Location_ToServiceFormat);


		/** @var string $result  */
		$result = $converter->getResult();


		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getPostingSource () {

		if (!isset ($this->_postingSource)) {

			/** @var Df_Shipping_Model_Rate_Request $rmRequest  */
			$rmRequest = Df_Shipping_Model_Rate_Request::createFromMagentoShippingRateRequest ($this->getRequest());

			df_assert ($rmRequest instanceof Df_Shipping_Model_Rate_Request);

			/** @var Df_Ems_Model_Converter_Location_ToServiceFormat $converter */
			$converter =
				df_model (
					Df_Ems_Model_Converter_Location_ToServiceFormat::getNameInMagentoFormat()
					,
					array (
						Df_Ems_Model_Converter_Location_ToServiceFormat
							::PARAM__CITY => $rmRequest->getOriginCity()
						,
						Df_Ems_Model_Converter_Location_ToServiceFormat
							::PARAM__REGION_ID => $rmRequest->getOriginRegionId()
						,
						Df_Ems_Model_Converter_Location_ToServiceFormat
							::PARAM__COUNTRY_ID => $rmRequest->getOriginCountryId()
					)
				)
			;

			df_assert ($converter instanceof Df_Ems_Model_Converter_Location_ToServiceFormat);


			/** @var string $result  */
			$result = $converter->getResult();

			df_assert_string ($result);

			$this->_postingSource = $result;

		}


		df_result_string ($this->_postingSource);

		return $this->_postingSource;

	}


	/**
	* @var string
	*/
	private $_postingSource;







	/**
	 * @return float
	 */
	private function getPostingWeight () {

		/** @var float $result  */
		$result = $this->getRequest()->getWeightInKilogrammes();

		df_result_float ($result);

		return $result;

	}




	/**
	 * @param float $amount
	 * @return float
	 */
	private function processMoney ($amount) {

		df_param_float ($amount, 0);

		/** @var float $result  */
		$result =
			Mage::app()->getStore()->roundPrice (
				/**
				 * Обратите внимание, что перевод из одной валюты в другую
				 * надо осуществлять только в направлении "базовая валюта" => "второстепенная валюта",
				 * но не наоборот
				 * (Magento не умеет выполнять первод "второстепенная валюта" => "базовая валюта"
				 * даже при наличии курса "базовая валюта" => "второстепенная валюта",
				 * и возбуждает исключительную ситуацию).
				 */
				df_helper()->directory()->currency()->convertFromRoublesToBase (
					$amount
				)
			)
		;

		df_result_float ($result);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Ems_Model_Method';
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


