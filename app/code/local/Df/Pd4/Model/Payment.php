<?php


class Df_Pd4_Model_Payment extends Df_Payment_Model_Method_Base {


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
	protected function getConfigClassAdminMf () {

		/** @var string $result  */
		$result =
			Df_Pd4_Model_Config_Admin::getNameInMagentoFormat()
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getRequestPaymentClassMf () {

		/** @var string $result  */
		$result = Df_Pd4_Model_Request_Payment::getNameInMagentoFormat();

		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	protected function getRmFeatureCode () {

		/** @var string $result  */
		$result = Df_Core_Feature::PD4;

		df_result_string ($result);

		return $result;

	}




	const RM__ID = 'pd4';

	const RM__FORM_BLOCK_TYPE = 'df_pd4/form';
	const RM__INFO_BLOCK_TYPE = 'df_pd4/info';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pd4_Model_Payment';
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

