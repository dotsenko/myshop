<?php


class Df_Zf_Validate_Class extends Zend_Validate_Abstract {



	/**
	 * @param  mixed $value
	 * @return bool
	 */
	public function isValid ($value) {
		$result = false;
		if ($this->canBeNull() && (NULL === $value)) {
			$result = true;
		}
//		else if (!is_object ($value)) {
//            $this->_error (self::IS_NOT_OBJECT);
//		}
		else if (!@is_a ($value, $this->getClassName())) {

			$this->_messageTemplates [self::IS_NOT_OF_CLASS] =
				strtr (
					$this->_messageTemplates [self::IS_NOT_OF_CLASS]
					,
					array (
						'%type%' =>
							is_object ($value) ? get_class ($value) : gettype ($value)
					)
				)
			;
			$result = false;
            $this->_error (self::IS_NOT_OF_CLASS);


		}
		else {
			$result = true;
		}
		return
			$result
		;
	}


	const IS_NOT_OF_CLASS = 'IS_NOT_OF_CLASS';


    protected $_messageTemplates = array ();


	/**
	 * @var string
	 */
	private $_className;



	/**
	 * @var array
	 */
	private $_params;


	/**
	 * @param string $className
	 * @param array $params
	 */
	public function __construct ($className, array $params = array ()) {
		$this->_className = $className;
		$this->_params = $params;
	    $this->_messageTemplates [self::IS_NOT_OF_CLASS] =
	        strtr (
				"Требуется объект класса «%class%», но вместо него получена переменная типа «%type%»."
	            ,
				array (
					 '%class%' => $this->_className
				)
	        )
	    ;
	}



	/**
	 * @return bool
	 */
	protected function canBeNull () {
		return $this->getParam (self::PARAM_CAN_BE_NULL, false);
	}



	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getParam ($name, $default = NULL) {
		return df_a ($this->_params, $name, $default);
	}


	/**
	 * @return string
	 */
	protected function getClassName () {
		return $this->_className;
	}


	const PARAM_CAN_BE_NULL = 'canBeNull';

}