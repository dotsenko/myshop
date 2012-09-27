<?php

class Df_Dellin_Model_Rate_Collector extends Df_Shipping_Model_Rate_Collector {


	
	/**
	 * @override
	 * @return array
	 */
	protected function getMethods () {
	
		if (!isset ($this->_methods)) {

			/** @var array $result  */
			$result = array ();


			/** @var Df_Dellin_Model_Method_Mini $methodMini  */
			$methodMini =
				$this->createMethod (
					Df_Dellin_Model_Method_Mini::getNameInMagentoFormat()
				)
			;

			df_assert ($methodMini instanceof Df_Dellin_Model_Method_Mini);


			/** @var bool $isMiniApplicable  */
			$isMiniApplicable = false;


			try {
				$isMiniApplicable =
						$methodMini->isApplicable()
					&&
						0 < $methodMini->getCost()
				;
			}
			catch (Exception $e) {

			}


			if ($isMiniApplicable) {
				$result []= $methodMini;
			}

			else {

				/** @var Df_Dellin_Model_Method_Universal $methodUniversal  */
				$methodUniversal =
					$this->createMethod (
						Df_Dellin_Model_Method_Universal::getNameInMagentoFormat()
					)
				;

				df_assert ($methodUniversal instanceof Df_Dellin_Model_Method_Universal);


				if ($methodUniversal->isApplicable()) {

					try {
						$result []= $methodUniversal;
					}
					catch (Exception $e) {
						df_log_exception ($e);
					}

				}

			}

	
			df_assert_array ($result);
	
			$this->_methods = $result;
	
		}
	
	
		df_result_array ($this->_methods);
	
		return $this->_methods;
	
	}


	/**
	* @var array
	*/
	private $_methods;






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dellin_Model_Rate_Collector';
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


