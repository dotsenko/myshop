<?php

abstract class Df_1C_Model_Cml2_Import extends Df_Core_Model_Abstract {



	/**
	 * @abstract
	 * @return Df_1C_Model_Cml2_Import
	 */
	abstract public function process ();





	/**
	 * @return Df_1C_Helper_Cml2_Registry
	 */
	protected function getRegistry () {

		/** @var Df_1C_Helper_Cml2_Registry $result  */
		$result = df_helper()->_1c()->cml2()->registry();

		df_assert ($result instanceof Df_1C_Helper_Cml2_Registry);

		return $result;

	}





	/**
	 * @return Varien_Simplexml_Element
	 */
	protected function getSimpleXmlElement () {
		return $this->cfg (self::PARAM__SIMPLE_XML);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__SIMPLE_XML, 'Varien_Simplexml_Element'
			)
		;
	}



	const PARAM__SIMPLE_XML = 'simple_xml';


	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import';
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
