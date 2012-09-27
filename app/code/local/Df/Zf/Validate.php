<?php


abstract class Df_Zf_Validate extends Zend_Validate_Abstract {


	/**
	 * @param bool $itRequired
	 * @return Df_Zf_Validate
	 */
	public function setRequired ($itRequired) {
		$this->_params [self::PARAM_REQUIRED] = $itRequired;
		return $this;
	}



	/**
	 * @return bool
	 */
	protected function isRequired () {
		return $this->cfg (self::PARAM_REQUIRED, true);
	}




	/**
	 * @param array $params
	 */
	public function __construct (array $params = array ()) {
		$this->_params = $params;
	}



	/**
	 * @param string $paramName
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	protected function cfg ($paramName, $defaultValue = NULL) {
		return df_a ($this->_params, $paramName, $defaultValue);
	}


	/**
	 * @var array
	 */
	private $_params;




	const PARAM_REQUIRED = 'required';


}