<?php


abstract class Df_Seo_Model_Template_Adapter extends Df_Core_Model_Abstract {






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Template_Adapter';
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
	 * @return Df_Seo_Model_Template_Property
	 */
	abstract protected function getPropertyClass ($propertyName);


	/**
	 * Результат вычисления выражения
	 *
	 * @param string $propertyName
	 * @return string
	 */
	public function getPropertyValue ($propertyName) {
		return
				!$this->getProperty ($propertyName)
			?
				null
			:
				$this->getProperty ($propertyName)->getValue ()
		;
	}



	/**
	 * @var array
	 */
	private $_properties = array ();


	/**
	 * @return Df_Seo_Model_Template_Property
	 */
	protected function getProperty ($propertyName) {
		if (!isset ($this->_properties [$propertyName])) {
			$this->_properties [$propertyName] =
					!$this->getPropertyClass ($propertyName)
				?
					null
				:
					df_model (
						$this->getPropertyClass ($propertyName)
						,
						array (
							Df_Seo_Model_Template_Property::PARAM_ADAPTER => $this
							,
							Df_Seo_Model_Template_Property::PARAM_NAME => $propertyName
						)
					)
			;
		}
		return $this->_properties [$propertyName];
	}



	/**
	 * @return Varien_Object
	 */
	public function getObject () {
		return $this->getExpression()->getObject();
	}



	/**
	 * @return string
	 */
	public function getName () {
		return $this->getExpression ()->getObjectName ();
	}



	/**
	 * @return Df_Seo_Model_Template_Processor
	 */
	public function getProcessor () {
		return $this->getExpression ()->getProcessor();
	}


	/**
	 * @return Df_Seo_Model_Template_Expression
	 */
	protected function getExpression () {
		return $this->cfg (self::PARAM_EXPRESSION);
	}


	const PARAM_EXPRESSION = 'expression';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (self::PARAM_EXPRESSION, Df_Seo_Model_Template_Expression::getClass())
		;
	}

}