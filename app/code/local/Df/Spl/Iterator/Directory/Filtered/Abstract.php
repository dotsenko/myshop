<?php


abstract class Df_Spl_Iterator_Directory_Filtered_Abstract extends DirectoryIterator {

	/**
	 * @abstract
	 * @return Zend_Validate_Interface
	 */
	abstract protected function createValidator ();

	/**
	 * @var array
	 */
	private $_params = array ();



	/**
	 * @return array
	 */
	protected function getParams () {
		return $this->_params;
	}

	/**
	 * @param string $name
	 * @param null|mixed $default
	 * @param bool $required
	 * @return mixed
	 */
	protected function getParam ($name, $default = NULL, $required = false) {
		df_assert (
			!$required || isset ($this->_params [$name])
			,
			sprintf (
				"%s: required parameter %s is absent"
				,
				get_class ($this)
				,
				$name
			)
		)
		;
		return df_a ($this->getParams (), $name, $default);
	}


	public function __construct ($path, $params = array ()) {
		$this->_params = $params;
		parent::__construct ($path);
	}



	/**
	 * @return bool
	 */
	public function valid () {
		$result = parent::valid ();
		if ($result) {
			$result = $this->getValidator ()->isValid ($this->current());
			if (!$result) {
				$this->next ();
				$result = $this->valid ();
			}
		}
		return $result;
	}


	/**
	 * @var Zend_Validate_Interface
	 */
	private $_validator;

	/**
	 * @return Zend_Validate_Interface
	 */
	protected function getValidator () {
		if (!isset ($this->_validator)) {
			$this->_validator =
				$this->createValidator ()
			;
		}
		return $this->_validator;
	}


}
