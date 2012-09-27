<?php


class Df_Zf_Validate_String extends Df_Zf_Validate {




	/**
	 * @param  mixed $value
	 * @return bool
	 */
	public function isValid ($value) {
		if ((null === $value) && !$this->isRequired()) {
			$result = true;
		}
		else if (!is_string ($value)) {
			$result = false;

			$this->_messageTemplates [self::IS_NOT_STRING] =
				strtr (
					$this->_messageTemplates [self::IS_NOT_STRING]
					,
					array (
						'%type%' => gettype ($value)
					)
				)
			;
			$this->_error (self::IS_NOT_STRING);
		}
		else {
			$result = true;
		}
		return
			$result
		;
	}





	const IS_NOT_STRING = 'IS_NOT_STRING';



	/**
	 * @var array
	 */
    protected $_messageTemplates = array(
        self::IS_NOT_STRING => "Требуется строка, но вместо неё получена переменная типа «%type%».",
    );

}