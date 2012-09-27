<?php


class Df_Spl_Iterator_Filter_Callback extends FilterIterator {


	/**
	 * @var array|string
	 */
	private $_callback;

	/**
	 * @var array
	 */
	private $_callbackParams;

	/**
	 * @param Iterator $iterator
	 * @param array|string $callback
	 * @param array $callbackParams
	 *
	 */
	public function __construct (Iterator $iterator, $callback, $callbackParams = array ()) {
		parent::__construct ($iterator);
		$this->_callback = $callback;
	    $this->_callbackParams = $callbackParams;
	}


	/**
	 * @return bool
	 */
 	public function accept () {
		return
			call_user_func_array (
				$this->_callback
				,
				array_merge (
					array (
						$this->current()
					)
					,
					$this->_callbackParams
				)
			)
		;
	}

}