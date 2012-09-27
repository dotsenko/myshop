<?php


class Df_Spl_Iterator_Directory_Filtered extends Df_Spl_Iterator_Directory_Filtered_Abstract {

	/**
	 * @var array
	 */
	private $_params;


	/**
	 * @param string $path
	 * @param array $params
	 *
	 */
	public function __construct ($path, $params = array ()) {
		$this->_params = $params;
		parent::__construct ($path);
	}


	/**
	 * @return Zend_Validate_Interface
	 */
	protected function createValidator () {
		return df_a ($this->_params, "validator", new Df_Zf_Validate_Anything ());
	}


}
