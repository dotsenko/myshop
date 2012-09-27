<?php


class Df_Zf_Validate_Array extends Df_Zf_Validate {


	/**
	 * @param  mixed $value
	 * @return bool
	 */
	public function isValid ($value) {
		if ((null === $value) && !$this->isRequired()) {
			$result = true;
		}
		else if (!is_array ($value)) {
			$result = false;

			$this->_messageTemplates [self::IS_NOT_ARRAY] =
				strtr (
					$this->_messageTemplates [self::IS_NOT_ARRAY]
					,
					array (
						'%type%' => gettype ($value)
					)
				)
			;
			$this->_error (self::IS_NOT_ARRAY);
		}
		else {
			$result = true;
		}
		return
			$result
		;
	}


	const IS_NOT_ARRAY = 'IS_NOT_ARRAY';


    protected $_messageTemplates = array(
        self::IS_NOT_ARRAY => "Требуется массив, но вместо него получена переменная типа «%type%».",
    );


}