<?php

class Df_Garantpost_Model_Method_Heavy_Air extends Df_Garantpost_Model_Method_Heavy {



	/**
	 * @override
	 * @return string
	 */
	public function getMethod () {
		return self::METHOD;
	}



	/**
	 * @override
	 * @return string
	 */
	public function getMethodTitle () {
		return 'воздушная:';
	}




	/**
	 * @abstract
	 * @return string
	 */
	protected function getLocationDestinationSuffix () {
		return 'авиа';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTitleBase () {
		return 'тяжёлый груз, воздушным транспортом';
	}




	const METHOD = 'heavy-air';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Garantpost_Model_Method_Heavy_Air';
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


