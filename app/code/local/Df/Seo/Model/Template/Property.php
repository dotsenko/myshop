<?php


abstract class Df_Seo_Model_Template_Property extends Df_Core_Model_Abstract {



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Template_Property';
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
	 * @return string
	 */
	abstract public function getValue ();


	/**
	 * @return string
	 */
	public function getName () {
		return $this->cfg (self::PARAM_NAME);
	}


	/**
	 * @return Df_Seo_Model_Template_Object
	 */
	public function getAdapter () {
		return $this->cfg (self::PARAM_ADAPTER);
	}


	const PARAM_ADAPTER = 'adapter';

	const PARAM_NAME = 'name';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (self::PARAM_ADAPTER, Df_Seo_Model_Template_Adapter::getClass())
	        ->addValidator (self::PARAM_NAME, new Df_Zf_Validate_String())
		;
	}

}