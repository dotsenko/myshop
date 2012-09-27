<?php

class Df_IPay_Model_Payment extends Df_Payment_Model_Method_WithRedirect {



    /**
     * Retrieve block type for method form generation
     *
	 * @override
     * @return string
     */
    public function getFormBlockType() {

		/** @var string $result  */
        $result = self::RM__FORM_BLOCK_TYPE;

		df_result_string ($result);

		return $result;
    }





    /**
     * Retrieve block type for display method information
     *
	 * @override
     * @return string
     */
	public function getInfoBlockType() {

		/** @var string $result  */
		$result = self::RM__INFO_BLOCK_TYPE;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return string|null
	 */
	public function getMobileNetworkOperator () {

		/** @var string|null $result  */
		$result =
			$this->getInfoInstance()
				->getAdditionalInformation (
					self::INFO_KEY__MOBILE_NETWORK_OPERATOR
				)
		;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}







	/**
	 * @param int|string|null|Mage_Core_Model_Store $storeId [optional]
	 * @return Df_IPay_Model_Config_Facade
	 */
	public function getRmConfig ($storeId = null) {

		/** @var Df_IPay_Model_Config_Facade $result  */
		$result = parent::getRmConfig ($storeId);

		df_assert ($result instanceof Df_IPay_Model_Config_Facade);

		return $result;

	}






	/**
	 * @override
	 * @return string
	 */
	public function getRmId () {

		/** @var string $result  */
		$result = self::RM__ID;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	protected function getConfigClassServiceMf () {

		/** @var string $result  */
		$result = Df_IPay_Model_Config_Service::getNameInMagentoFormat();

		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return array
	 */
	protected function getCustomInformationKeys () {

		/** @var array $result  */
		$result =
			array_merge (
				parent::getCustomInformationKeys()
				,
				array (
					self::INFO_KEY__MOBILE_NETWORK_OPERATOR
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
	protected function getRequestPaymentClassMf () {

		/** @var string $result  */
		$result = Df_IPay_Model_Request_Payment::getNameInMagentoFormat();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	protected function getRmConfigClassMf () {

		/** @var string $result  */
		$result = Df_IPay_Model_Config_Facade::getNameInMagentoFormat();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @override
	 * @return string
	 */
	protected function getRmFeatureCode () {

		/** @var string $result  */
		$result = Df_Core_Feature::IPAY;

		df_result_string ($result);

		return $result;

	}





	const INFO_KEY__MOBILE_NETWORK_OPERATOR = 'df_ipay__mobile_network_operator';


	const RM__ID = 'ipay';

	const RM__FORM_BLOCK_TYPE = 'df_ipay/form';
	const RM__INFO_BLOCK_TYPE = 'df_ipay/info';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_IPay_Model_Payment';
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


