<?php

class Df_EasyPay_Model_Payment extends Df_Payment_Model_Method_WithRedirect {


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
	 * @param int|string|null|Mage_Core_Model_Store $storeId [optional]
	 * @return Df_EasyPay_Model_Config_Facade
	 */
	public function getRmConfig ($storeId = null) {

		/** @var Df_EasyPay_Model_Config_Facade $result  */
		$result = parent::getRmConfig ($storeId);

		df_assert ($result instanceof Df_EasyPay_Model_Config_Facade);

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
		$result = Df_EasyPay_Model_Config_Service::getNameInMagentoFormat();

		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	protected function getRequestPaymentClassMf () {

		/** @var string $result  */
		$result = Df_EasyPay_Model_Request_Payment::getNameInMagentoFormat();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	protected function getRmConfigClassMf () {

		/** @var string $result  */
		$result = Df_EasyPay_Model_Config_Facade::getNameInMagentoFormat();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @override
	 * @return string
	 */
	protected function getRmFeatureCode () {

		/** @var string $result  */
		$result = Df_Core_Feature::EASYPAY;

		df_result_string ($result);

		return $result;

	}




	const RM__ID = 'easypay';

	const RM__FORM_BLOCK_TYPE = 'df_easypay/form';
	const RM__INFO_BLOCK_TYPE = 'df_easypay/info';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_EasyPay_Model_Payment';
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


