<?php


class Df_Phpquery_Model_Xml extends Df_Phpquery_Model_Document {



	/**
	 * @param string $text
	 * @return phpQueryObject
	 */
	protected function createPqDocument ($text) {

		df_param_string ($text, 0);

		/** @var phpQueryObject $result */
		$result =
			phpQuery::newDocumentXML (
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
		return 'Df_Phpquery_Model_Xml';
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