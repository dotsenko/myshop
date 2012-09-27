<?php


class Df_Zf_Validate_Anything extends Zend_Validate_Abstract {

	/**
	 * @param  mixed $value
	 * @return bool
	 */
	public function isValid ($value) {
		return true;
	}

}