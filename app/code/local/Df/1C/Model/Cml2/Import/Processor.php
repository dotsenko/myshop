<?php

abstract class Df_1C_Model_Cml2_Import_Processor extends Df_1C_Model_Cml2 {


	/**
	 * @abstract
	 * @return Df_1C_Model_Cml2_Import_Processor
	 */
	abstract public function process ();



	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity
	 */
	protected function getEntity () {
		return $this->cfg (self::PARAM__ENTITY);
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__ENTITY, Df_1C_Model_Cml2_Import_Data_Entity::getClass()
			)
		;
	}



	const PARAM__ENTITY = 'entity';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor';
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


