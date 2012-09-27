<?php

class Df_1C_Model_Cml2_SimpleXml_Document extends Df_Core_Model_SimpleXml_Document {



	/**
	 * @return string
	 */
	public function getXml () {

		/** @var string $result  */
		$result =
			/**
			 * Документы в кодировке UTF-8 должны передаваться в 1С:Управление торговлей
			 * с символом BOM в начале.
			 *
			 * @link http://habrahabr.ru/company/bitrix/blog/129156/#comment_4277527
			 */
			df_text()->bomAdd (
				parent::getXml()
			)
		;

		df_result_string ($result);

		return $result;
	}



	/**
	 * @override
	 * @return Df_Varien_Simplexml_Element
	 */
	protected function createElement () {

		/** @var Df_Varien_Simplexml_Element $result  */
		$result = parent::createElement();

		$result
			->addAttributes (
				array (
					'ВерсияСхемы' => '2.05'
					,
					'ДатаФормирования' =>
						implode (
							'T'
							,
							array (
								Zend_Date::now()->toString (self::DATE_FORMAT)
								,
								Zend_Date::now()->toString (Zend_Date::TIME_MEDIUM)
							)
						)
					,
					'ФорматДаты' => 'ДФ=yyyy-MM-dd; ДЛФ=DT'
					,
					'ФорматВремени' => 'ДФ=ЧЧ:мм:сс; ДЛФ=T'
					,
					'РазделительДатаВремя' => 'T'
					,
					'ФорматСуммы' => 'ЧЦ=18; ЧДЦ=2; ЧРД=.'
					,
					'ФорматКоличества' => 'ЧЦ=18; ЧДЦ=2; ЧРД=.'					
				)
			)
		;

		df_assert ($result instanceof Df_Varien_Simplexml_Element);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getTagName() {
		return 'КоммерческаяИнформация';
	}




	/**
	 * @return bool
	 */
//	protected function hasEncodingWindows1251 () {
//		return true;
//	}




	const DATE_FORMAT = 'y-MM-dd';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_SimpleXml_Document';
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


