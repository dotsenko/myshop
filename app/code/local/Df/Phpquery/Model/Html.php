<?php


class Df_Phpquery_Model_Html extends Df_Phpquery_Model_Document {




	/**
	 * @return string
	 */
	public function generateHtml () {

		/** @var string $result  */
		$result =
			$this->getPq()->htmlOuter()
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @param string $text
	 * @return phpQueryObject
	 */
	protected function createPqDocument ($text) {

		df_param_string ($text, 0);

		if (df_empty(df_trim ($text))) {
			/**
			 * Пустые документы нам тоже нужны: в качестве отправной точки.
			 * Пример применения:
			 * вставка покупательского примечания к заказу в шаблон условий обслуживания,
			 * при том, что шаблон условий обслуживания может быть пустым.
			 */
			$text = '<div></div>';
		}

		/** @var phpQueryObject $result */
		$result =
			phpQuery::newDocumentHTML (
				$text
			)
		;

		df_assert ($result instanceof phpQueryObject);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Phpquery_Model_Html';
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