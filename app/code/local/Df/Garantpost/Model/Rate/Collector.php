<?php

class Df_Garantpost_Model_Rate_Collector extends Df_Shipping_Model_Rate_Collector {


	
	/**
	 * @override
	 * @return array
	 */
	protected function getMethods () {

		if (!isset ($this->_methods)) {

			/** @var array $result  */
			$result = array ();


			foreach ($this->getMethodClasses () as $class) {

				/** @var string $class */
				df_assert_string ($class);


				/** @var Df_Garantpost_Model_Method $method */
				$method = $this->createMethod ($class);

				df_assert ($method instanceof Df_Garantpost_Model_Method);


				if ($method->isApplicable()) {

					try {
						$method->getCost();
					}
					catch (Exception $e) {
						/**
						 * Намеренно ничего не делаем.
						 * Раньше тут происходила запись исключительной ситуации в системный журнал.
						 * Но нам это не надо, потому что там исключительная ситуация всегда одна -
						 * "Укажите город или хотя бы индекс".
						 * В журнал это писать не надо, а в интерфейсе покупатель всё равно увидит
						 * данное сообщение.
						 */
					}


					$result []= $method;

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
		return 'Df_Garantpost_Model_Rate_Collector';
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


