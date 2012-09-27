<?php

abstract class Df_Varien_Data_Form_Processor extends Df_Core_Model_Abstract {


	/**
	 * @abstract
	 * @return Df_Varien_Data_Form_Processor
	 */
	abstract public function process ();



	/**
	 * @param Df_Varien_Data_Form $form
	 * @return Df_Varien_Data_Form_Processor
	 */
	public function setForm (Df_Varien_Data_Form $form) {

		$this->setData (self::PARAM__FORM, $form);

		return $this;

	}




	/**
	 * @return Df_Varien_Data_Form
	 */
	protected function getForm () {

		/** @var Df_Varien_Data_Form $result  */
		$result = $this->cfg (self::PARAM__FORM);

		df_assert ($result instanceof Df_Varien_Data_Form);

		return $result;

	}


	const PARAM__FORM = 'form';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Varien_Data_Form_Processor';
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

