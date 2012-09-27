<?php

class Df_EasyPay_Block_Form extends Df_Payment_Block_Form {


    /**
     * Перекрываем метод лишь для того,
	 * чтобы среда разработки знала класс способа оплаты
     *
	 * @override
     * @return Df_EasyPay_Model_Payment
     */
	public function getMethod() {

		/** @var Df_EasyPay_Model_Payment $result  */
		$result = parent::getMethod();

		df_assert ($result instanceof Df_EasyPay_Model_Payment);

		return $result;
	}




	/**
	 * @override
	 * @return string
	 */
	public function getTemplate () {

		/** @var string $result  */
		$result = self::RM__TEMPLATE;

		df_result_string ($result);

		return $result;

	}



	const RM__TEMPLATE = 'df/easypay/form.phtml';



	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_EasyPay_Block_Form';
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


