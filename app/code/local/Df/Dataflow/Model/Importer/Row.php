<?php



abstract class Df_Dataflow_Model_Importer_Row extends Df_Core_Model_Abstract {



	/**
	 * @return Df_Dataflow_Model_Importer_Row
	 */
	abstract public function import ();




	/**
	 * Возвращает значение конкретного параметра импорта,
	 * общего для всех строк импортируемых данных.
	 *
	 * Как правило, общие параметры используются в качестве параметров по умолчанию.
	 *
	 * @param string $paramName
	 * @param string|null $defaultValue [optional]
	 * @return string|null
	 */
	protected function getConfigParam ($paramName, $defaultValue = null) {

		df_param_string ($paramName, 0);

		if (!is_null ($defaultValue)) {
			df_param_string ($defaultValue, 1);
		}


		/** @var string|null $result  */
		$result =
			$this->getConfig()->getParam ($paramName, $defaultValue)
		;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}






	/**
	 * Возвращает значения параметров импорта, общих для всех строк импортируемых данных.
	 *
	 * Как правило, общие параметры используются в качестве параметров по умолчанию.
	 *
	 * @return Df_Dataflow_Model_Import_Config
	 */
	protected function getConfig () {

		/** @var Df_Dataflow_Model_Import_Config $result  */
		$result =
			df_helper()->dataflow()->import()->getConfig()
		;

		df_assert ($result instanceof Df_Dataflow_Model_Import_Config);

		return $result;

	}





	/**
	 * @return Df_Dataflow_Model_Import_Abstract_Row
	 */
	protected function getRow () {

		/** @var Df_Dataflow_Model_Import_Abstract_Row $result  */
		$result =
			$this->cfg (self::PARAM_ROW)
		;

		df_assert ($result instanceof Df_Dataflow_Model_Import_Abstract_Row);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Row';
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



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM_ROW, Df_Dataflow_Model_Import_Abstract_Row::getClass()
			)
		;
	}



	const PARAM_ROW = 'row';

}


