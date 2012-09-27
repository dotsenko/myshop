<?php

class Df_WebPay_Model_Config_Service extends Df_Payment_Model_Config_Area_Service {




	/**
	 *  Использовать ли промышленный платёжный сервис WEBPAY в тестовом режиме?
		Укажите в данном поле значение «<i>да</i>»,
		если компания WEBPAY уже предоставила Вам доступ
		к промышленному платёжному сервису,
		однако Вы хотите, чтобы платежи проводились в тестовом режиме.
		<br/>
		В тестовом режиме денежные средства с покупателя не списываются.
	 *
	 * @return bool
	 */
    public function isTestModeOnProduction () {

		/** @var bool $result */
        $result =
			$this->getVar (
				self::KEY__VAR__TEST_ON_PRODUCTION
			)
		;

		$result =
				is_null ($result)
			?
				false
			:
				$this->parseYesNo($result)
		;

		df_result_boolean ($result);

		return $result;
    }



	const KEY__VAR__TEST_ON_PRODUCTION = 'test_on_production';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WebPay_Model_Config_Service';
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


